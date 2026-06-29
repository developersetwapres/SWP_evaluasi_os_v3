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
            $saran = [];

            foreach ($jabatan->outsourcings->where('is_active', 1) as $os) {

                $penugasan = $os->penugasan->map(function ($p) {
                    return [
                        'nama' => $p->evaluators?->userable?->name,
                        'image' => $p->evaluators?->userable?->image,
                        'tipe_penilai' => $p->tipe_penilai,
                        'catatan' => $p->catatan,
                    ];
                });

                $saran[] = [
                    'name' => $os->name,
                    'image' => $os->image,
                    'penugasan' => $penugasan->values(),
                ];
            }

            if (!empty($saran)) {
                $result[] = [
                    'jabatan' => $jabatan->nama_jabatan,
                    'saran' => $saran,
                ];
            }
        }

        return $result;
    }
}
