<?php

namespace App\Services\Penilaian;

use App\Models\Pilar;
use Illuminate\Support\Collection;

class EvaluationEngineService
{
    public function calculate(Collection $penugasanCollection): array
    {
        $evaluators = [];
        $globalPilar = [];
        $finalScore = 0;

        foreach ($penugasanCollection as $penugasan) {

            $evaluatorResult = $this->calculateEvaluator($penugasan);

            $evaluators[] = $evaluatorResult;

            $finalScore += $evaluatorResult['weightedScore'];

            foreach ($evaluatorResult['aspects'] as $aspek) {

                $globalPilar[$aspek['id']]['title'] = $aspek['title'];
                $globalPilar[$aspek['id']]['bobot'] = $aspek['bobot'];

                // gunakan weightedScore PER evaluator (sudah termasuk bobot aspek)
                $globalPilar[$aspek['id']]['nilai'][] =
                    $aspek['weightedScore'] * $evaluatorResult['bobot'];
            }
        }

        return [
            'status' => $this->resolveStatus($evaluators),
            'finalScore' => round($finalScore, 2),
            'evaluators' => $evaluators,
            'aspectsGlobal' => $this->calculateGlobalPilar($globalPilar),
        ];
    }

    protected function calculateEvaluator($penugasan): array
    {
        $aspekData = [];
        $totalEvaluatorScore = 0;

        foreach ($penugasan->penilaian as $penilaian) {

            $aspek = $penilaian->indikator?->aspek;

            if (! $aspek) {
                continue;
            }

            $aspekData[$aspek->id]['title'] = $aspek->title;
            $aspekData[$aspek->id]['bobot'] = $aspek->bobotSkor->bobot / 100;
            $aspekData[$aspek->id]['nilai'][] = $penilaian->nilai;
        }

        $aspekResults = [];

        foreach ($aspekData as $id => $data) {

            $countNilai = count($data['nilai']);

            if ($countNilai === 0) {
                continue;
            }

            $avg = array_sum($data['nilai']) / $countNilai;
            $weightedPilar = $avg * $data['bobot'];

            $totalEvaluatorScore += $weightedPilar;

            $aspekResults[] = [
                'id' => $id,
                'title' => $data['title'],
                'averageScore' => round($avg, 2),
                'weightedScore' => round($weightedPilar, 2),
                'bobot' => $data['bobot'],
            ];
        }

        $bobotEvaluator = $penugasan->bobotSkor->bobot / 100;

        // konsisten: sum(avg × bobot_aspek) × bobot_evaluator
        $weightedFinal = $totalEvaluatorScore * $bobotEvaluator;

        return [
            'type' => $penugasan->tipe_penilai,
            'uuidPenugasan' => $penugasan->uuid,
            'evaluatorName' => $penugasan->evaluators?->userable?->name,
            'averageScore' => round($totalEvaluatorScore, 2),
            'bobot' => $bobotEvaluator,
            'weightedScore' => round($weightedFinal, 2),
            'status' => $penugasan->status,
            'notes' => $penugasan->catatan,
            'aspects' => $aspekResults,
        ];
    }

    protected function calculateGlobalPilar(array $globalPilar): array
    {
        $result = [];

        foreach ($globalPilar as $id => $data) {

            $countNilai = count($data['nilai'] ?? []);

            if ($countNilai === 0) {
                continue;
            }

            $total = array_sum($data['nilai']);

            $result[] = [
                'id' => $id,
                'title' => $data['title'],
                'averageScore' => round($total / $countNilai, 2),
                'weightedScore' => round($total, 2),
                'bobot' => $data['bobot'],
            ];
        }

        return $result;
    }

    private function resolveStatus(array $evaluators): string
    {
        $completed = collect($evaluators)
            ->every(fn ($e) => $e['status'] === 'completed');

        return $completed ? 'completed' : 'draft';
    }

    public function calculateDetailPerPilar(
        Collection $penugasanCollection,
        string $outsourcingUuid
    ): array {

        $result = $this->calculate($penugasanCollection);

        $aspects = [];

        foreach ($result['aspectsGlobal'] as $globalPilar) {

            $evaluators = [];

            foreach ($result['evaluators'] as $evaluator) {

                $found = collect($evaluator['aspects'])
                    ->firstWhere('id', $globalPilar['id']);

                if ($found) {

                    $weighted =
                        $found['weightedScore'] * $evaluator['bobot'];

                    $evaluators[] = [
                        'evaluatorName' => $evaluator['evaluatorName'],
                        'evaluatorType' => $evaluator['type'],
                        'averageScore' => $found['averageScore'],
                        'weightedScore' => round($weighted, 2),
                        'bobot' => $evaluator['bobot'],
                    ];
                } else {

                    $evaluators[] = [
                        'evaluatorName' => $evaluator['evaluatorName'],
                        'evaluatorType' => $evaluator['type'],
                        'averageScore' => 0,
                        'weightedScore' => 0,
                        'bobot' => $evaluator['bobot'],
                    ];
                }
            }

            $total = collect($evaluators)->sum('weightedScore');

            $aspects[] = [
                'id' => $globalPilar['id'],
                'aspectTitle' => $globalPilar['title'],
                'evaluators' => $evaluators,
                'total' => round($total, 2),
            ];
        }

        return [
            'uuid' => $outsourcingUuid,
            'aspects' => $aspects,
        ];
    }

    public function calculateRawScore($penugasan): float
    {
        $aspekData = [];

        foreach ($penugasan->penilaian as $penilaian) {

            $aspek = $penilaian->indikator?->aspek;

            if (! $aspek) {
                continue;
            }

            $aspekData[$aspek->id]['bobot'] =
                $aspek->bobotSkor->bobot / 100;

            $aspekData[$aspek->id]['nilai'][] =
                $penilaian->nilai;
        }

        $total = 0;

        foreach ($aspekData as $data) {

            $count = count($data['nilai']);

            if ($count === 0) {
                continue;
            }

            $avg = array_sum($data['nilai']) / $count;

            $total += $avg * $data['bobot'];
        }

        return round($total, 2);
    }

    public function calculateSingleSummary($penilaian): array
    {
        $aspects = Pilar::with(['indikator', 'bobotSkor'])->get();

        $finalTotalScore = 0;
        $aspectResults = [];

        foreach ($aspects as $aspect) {

            $indikatorIds = $aspect->indikator->pluck('id');

            $nilai = $penilaian
                ->whereIn('indikator_id', $indikatorIds)
                ->pluck('nilai')
                ->filter(fn ($n) => $n !== null)
                ->map(fn ($n) => (float) $n);

            if ($nilai->isEmpty() || ! $aspect->bobotSkor) {
                continue;
            }

            $average = round($nilai->avg(), 2);
            $bobot = $aspect->bobotSkor->bobot / 100;

            $weighted = round($average * $bobot, 2);

            $finalTotalScore += $weighted;

            $aspectResults[] = [
                'title' => $aspect->title,
                'nilai' => $average,
                'bobot' => $bobot,
            ];
        }

        return [
            'finalTotalScore' => round($finalTotalScore, 2),
            'aspects' => $aspectResults,
        ];
    }

    public function getEvaluationData($penugasan, int $jabatanId)
    {
        return Pilar::select(['id', 'title', 'bobot_skor_id'])
            ->with([
                'bobotSkor:id,bobot',
                'indikator' => fn ($query) => $query
                    ->where('jabatan_id', $jabatanId)
                    ->orderBy('id')
                    ->with([
                        'behavioral' => fn ($behavioralQuery) => $behavioralQuery->orderByDesc('skor'),
                        'penilaian' => fn ($penilaianQuery) => $penilaianQuery->where('penugasan_id', $penugasan->id),
                    ]),
            ])
            ->orderBy('id')
            ->get();
    }

    public function getDetailByPilar($penilaian): array
    {
        $aspects = Pilar::with(['indikator', 'bobotSkor'])->get();

        $finalTotalScore = 0;
        $aspectResults = [];

        foreach ($aspects as $aspect) {
            $indikatorIds = $aspect->indikator->pluck('id');

            // kumpulkan semua nilai indikator dalam 1 aspek
            $nilai = collect();
            $bobot = optional($aspect->bobotSkor)->bobot;

            $penilaian
                ->whereIn('indikator_id', $indikatorIds)
                ->each(
                    fn ($p) => $p->nilai !== null
                        ? $nilai->push((float) $p->nilai)
                        : null
                );

            if ($nilai->isEmpty() || $bobot === null) {
                continue;
            }

            $average = round($nilai->avg(), 2);
            $weighted = round($average * ($bobot / 100), 2);

            $finalTotalScore += $weighted;

            $aspectResults[] = [
                'title' => $aspect->title,
                'nilai' => $average,
                'avg' => round($nilai->avg(), 2),
                'bobot' => $bobot / 100,
            ];
        }

        return [
            'finalTotalScore' => round($finalTotalScore, 2),
            'aspects' => $aspectResults,
        ];
    }

    public function calculateRankingByJabatan(Collection $outsourcings): array
    {
        $result = [];

        foreach ($outsourcings as $outsourcing) {

            if ($outsourcing->penugasan->isEmpty()) {
                continue;
            }

            $calc = $this->calculate($outsourcing->penugasan);

            $evaluators = collect($calc['evaluators']);

            $atasan = $evaluators->firstWhere('type', 'atasan')['weightedScore'] ?? 0;
            $penerima = $evaluators->firstWhere('type', 'penerima_layanan')['weightedScore'] ?? 0;
            $teman = $evaluators->firstWhere('type', 'teman_setingkat')['weightedScore'] ?? 0;

            $jabatan = $outsourcing->jabatan?->nama_jabatan ?? 'Tanpa Jabatan';

            $result[$jabatan][] = [
                'nama' => $outsourcing->name,
                'atasan' => round($atasan, 2),
                'penerima_layanan' => round($penerima, 2),
                'teman' => round($teman, 2),
                'total' => round($calc['finalScore'], 2),
            ];
        }

        $final = [];

        foreach ($result as $jabatan => $items) {

            $sorted = collect($items)
                ->sortByDesc('total')
                ->values()
                ->map(function ($item, $index) {
                    $item['ranking'] = $index + 1;

                    return $item;
                });

            $final[] = [
                'jabatan' => $jabatan,
                'ranking' => $sorted->values(),
            ];
        }

        return $final;
    }
}
