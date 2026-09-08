<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusPenilaianByOutsourcingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'outsourcing_name' => $this->name,
            'outsourcing_image' => $this->image,
            'outsourcing_jabatan' => $this->jabatan?->nama_jabatan,
            'evaluatorsAtasan' => $this->evaluatorFor('atasan'),
            'evaluatorsTemanSetingkat' => $this->evaluatorFor('penerima_layanan2'),
            'evaluatorsPenerimaLayanan' => $this->evaluatorFor('penerima_layanan1'),
        ];
    }

    /**
     * @return array{name: string|null, image: string|null, uuid: string|null, status: string|null}
     */
    private function evaluatorFor(string $tipePenilai): array
    {
        $penugasan = $this->penugasan->firstWhere('tipe_penilai', $tipePenilai);
        $userable = $penugasan?->evaluators?->userable;

        return [
            'name' => $userable?->name,
            'image' => $userable?->image,
            'uuid' => $userable?->uuid,
            'status' => $penugasan?->status,
        ];
    }
}
