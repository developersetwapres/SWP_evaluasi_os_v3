<?php

namespace App\Services\Penilaian;

use App\Models\Jabatan;

class SaranPerbaikanEvaluatorService
{
    public function saran()
    {
        $result = [];

        $jabatans = Jabatan::with([
            'outsourcings.penugasan.evaluators.userable',
        ])->get();

        foreach ($jabatans as $jabatan) {
            $evaluators = [];

            foreach ($jabatan->outsourcings->where('is_active', 1) as $os) {

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

            if (!empty($evaluators)) {
                $result[] = [
                    'jabatan' => $jabatan->nama_jabatan,
                    'evaluators' => $evaluators,
                ];
            }
        }

        return $result;
    }
}
