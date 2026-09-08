<?php

namespace App\Services\Penilaian;

use App\Models\Jabatan;

class SaranPerbaikanEvaluatorService
{
    public function saran(?int $jabatanId = null): array
    {
        $query = Jabatan::query()
            ->with([
                'outsourcings' => function ($query) {
                    $query->where('is_active', true);
                },
                'outsourcings.penugasan.evaluators.userable',
            ]);

        if ($jabatanId !== null) {
            $query->whereKey($jabatanId);
        }

        $jabatans = $query->get();

        $result = [];

        foreach ($jabatans as $jabatan) {
            $evaluators = [];

            foreach ($jabatan->outsourcings as $os) {
                $penugasan = $os->penugasan->map(function ($p) {
                    return [
                        'nama' => $p->evaluators?->userable?->name,
                        'image' => $p->evaluators?->userable?->image,
                        'tipe_penilai' => $p->tipe_penilai,
                        'catatan' => $p->catatan,
                        'area_pengembangan' => $p->area_pengembangan,
                        'kekuatan_teramati' => $p->kekuatan_teramati,
                    ];
                });

                $evaluators[] = [
                    'name' => $os->name,
                    'image' => $os->image,
                    'penugasan' => $penugasan->values(),
                ];
            }

            if (! empty($evaluators)) {
                $result[] = [
                    'jabatan' => $jabatan->nama_jabatan,
                    'jabatan_id' => $jabatan->id,
                    'evaluators' => $evaluators,
                ];
            }
        }

        return $result;
    }
}
