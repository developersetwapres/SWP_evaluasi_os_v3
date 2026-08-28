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
use App\Services\Penilaian\SaranPerbaikanEvaluatorService;
use App\Services\Penugasan\PenugasanDashboardService;
use Illuminate\Http\RedirectResponse;
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

    public function home(PenugasanDashboardService $service): Response
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

    public function saranPerbaikan(SaranPerbaikanEvaluatorService $service): Response
    {
        // Get jabatan_id from query parameter if provided
        $jabatanId = request()->input('jabatan_id') ? (int) request()->input('jabatan_id') : null;

        $data = [
            'Outsourcings' => $service->saran($jabatanId),
        ];

        // Get all available jabatan for dropdown options
        $allJabatan = Jabatan::select('id', 'nama_jabatan')
            ->orderBy('nama_jabatan', 'asc')
            ->get();

        $data['allJabatan'] = $allJabatan;

        return Inertia::render('admin/saranperbaikan/page', $data);
    }
}
