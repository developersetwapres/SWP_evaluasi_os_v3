<?php

namespace App\Services\Penugasan;

use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use App\Models\Penugasan;
use App\Models\Siklus;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

class PenugasanDashboardService
{
    public function activeSiklus(): ?Siklus
    {
        return Siklus::select(['id', 'title'])
            ->where('is_active', true)
            ->first();
    }

    public function assignmentOutsourcings(Siklus $siklus): Collection
    {
        return Outsourcing::query()
            ->select(['id', 'uuid', 'image', 'name', 'jabatan_id', 'kode_biro'])
            ->where('is_active', true)
            ->with([
                'jabatan:id,nama_jabatan',
                'biro:kode_biro,nama_biro',
                'penugasan' => fn ($query) => $query
                    ->select(['id', 'uuid', 'siklus_id', 'outsourcing_id', 'penilai_id', 'tipe_penilai', 'status'])
                    ->where('siklus_id', $siklus->id)
                    ->with([
                        'evaluators' => fn ($evaluatorQuery) => $evaluatorQuery
                            ->select(['id', 'userable_id', 'userable_type', 'nip']),
                        'evaluators.userable' => fn (MorphTo $morphTo) => $morphTo->morphWith([
                            Outsourcing::class => ['jabatan:id,nama_jabatan'],
                            MasterPegawai::class => [],
                        ]),
                    ]),
            ])
            ->orderBy('name')
            ->get();
    }

    public function evaluatorOptions(): Collection
    {
        return MasterPegawai::query()
            ->select(['id', 'name', 'image', 'jabatan', 'kode_biro', 'kode_unit', 'uuid'])
            ->where('kode_unit', '02')
            ->with('biro:kode_biro,nama_biro')
            ->orderBy('name')
            ->get();
    }

    public function evaluatorHomeAssignments(User $user): Collection
    {
        return $user->penugasan()
            ->select(['id', 'outsourcing_id', 'siklus_id', 'status', 'uuid', 'tipe_penilai'])
            ->whereHas('siklus', fn ($query) => $query->where('is_active', true))
            ->with([
                'siklus:id,title',
                'outsourcings:id,uuid,image,name,jabatan_id',
                'outsourcings.jabatan:id,nama_jabatan',
            ])
            ->orderBy('status')
            ->get();
    }

    public function statusByOutsourcings(): Collection
    {
        return Outsourcing::query()
            ->select(['id', 'name', 'image', 'jabatan_id', 'is_active'])
            ->where('is_active', true)
            ->with([
                'jabatan:id,nama_jabatan',
                'penugasan' => fn ($query) => $query
                    ->select(['id', 'siklus_id', 'outsourcing_id', 'penilai_id', 'tipe_penilai', 'status'])
                    ->with([
                        'evaluators' => fn ($evaluatorQuery) => $evaluatorQuery
                            ->select(['id', 'userable_id', 'userable_type', 'nip']),
                        'evaluators.userable',
                    ]),
            ])
            ->orderBy('name')
            ->get();
    }

    public function statusByEvaluators(): Collection
    {
        return Penugasan::query()
            ->select(['id', 'siklus_id', 'status', 'outsourcing_id', 'penilai_id', 'tipe_penilai'])
            ->whereHas('siklus', fn ($query) => $query->where('is_active', true))
            ->with([
                'outsourcings:id,name,uuid,image,jabatan_id,nip',
                'outsourcings.jabatan:id,nama_jabatan',
                'evaluators' => fn ($query) => $query->select(['id', 'userable_id', 'userable_type', 'nip']),
                'evaluators.userable',
            ])
            ->get();
    }
}
