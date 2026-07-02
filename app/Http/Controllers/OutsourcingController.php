<?php

namespace App\Http\Controllers;

use App\Models\Outsourcing;
use App\Http\Requests\StoreOutsourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateOutsourcingRequest;
use App\Models\Jabatan;
use App\Models\Pilar;
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
            'penugasan.bobotSkor',
            'penugasan.evaluators.userable',
            'penugasan.penilaian.indikator.pilar.bobotSkor',
            'jabatan',
            'biro',
        ]);

        $penugasanCollection = $outsourcing->penugasan;

        $result = $engine->calculate($penugasanCollection);

        return Inertia::render('admin/detail/nilai-akhir', [
            'rekapAspekEvaluator' => [
                'id' => $outsourcing->id,
                'name' => $outsourcing->name,
                'uuid' => $outsourcing->uuid,
                'image' => $outsourcing->image,
                'jabatan' => $outsourcing->jabatan?->nama_jabatan,
                'unit_kerja' => $outsourcing->biro?->nama_biro,

                'status' => $result['status'],
                'finalTotalScore' => $result['finalScore'],
                'aspekScores' => $result['aspectsGlobal'],
                'evaluatorScores' => $result['evaluators'],
            ]
        ]);
    }

    public function rekapNilai(
        Outsourcing $outsourcing,
        EvaluationEngineService $engine
    ): Response {

        $outsourcing->load([
            'penugasan.bobotSkor',
            'penugasan.evaluators.userable',
            'penugasan.penilaian.indikator.pilar.bobotSkor',
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
            'penugasans' => $outsourcing->penugasan->load('evaluators.userable'),
            'uuidOs' => $outsourcing->uuid,
        ];

        return Inertia::render('admin/detail/catatan-evaluator', $data);
    }

    public function nilaiPerkriteria(Outsourcing $outsourcing, EvaluationEngineService $engine, $tipePenilai = 'atasan'): Response
    {

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

            'evaluationData' => Pilar::select(['id', 'title'])
                ->with([
                    'bobotSkor:id,bobot',
                    'indikator' => fn($query) => $query
                        ->where('kelompok_jabatan_id', $kelompokJabatanId)
                        ->orderBy('id')
                        ->with([
                            'behavioral' => fn($behavioralQuery) => $behavioralQuery->orderByDesc('skor'),
                            'penilaian' => fn($penilaianQuery) => $penilaianQuery->where('penugasan_id', $penugasan->id),
                        ]),
                ])
                ->orderBy('id')
                ->get(),

            'uuidOs' => $outsourcing->uuid,
        ];

        return Inertia::render('admin/detail/nilai-perkriteria', $data);
    }
}
