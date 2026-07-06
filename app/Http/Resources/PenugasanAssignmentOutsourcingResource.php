<?php

namespace App\Http\Resources;

use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenugasanAssignmentOutsourcingResource extends JsonResource
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
            'image' => $this->image,
            'name' => $this->name,
            'jabatan' => $this->jabatan?->nama_jabatan,
            'biro' => $this->biro?->nama_biro,
            'nama_jabatan' => $this->jabatan?->nama_jabatan,
            'evaluators' => $this->evaluators(),
        ];
    }

    /**
     * @return array<string, array{name: string|null, jabatan: string|null, uuid: string|null}>
     */
    private function evaluators(): array
    {
        $evaluators = [
            'atasan' => ['name' => null, 'jabatan' => null, 'uuid' => null],
            'penerima_layanan1' => ['name' => null, 'jabatan' => null, 'uuid' => null],
            'penerima_layanan2' => ['name' => null, 'jabatan' => null, 'uuid' => null],
        ];

        foreach ($this->penugasan as $penugasan) {
            if (! array_key_exists($penugasan->tipe_penilai, $evaluators)) {
                continue;
            }

            if ($evaluators[$penugasan->tipe_penilai]['name'] !== null) {
                continue;
            }

            $userable = $penugasan->evaluators?->userable;

            if (! $userable) {
                continue;
            }

            $evaluators[$penugasan->tipe_penilai] = [
                'name' => $userable->name,
                'uuid' => $userable->uuid,
                'jabatan' => $this->displayJabatan($userable),
            ];
        }

        return $evaluators;
    }

    private function displayJabatan(MasterPegawai|Outsourcing $userable): ?string
    {
        if ($userable instanceof MasterPegawai) {
            return $userable->jabatan;
        }

        return $userable->jabatan?->nama_jabatan;
    }
}
