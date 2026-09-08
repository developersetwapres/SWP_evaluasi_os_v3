<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluatorOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image' => $this->image,
            'jabatan' => $this->jabatan,
            'kode_biro' => $this->kode_biro,
            'biro' => [
                'kode_biro' => $this->biro?->kode_biro,
                'nama_biro' => $this->biro?->nama_biro,
            ],
        ];
    }
}
