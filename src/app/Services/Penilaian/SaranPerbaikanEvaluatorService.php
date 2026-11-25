<?php

namespace App\Services\Penilaian;

use App\Models\Jabatan;
use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SaranPerbaikanEvaluatorService
{
    public function saran(?int $jabatanId = null): array
    {
        $query = Jabatan::query()
            ->select(['id', 'nama_jabatan'])
            ->with([
                'outsourcings' => function ($query) {
                    $query
                        ->select(['id','uuid','name','image','jabatan_id','is_active'])
                        ->where('is_active', true);
                },
                'outsourcings.penugasan' => fn($query) => $query
                    ->select(['id','uuid','siklus_id','outsourcing_id','penilai_id','tipe_penilai','catatan','area_pengembangan','kekuatan_teramati']),
                'outsourcings.penugasan.evaluators' => fn($query) => $query
                    ->select(['id','name','email','userable_id','userable_type','nip']),
                'outsourcings.penugasan.evaluators.userable' => fn(MorphTo $morphTo) => $morphTo->constrain([
                    MasterPegawai::class => fn($query) => $query
                        ->select(['id','uuid','nip','name','image','jabatan']),
                    Outsourcing::class => fn($query) => $query
                        ->select(['id','uuid','nip','name','image']),
                ]),
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
