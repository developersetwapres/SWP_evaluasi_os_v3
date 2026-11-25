<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRekapHasilRequest;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Biro;
use App\Models\Jabatan;
use App\Models\Outsourcing;
use App\Models\Penilaian;
use App\Models\Penugasan;
use App\Http\Resources\RekapHasilResource;
use App\Http\Resources\EvaluationFormResource;
use App\Models\MasterPegawai;
use App\Services\Penilaian\EvaluationEngineService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Penugasan $penugasan, EvaluationEngineService $engine): Response|RedirectResponse
    {
        abort_if(!$penugasan->outsourcings, 404);

        if ($penugasan->evaluators->id !== Auth::id()) {
            return to_route('home');
        }

        $outsourcing = $penugasan->outsourcings()
            ->select(['id','uuid','nip','name','image','jabatan_id','kode_biro'])
            ->with([
                'jabatan:id,nama_jabatan',
                'biro:kode_biro,nama_biro',
            ])
            ->firstOrFail();
        $jabatanId = $outsourcing->jabatan_id;

        $evaluatorUser = $penugasan->evaluators()
            ->select(['id','email','userable_id','userable_type','nip'])
            ->with([
                'userable' => fn(MorphTo $morphTo) => $morphTo->constrain([
                    Outsourcing::class => fn($query) => $query
                        ->select(['id','uuid','nip','name','image','jabatan_id','kode_biro'])
                        ->with([
                            'jabatan:id,nama_jabatan',
                            'biro:kode_biro,nama_biro',
                        ]),
                    MasterPegawai::class => fn($query) => $query
                        ->select(['id','uuid','nip','name','image','jabatan','kode_biro'])
                        ->with('biro:kode_biro,nama_biro'),
                ]),
            ])
            ->firstOrFail();
        $evaluator = $evaluatorUser->userable;

        $data = [
            'outsourcing' => [
                'id' => $outsourcing->id,
                'uuid' => $outsourcing->uuid,
                'name' => $outsourcing->name,
                'image' => $outsourcing->image,
                'jabatan' => $outsourcing->jabatan ? ['nama_jabatan' => $outsourcing->jabatan->nama_jabatan] : null,
                'biro' => $outsourcing->biro ? ['nama_biro' => $outsourcing->biro->nama_biro] : null,
            ],
            'evaluator' => $evaluator ? [
                'id' => $evaluatorUser->id,
                'name' => $evaluator->name,
                'image' => $evaluator->image,
                'jabatan' => $evaluator instanceof MasterPegawai
                    ? $evaluator->jabatan
                    : ($evaluator->jabatan ? ['nama_jabatan' => $evaluator->jabatan->nama_jabatan] : null),
                'biro' => $evaluator->biro ? ['nama_biro' => $evaluator->biro->nama_biro] : null,
            ] : null,
            'uuidPenugasanPeer' => $penugasan->uuid,
            'tipePenilai' => $penugasan->tipe_penilai,
            'overallNotes' => $penugasan->catatan,
            'developmentArea' => $penugasan->area_pengembangan,
            'observedStrengths' => $penugasan->kekuatan_teramati,
            'evaluationData' => $engine->getEvaluationData($penugasan, $jabatanId),
        ];

        if ($penugasan->status === 'completed') {
            return Inertia::render('evaluator/viewscore', (new EvaluationFormResource($data))->resolve());
        }

        return Inertia::render('evaluator/evaluation-form', (new EvaluationFormResource($data))->resolve());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenilaianRequest $request, Penugasan $penugasan): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($penugasan, $validated): void {
            foreach ($validated['scores'] as $score) {
                $penilaian = Penilaian::firstOrNew([
                    'penugasan_id' => $penugasan->id,
                    'indikator_id' => $score['indicator_id'],
                ]);

                if (! $penilaian->exists) {
                    $penilaian->uuid = (string) Str::uuid();
                }

                $penilaian->nilai = $score['value'];
                $penilaian->save();
            }

            $penugasan->forceFill([
                'catatan' => $validated['notes'] ?? null,
                'area_pengembangan' => $validated['development_area'] ?? null,
                'kekuatan_teramati' => $validated['observed_strengths'] ?? null,
                'status' => 'completed',
            ])->save();
        });

        return to_route('home')->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penilaian $penilaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenilaianRequest $request, Penilaian $penilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penilaian $penilaian)
    {
        //
    }

    public function rekaphasil(
        FilterRekapHasilRequest $request,
        EvaluationEngineService $engine
    ): Response {
        $filters = $request->validated();
        $search = trim($filters['search'] ?? '');
        $kodeBiro = $filters['kode_biro'] ?? null;

        $evaluationResults = Outsourcing::query()
            ->select([
                'id',
                'uuid',
                'name',
                'image',
                'jabatan_id',
                'kode_biro',
                'is_active',
            ])
            ->with([
                'penugasanAktif' => fn($query) => $query
                    ->select(['id','uuid','bobot_skor_id','siklus_id','outsourcing_id','penilai_id','tipe_penilai','status','catatan'])
                    ->with([
                        'bobotSkor:id,bobot',
                        'evaluators' => fn($query) => $query
                            ->select(['id','email','userable_id','userable_type','nip']),
                        'evaluators.userable' => fn(MorphTo $morphTo) => $morphTo->constrain([
                            MasterPegawai::class => fn($query) => $query
                                ->select(['id','uuid','nip','name','image','jabatan']),
                            Outsourcing::class => fn($query) => $query
                                ->select(['id','uuid','nip','name','image']),
                        ]),
                        'penilaian' => fn($query) => $query
                            ->select(['id','uuid','penugasan_id','indikator_id','nilai']),
                        'penilaian.indikator:id,title,pilar_id',
                        'penilaian.indikator.pilar:id,title,bobot_skor_id',
                        'penilaian.indikator.pilar.bobotSkor:id,bobot',
                    ]),
                'biro:kode_biro,nama_biro',
                'jabatan:id,nama_jabatan',
            ])
            ->where('is_active', true)
            ->when($kodeBiro, function (Builder $query, string $kodeBiro): void {
                $query->where('kode_biro', $kodeBiro);
            })
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhereHas('biro', function (Builder $query) use ($search): void {
                            $query->where('nama_biro', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($os) use ($engine) {
                $result = $engine->calculate($os->penugasanAktif);

                return [
                    'id' => $os->id,
                    'name' => $os->name,
                    'uuid' => $os->uuid,
                    'image' => $os->image,

                    'biro' => $os->biro?->nama_biro,
                    'jabatan' => $os->jabatan?->nama_jabatan,

                    'status' => $result['status'] === 'completed'
                        ? 'completed'
                        : 'progress',

                    'finalTotalScore' => $result['finalScore'],
                    'evaluatorScores' => $result['evaluators'],
                ];
            });

        $units = Biro::query()
            ->select('kode_biro', 'nama_biro')
            ->whereIn(
                'kode_biro',
                Outsourcing::query()
                    ->where('is_active', true)
                    ->whereNotNull('kode_biro')
                    ->select('kode_biro')
                    ->distinct()
            )
            ->orderBy('nama_biro', 'asc')
            ->get()
            ->map(fn(Biro $biro): array => [
                'kodeBiro' => $biro->kode_biro,
                'namaBiro' => $biro->nama_biro,
            ])
            ->values();

        return Inertia::render('admin/rekaphasil/page', [
            'evaluationResults' => RekapHasilResource::collection($evaluationResults)->resolve(),
            'filters' => [
                'search' => $search,
                'kodeBiro' => $kodeBiro,
            ],
            'units' => $units,
        ]);
    }

    public function ranking(
        EvaluationEngineService $engine,
        Request $request
    ): Response {
        $jabatanId = $request->input('jabatan_id')
            ?? Jabatan::where('kode_jabatan', 'DESAINER_GRAFIS')->value('id');

        $outsourcings = Outsourcing::query()
            ->select(['id','uuid','name','image','jabatan_id','is_active'])
            ->with([
            'jabatan:id,nama_jabatan',
            'penugasanAktif' => fn($query) => $query
                ->select(['id','uuid','bobot_skor_id','siklus_id','outsourcing_id','penilai_id','tipe_penilai','status','catatan'])
                ->with([
                    'bobotSkor:id,bobot',
                    'evaluators' => fn($query) => $query
                        ->select(['id','email','userable_id','userable_type','nip']),
                    'evaluators.userable',
                    'penilaian' => fn($query) => $query
                        ->select(['id','uuid','penugasan_id','indikator_id','nilai']),
                    'penilaian.indikator:id,title,pilar_id',
                    'penilaian.indikator.pilar:id,title,bobot_skor_id',
                    'penilaian.indikator.pilar.bobotSkor:id,bobot',
                ]),
        ])
            ->where('is_active', true)
            ->where('jabatan_id', $jabatanId)
            ->get();

        $outsourcingData = $engine->calculateRankingByJabatan($outsourcings);

        $allJabatan = Jabatan::select('id', 'nama_jabatan')
            ->whereNot('kode_jabatan', 'UMUM')
            ->orderBy('nama_jabatan', 'asc')
            ->get();

        return Inertia::render('admin/ranking/page', [
            'outsourcingData' => $outsourcingData,
            'allJabatan' => $allJabatan,
            'selectedJabatanId' => (string) $jabatanId,
        ]);
    }
}
