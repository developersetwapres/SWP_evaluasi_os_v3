<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluatorHomeAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'status' => $this->status,
            'tipe_penilai' => $this->tipe_penilai,
            'siklus' => [
                'id' => $this->siklus?->id,
                'title' => $this->siklus?->title,
            ],
            'outsourcings' => [
                'uuid' => $this->outsourcings?->uuid,
                'image' => $this->outsourcings?->image,
                'name' => $this->outsourcings?->name,
                'jabatan' => $this->outsourcings?->jabatan?->nama_jabatan,
            ],
        ];
    }
}
