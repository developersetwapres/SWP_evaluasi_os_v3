<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Http\Requests\StorePenugasanRequest;
use App\Http\Requests\UpdatePenugasanRequest;
use App\Models\BobotSkor;
use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use App\Models\Siklus;
use App\Services\Penilaian\SaranPerbaikanEvaluatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PenugasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $siklus = Siklus::where('is_active', 1)->first();


        if (!$siklus) {
            ['message' => 'Tidak ada siklus aktif'];
        }

        $outsourcings = Outsourcing::where('is_active', 1)
            ->with([
                'penugasan' => fn($q) =>
                $q->where('siklus_id', $siklus->id)->with('evaluators.userable'),
                'biro',
                'jabatan'
            ])
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($os) {

                $evaluators = [
                    'atasan' => ['name' => null, 'jabatan' => null, 'uuid' => null],
                    'penerima_layanan1' => ['name' => null, 'jabatan' => null, 'uuid' => null],
                    'penerima_layanan2' => ['name' => null, 'jabatan' => null, 'uuid' => null],
                ];

                foreach ($os->penugasan as $p) {
                    if (! array_key_exists($p->tipe_penilai, $evaluators)) {
                        continue;
                    }

                    if ($evaluators[$p->tipe_penilai]['name'] !== null) {
                        continue;
                    }

                    $userable = $p->evaluators?->userable;

                    if (! $userable) {
                        continue;
                    }

                    $evaluators[$p->tipe_penilai] = [
                        'name' => $userable->name,
                        'uuid' => $userable->uuid,
                        'jabatan' => method_exists($userable, 'displayJabatan')
                            ? $userable->displayJabatan()
                            : null,
                    ];
                }

                return [
                    'uuid' => $os->uuid,
                    'image' => $os->image,
                    'name' => $os->name,
                    'jabatan' => $os->jabatan?->nama_jabatan,
                    'biro' => $os->biro?->nama_biro,
                    'nama_jabatan' => $os->jabatan?->nama_jabatan,
                    'evaluators' => $evaluators,
                ];
            });

        $data = [
            'outsourcing' =>  $outsourcings,
            'evaluators' => MasterPegawai::select(['name', 'jabatan', 'kode_biro', 'uuid'])
                ->where('kode_unit', '02')
                ->with('biro')
                ->orderBy('name', 'asc')
                ->get()
        ];


        return Inertia::render('admin/penugasan/page', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenugasanRequest $request, Outsourcing $outsourcing)
    {
        DB::transaction(function () use ($outsourcing, $request) {

            $siklus = Siklus::where('is_active', 1)->firstOrFail();

            foreach ($request->validated() as $tipePenilai => $penilaiUuid) {

                // 1. Bobot skor
                $bobotSkor = BobotSkor::where('kode_bobot', $tipePenilai)
                    ->firstOrFail();


                // 2. Tentukan penilai berdasarkan tipe
                $penilaiUserId = match ($tipePenilai) {
                    'atasan', 'penerima_layanan1', 'penerima_layanan2' => MasterPegawai::where('uuid', $penilaiUuid)
                        ->with('user')
                        ->firstOrFail()
                        ->user
                        ->id,
                };

                // 3. Simpan penugasan
                Penugasan::updateOrCreate(
                    [
                        'siklus_id'      => $siklus->id,
                        'outsourcing_id' => $outsourcing->id,
                        'tipe_penilai'   => $tipePenilai,
                    ],
                    [
                        'penilai_id'    => $penilaiUserId,
                        'bobot_skor_id' => $bobotSkor->id,
                    ]
                );
            }
        });

        return back()->with('success', 'Penugasan penilai berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penugasan $penugasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penugasan $penugasan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenugasanRequest $request, Penugasan $penugasan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penugasan $penugasan)
    {
        //
    }

    public function home(): Response
    {
        $data = [
            'penugasanPeer' => Auth::user()->penugasan()
                ->select(['outsourcing_id', 'siklus_id', 'status', 'uuid', 'tipe_penilai'])
                ->whereHas('siklus', fn($q) => $q->where('is_active', true))
                ->with(['siklus', 'outsourcings'])
                ->get(),
            'siklusAktif' => 'Semester I tahun 2026'
        ];

        return Inertia::render('evaluator/page', $data);
    }

    public function byOutsourcings(): Response
    {
        $outsourcings = app(Outsourcing::class)->byOutsourcings();

        return Inertia::render('admin/statuspenilaian/ETXpenilaianByOutsourcing', [
            'outsourcings' => $outsourcings,
        ]);
    }

    public function byEvaluators(): Response
    {
        $evaluators = app(Penugasan::class)->byEvaluators();

        return Inertia::render('admin/statuspenilaian/ETXpenilaianByEvaluator', [
            'evaluators' => $evaluators,
        ]);
    }

    public function statusPenilaian(): Response
    {
        $data = [
            'byOutsourcings' => app(Outsourcing::class)->byOutsourcings(),
            'byEvaluators' => app(Penugasan::class)->byEvaluators(),
        ];

        return Inertia::render('admin/statuspenilaian/page', $data);
    }

    public function reset(Penugasan $penugasan)
    {
        foreach ($penugasan->penilaian as $key => $penugasan) {
            $penugasan->delete();
        };

        $penugasan->update([
            'catatan' => null,
            'status' => 'incomplete',
        ]);
    }


    public function saranPerbaikan(SaranPerbaikanEvaluatorService $service): Response
    {
        $data = [
            'Outsourcings' => $service->saran()
        ];

        return Inertia::render('admin/saranperbaikan/page', $data);
    }
}
