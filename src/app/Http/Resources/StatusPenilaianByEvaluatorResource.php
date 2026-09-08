<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusPenilaianByEvaluatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userable = $this->evaluators?->userable;

        return [
            'outsourcing_name' => $this->outsourcings?->name,
            'outsourcing_image' => $this->outsourcings?->image,
            'outsourcing_jabatan' => $this->outsourcings?->jabatan?->nama_jabatan,
            'tipe_penilai' => $this->tipe_penilai,
            'status' => $this->status,
            'evaluator_name' => $userable?->name,
            'evaluator_image' => $userable?->image,
            'evaluator_uuid' => $userable?->uuid,
        ];
    }
}
