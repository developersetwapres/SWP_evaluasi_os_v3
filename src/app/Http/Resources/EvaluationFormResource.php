<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'outsourcing' => $this['outsourcing'],
            'evaluator' => $this['evaluator'],
            'uuidPenugasanPeer' => $this['uuidPenugasanPeer'],
            'tipePenilai' => $this['tipePenilai'],
            'overallNotes' => $this['overallNotes'],
            'developmentArea' => $this['developmentArea'],
            'observedStrengths' => $this['observedStrengths'],
            'evaluationData' => $this['evaluationData'],
        ];
    }
}
