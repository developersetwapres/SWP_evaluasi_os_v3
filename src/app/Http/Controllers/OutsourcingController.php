<?php

namespace App\Http\Controllers;

use App\Models\Outsourcing;
use App\Http\Requests\StoreOutsourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateOutsourcingRequest;
use App\Http\Resources\Api\IntegrationPengemudiResource;
use App\Models\Jabatan;
use App\Models\Pilar;
use App\Models\MasterPegawai;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Services\Penilaian\EvaluationEngineService;
use App\Services\Uploadfile\FotoUserService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OutsourcingController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreOutsourcingRequest $request)
    {
        DB::transaction(function () use ($request) {
            $moveImageFromTemp = app(FotoUserService::class)->moveImageFromTemp(...);
            $finalImagePath = $moveImageFromTemp($request->image, 'os');

            Outsourcing::create([
                'name' => $request->name,
                'jabatan_id' => $request->jabatan,
                'kode_biro' => $request->unit_kerja,
                'is_active' => $request->status,
                'nip' => $request->nip,
                'image' => $finalImagePath ?? 'foto_default.png',
            ]);

            $emailPrefix = Str::of($request->name)
                ->lower()
                ->replaceMatches('/\s+/', '.')
                ->toString();

            $email = "{$emailPrefix}@set.wapresri.go.id";

            while (User::where('email', $email)->exists()) {
                $suffix = Str::lower(Str::random(3));

                $email = "{$emailPrefix}.{$suffix}@set.wapresri.go.id";
            }

            User::create([
                'userable_id' => $request->nip,
                'userable_type' => Outsourcing::class,
                'nip' => $request->nip,
                'is_ldap' => false,
                'email' => $email,
                'role' => ['evaluator'],
                'password' => Hash::make($request->nip),
            ]);
        });

        return redirect()->back()->with('success', 'Data Outsourcing berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outsourcing $outsourcing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outsourcing $outsourcing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutsourcingRequest $request, Outsourcing $outsourcing, FotoUserService $service)
    {
        DB::transaction(function () use ($request, $outsourcing, $service) {
            $finalImagePath = $service->moveImageFromTemp($request->image, 'os');

            $outsourcing->update([
                'name' => $request->name,
                'jabatan_id' => $request->jabatan,
                'kode_biro' => $request->unit_kerja,
                'is_active' => $request->status,
            ]);

            if ($finalImagePath) {
                $outsourcing->update(['image' => $finalImagePath]);
            }

            $userData = [
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $outsourcing->user->update($userData);
        });

        return redirect()->back()->with('success', 'Data Outsourcing berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outsourcing $outsourcing)
    {
        //
    }

    public function nilaiAkhir(
        Outsourcing $outsourcing,
        EvaluationEngineService $engine
    ): Response {

        $outsourcing->load([
            'penugasan' => fn($query) => $query
                ->select(['id','uuid','bobot_skor_id','siklus_id','outsourcing_id','penilai_id','tipe_penilai','status','catatan','area_pengembangan','kekuatan_teramati'])
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
            'jabatan:id,nama_jabatan',
            'biro:kode_biro,nama_biro',
        ]);

        $result = $engine->calculate($outsourcing->penugasan);

        $data = [
            'name' => $outsourcing->name,
            'uuid' => $outsourcing->uuid,
            'image' => $outsourcing->image,
            'jabatan' => $outsourcing->jabatan?->nama_jabatan,
            'unit_kerja' => $outsourcing->biro?->nama_biro,

            'status' => $result['status'],
            'finalTotalScore' => $result['finalScore'],
            'pilarScores' => $result['aspectsGlobal'],
            'evaluatorScores' => $result['evaluators'],
        ];

        return Inertia::render('admin/detail/nilai-akhir', $data);
    }

    public function rekapNilai(
        Outsourcing $outsourcing,
        EvaluationEngineService $engine
    ): Response {

        $outsourcing->load([
            'penugasan' => fn($query) => $query
                ->select(['id','uuid','bobot_skor_id','siklus_id','outsourcing_id','penilai_id','tipe_penilai','status','catatan','area_pengembangan','kekuatan_teramati'])
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
        ]);

        $data = [
            'peraspek' => $engine->calculateDetailPerPilar(
                $outsourcing->penugasan,
                $outsourcing->uuid
            )
        ];

        return Inertia::render('admin/detail/rekap-nilai', $data);
    }

    public function catatanEvaluator(Outsourcing $outsourcing): Response
    {

        $data = [
            'penugasans' => $outsourcing->penugasan()
                ->select(['id','uuid','siklus_id','outsourcing_id','penilai_id','tipe_penilai','catatan','area_pengembangan','kekuatan_teramati'])
                ->with([
                    'evaluators' => fn($query) => $query
                        ->select(['id','email','userable_id','userable_type','nip']),
                    'evaluators.userable' => fn(MorphTo $morphTo) => $morphTo->constrain([
                        MasterPegawai::class => fn($query) => $query
                            ->select(['id','uuid','nip','name','image','jabatan']),
                        Outsourcing::class => fn($query) => $query
                            ->select(['id','uuid','nip','name','image']),
                    ]),
                ])
                ->get(),
            'uuidOs' => $outsourcing->uuid,
        ];

        return Inertia::render('admin/detail/catatan-evaluator', $data);
    }

    public function nilaiPerkriteria(Outsourcing $outsourcing, EvaluationEngineService $engine, $tipePenilai = 'atasan'): Response
    {
        $outsourcing->load([
            'penugasan' => fn($query) => $query
                ->select(['id','uuid','bobot_skor_id','siklus_id','outsourcing_id','penilai_id','tipe_penilai','status','catatan','area_pengembangan','kekuatan_teramati']),
        ]);
        $penugasan = $outsourcing->penugasan->firstWhere('tipe_penilai', $tipePenilai);

        if (!$penugasan) {
            return Inertia::render('admin/detail/nilai-perkriteria', [
                'rekapPerAspek' => null,
                'evaluationData' => [],
                'uuidOs' => $outsourcing->uuid,
            ]);
        }

        $kelompokJabatanId = Jabatan::find($outsourcing->jabatan_id)?->kelompok_jabatan_id;

        $data = [
            'rekapPerAspek' => $engine->calculateSingleSummary($penugasan->penilaian),

            'evaluationData' => Pilar::select(['id', 'title', 'bobot_skor_id'])
                ->with([
                    'bobotSkor:id,bobot',
                    'indikator' => fn($query) => $query
                        ->select(['id','uuid','title','defenisi','pilar_id','kelompok_jabatan_id'])
                        ->where('kelompok_jabatan_id', $kelompokJabatanId)
                        ->orderBy('id')
                        ->with([
                            'behavioral' => fn($behavioralQuery) => $behavioralQuery
                                ->select(['id','indikator_id','skor','behavioral'])
                                ->orderByDesc('skor'),
                            'penilaian' => fn($penilaianQuery) => $penilaianQuery
                                ->select(['id','uuid','penugasan_id','indikator_id','nilai'])
                                ->where('penugasan_id', $penugasan->id),
                        ]),
                ])
                ->orderBy('id')
                ->get(),

            'uuidOs' => $outsourcing->uuid,
        ];

        return Inertia::render('admin/detail/nilai-perkriteria', $data);
    }

    public function exportNilaiMentahan(EvaluationEngineService $engine): Response
    {
        return Inertia::render('exportexcel/exportToExcelMentahan', [
            'evaluationResults' => $engine->buildExportRows()
        ]);
    }
}
