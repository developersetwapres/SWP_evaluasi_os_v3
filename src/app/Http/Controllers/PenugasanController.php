<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenugasanRequest;
use App\Http\Requests\UpdatePenugasanRequest;
use App\Http\Resources\EvaluatorHomeAssignmentResource;
use App\Http\Resources\EvaluatorOptionResource;
use App\Http\Resources\PenugasanAssignmentOutsourcingResource;
use App\Http\Resources\StatusPenilaianByEvaluatorResource;
use App\Http\Resources\StatusPenilaianByOutsourcingResource;
use App\Models\BobotSkor;
use App\Models\Jabatan;
use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use App\Models\Penugasan;
use App\Models\Siklus;
use App\Services\Penilaian\EvaluationEngineService;
use App\Services\Penilaian\SaranPerbaikanEvaluatorService;
use App\Services\Penugasan\PenugasanDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PenugasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PenugasanDashboardService $service): Response
    {
        $siklus = $service->activeSiklus();

        if (! $siklus) {
            return Inertia::render('admin/penugasan/page', [
                'outsourcing' => [],
                'evaluators' => [],
                'message' => 'Tidak ada siklus aktif',
            ]);
        }

        $data = [
            'outsourcing' => PenugasanAssignmentOutsourcingResource::collection(
                $service->assignmentOutsourcings($siklus)
            )->resolve(),
            'evaluators' => EvaluatorOptionResource::collection(
                $service->evaluatorOptions()
            )->resolve(),
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
    public function store(StorePenugasanRequest $request, Outsourcing $outsourcing): RedirectResponse
    {
        DB::transaction(function () use ($outsourcing, $request) {

            $siklus = Siklus::where('is_active', 1)->firstOrFail();
            $validated = $request->validated();
            $tipePenilais = array_keys($validated);

            $bobotSkors = BobotSkor::select(['id', 'kode_bobot'])
                ->whereIn('kode_bobot', $tipePenilais)
                ->get()
                ->keyBy('kode_bobot');

            $penilaiUserIds = MasterPegawai::select(['id', 'nip', 'name', 'uuid'])
                ->whereIn('uuid', array_values($validated))
                ->with('user:id,userable_id,userable_type')
                ->get()
                ->keyBy('uuid')
                ->map(function (MasterPegawai $pegawai): int {
                    if (! $pegawai->user) {
                        throw ValidationException::withMessages([
                            'penilai' => "Pegawai {$pegawai->name} belum memiliki akun evaluator.",
                        ]);
                    }

                    return $pegawai->user->id;
                });

            foreach ($validated as $tipePenilai => $penilaiUuid) {
                if (! $bobotSkors->has($tipePenilai)) {
                    throw ValidationException::withMessages([
                        $tipePenilai => "Bobot untuk tipe penilai {$tipePenilai} belum dikonfigurasi.",
                    ]);
                }

                Penugasan::updateOrCreate(
                    [
                        'siklus_id' => $siklus->id,
                        'outsourcing_id' => $outsourcing->id,
                        'tipe_penilai' => $tipePenilai,
                    ],
                    [
                        'penilai_id' => $penilaiUserIds->get($penilaiUuid),
                        'bobot_skor_id' => $bobotSkors->get($tipePenilai)->id,
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

    public function homex(PenugasanDashboardService $service): Response
    {
        $siklus = $service->activeSiklus();

        $data = [
            'penugasanPeer' => EvaluatorHomeAssignmentResource::collection(
                $service->evaluatorHomeAssignments(Auth::user())
            )->resolve(),
            'siklusAktif' => $siklus?->title ?? 'Tidak ada siklus aktif',
        ];

        return Inertia::render('evaluator/page', $data);
    }

    public function home(EvaluationEngineService $evaluationEngine): Response
    {
        $user = Auth::user();

        // 1. Ambil semua penugasan milik user login (lengkap untuk hitung nilai)
        $myPenugasans = $user
            ->penugasan()
            ->with([
                'siklus',
                'outsourcings.jabatan',
                'penilaian.indikator.pilar.bobotSkor',
                'bobotSkor',
                'evaluators.userable',
            ])
            ->whereHas('siklus', fn($q) => $q->where('is_active', false))
            ->get();

        // 2. Group berdasarkan siklus
        $semesterHistory = $myPenugasans
            ->groupBy('siklus_id')
            ->map(function ($penugasanBySiklus) use ($evaluationEngine) {
                $siklus = $penugasanBySiklus->first()->siklus;


                // 3. Di dalam siklus, group lagi berdasarkan outsourcing
                $employees = $penugasanBySiklus
                    ->groupBy('outsourcing_id')
                    ->map(function ($penugasanByOutsourcing) use ($evaluationEngine) {
                        $first = $penugasanByOutsourcing->first();

                        return [
                            'id' => $first->outsourcings->id,
                            'uuid' => $first->uuid,
                            'outsourcings' => [
                                'name' => $first->outsourcings->name,
                                'jabatan' => optional($first->outsourcings->jabatan)->nama_jabatan,
                                'image' => $first->outsourcings->image,
                            ],
                            'status' => $first->status,
                            'tipe_penilai' => $first->tipe_penilai,
                            'score' => $evaluationEngine->calculateRawScore($first),
                        ];
                    })
                    ->values();


                return [
                    'id' => $siklus->uuid,
                    'name' => $siklus->title,
                    'period' => sprintf(
                        '%s - %s',
                        optional($siklus->tanggal_mulai)->translatedFormat('F Y'),
                        optional($siklus->tanggal_selesai)->translatedFormat('F Y'),
                    ),
                    'status' => $siklus->is_active ? 'active' : 'completed',
                    'employees' => $employees,
                ];
            })
            ->values();

        //----------------------------------------------------------

        /** @var Outsourcing $outsourcing */
        $outsourcing = $user->userable;

        $siklusAktif = Siklus::query()
            ->select(['id', 'title'])
            ->where('is_active', true)
            ->first();

        if (! $siklusAktif) {
            $typeUser = Auth::user()->userable_type === Outsourcing::class ? 'outsourcing' : 'pegawai';

            return Inertia::render('evaluator/page', [
                'semesterHistory' => $semesterHistory,
                'penugasanPeer' => [],
                'resultScore' => null,
                'resultScoreHistory' => null,
                'typeUser' => $typeUser,
                'siklusAktif' => 'Tidak ada siklus aktif',
            ]);
        }

        $evaluatorsHistory = Penugasan::query()
            ->where('outsourcing_id', $outsourcing->id)
            ->whereNot('siklus_id', $siklusAktif->id)
            ->with([
                'bobotSkor',
                'evaluators.userable',
                'penilaian.indikator.pilar.bobotSkor',
            ])
            ->get();

        $penugasan = Penugasan::query()
            ->where('outsourcing_id', $outsourcing->id)
            ->where('siklus_id', $siklusAktif->id)
            ->with([
                'bobotSkor',
                'evaluators.userable',
                'penilaian.indikator.pilar.bobotSkor',
            ])
            ->get();


        $hasil = $evaluationEngine->calculate($penugasan)['evaluators'];

        $hasilHistory = $evaluatorsHistory
            ->groupBy('siklus_id')
            ->map(function ($penugasanBySiklus) use ($evaluationEngine) {
                $siklus = $penugasanBySiklus->first()->siklus;

                $evaluators = $evaluationEngine->calculate($penugasanBySiklus)['evaluators'];

                return [
                    'id' => $siklus->uuid,
                    'name' => $siklus->title,
                    'period' => sprintf(
                        '%s - %s',
                        optional($siklus->tanggal_mulai)->translatedFormat('F Y'),
                        optional($siklus->tanggal_selesai)->translatedFormat('F Y')
                    ),
                    'status' => $siklus->is_active ? 'active' : 'completed',
                    'evaluators' => $evaluators,
                ];
            })
            ->values();

        $typeUser = Auth::user()->userable_type === Outsourcing::class ? 'outsourcing' : 'pegawai';

        $data = [
            'semesterHistory' => $semesterHistory,
            'penugasanPeer' => Auth::user()->penugasan()
                ->select(['id', 'outsourcing_id', 'siklus_id', 'status', 'uuid', 'tipe_penilai'])
                ->whereHas('siklus', fn($q) => $q->where('is_active', true))
                ->with([
                    'outsourcings:id,uuid,image,name,jabatan_id',
                    'outsourcings.jabatan:id,nama_jabatan',
                ])
                ->get()
                ->map(fn(Penugasan $item) => [
                    'id' => $item->id,
                    'uuid' => $item->uuid,
                    'status' => $item->status,
                    'tipe_penilai' => $item->tipe_penilai,
                    'outsourcings' => [
                        'uuid' => $item->outsourcings->uuid,
                        'image' => $item->outsourcings->image,
                        'name' => $item->outsourcings->name,
                        'jabatan' => $item->outsourcings->jabatan?->nama_jabatan,
                    ],
                ])
                ->values(),
            'resultScore' => $typeUser == 'outsourcing' ? $hasil : null,
            'resultScoreHistory' => $typeUser == 'outsourcing' ? $hasilHistory : null,
            'typeUser' => $typeUser,
            'siklusAktif' => $siklusAktif->title
        ];

        return Inertia::render('evaluator/page', $data);
    }

    public function byOutsourcings(PenugasanDashboardService $service): Response
    {
        return Inertia::render('admin/statuspenilaian/ETXpenilaianByOutsourcing', [
            'outsourcings' => StatusPenilaianByOutsourcingResource::collection(
                $service->statusByOutsourcings()
            )->resolve(),
        ]);
    }

    public function byEvaluators(PenugasanDashboardService $service): Response
    {
        return Inertia::render('admin/statuspenilaian/ETXpenilaianByEvaluator', [
            'evaluators' => StatusPenilaianByEvaluatorResource::collection(
                $service->statusByEvaluators()
            )->resolve(),
        ]);
    }

    public function statusPenilaian(PenugasanDashboardService $service): Response
    {
        $data = [
            'byOutsourcings' => StatusPenilaianByOutsourcingResource::collection(
                $service->statusByOutsourcings()
            )->resolve(),
            'byEvaluators' => StatusPenilaianByEvaluatorResource::collection(
                $service->statusByEvaluators()
            )->resolve(),
        ];

        return Inertia::render('admin/statuspenilaian/page', $data);
    }

    public function reset(Penugasan $penugasan): RedirectResponse
    {
        $penugasan->penilaian()->delete();

        $penugasan->update([
            'catatan' => null,
            'status' => 'incomplete',
        ]);

        return back()->with('success', 'Penugasan berhasil direset.');
    }

    public function saranPerbaikan(
        SaranPerbaikanEvaluatorService $service,
        Request $request
    ): Response {
        $request->validate([
            'jabatan_id' => ['nullable', 'integer', 'exists:jabatans,id'],
        ]);

        $jabatanId = $request->filled('jabatan_id')
            ? $request->integer('jabatan_id')
            : Jabatan::where('kode_jabatan', 'DESAINER_GRAFIS')->value('id');

        return Inertia::render('admin/saranperbaikan/page', [
            'Outsourcings' => $service->saran($jabatanId),

            'allJabatan' => Jabatan::select('id', 'nama_jabatan')
                ->whereNot('kode_jabatan', 'UMUM')
                ->orderBy('nama_jabatan')
                ->get(),

            'selectedJabatanId' => (string) $jabatanId,
        ]);
    }

    public function exportSaranPerbaikan(
        SaranPerbaikanEvaluatorService $service
    ): Response {
        return Inertia::render('exportexcel/exportSaranPerbaikan', [
            'outsourcings' => $service->saran(),
        ]);
    }
}
