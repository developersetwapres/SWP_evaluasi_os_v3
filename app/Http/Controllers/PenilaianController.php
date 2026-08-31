<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Jabatan;
use App\Models\Outsourcing;
use App\Models\Penilaian;
use App\Models\Penugasan;
use App\Services\Penilaian\EvaluationEngineService;
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
        abort_if(! $penugasan->outsourcings, 404);

        if ($penugasan->evaluators->id !== Auth::id()) {
            return to_route('home');
        }

        $jabatanId = $penugasan->outsourcings->jabatan_id;

        $evaluator = $penugasan->evaluators?->userable;

        if ($penugasan->evaluators?->userable instanceof Outsourcing) {
            $evaluator->load(['jabatan', 'biro']);
        } else {
            $evaluator?->load('biro');
        }

        $data = [
            'outsourcing' => $penugasan->outsourcings->load(['jabatan', 'biro']),
            'evaluator' => $evaluator,
            'uuidPenugasanPeer' => $penugasan->uuid,
            'tipePenilai' => $penugasan->tipe_penilai,
            'overallNotes' => $penugasan->catatan,
            'developmentArea' => $penugasan->area_pengembangan,
            'observedStrengths' => $penugasan->kekuatan_teramati,
            'evaluationData' => $engine->getEvaluationData($penugasan, $jabatanId),
        ];

        if ($penugasan->status === 'completed') {
            return Inertia::render('evaluator/viewscore', $data);
        }

        return Inertia::render('evaluator/evaluation-form', $data);
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

    public function rekaphasil(EvaluationEngineService $engine): Response
    {
        $evaluationResults = Outsourcing::with([
            'penugasanAktif.bobotSkor',
            'penugasanAktif.evaluators.userable',
            'penugasanAktif.penilaian.indikator.pilar.bobotSkor',
            'biro',
            'jabatan',
        ])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($os) use ($engine) {
                $result = $engine->calculate($os->penugasanAktif);

                return [
                    'id' => $os->id,
                    'nip' => $os->nip,
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

        return Inertia::render('admin/rekaphasil/page', [
            'evaluationResults' => $evaluationResults,
        ]);
    }

    public function ranking(
        EvaluationEngineService $engine,
        Request $request
    ): Response {
        $jabatanId = $request->input('jabatan_id')
            ?? Jabatan::where('kode_jabatan', 'DESAINER_GRAFIS')->value('id');

        $outsourcings = Outsourcing::with([
            'jabatan',
            'penugasanAktif.penilaian.indikator.pilar.bobotSkor',
            'penugasanAktif.bobotSkor',
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
