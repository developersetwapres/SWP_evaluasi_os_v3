<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;


#[Fillable([
    'uuid',
    'nip',
    'name',
    'email',
    'image',
    'jabatan_id',
    'kode_biro',
    'is_active',
])]

class Outsourcing extends Model
{
    /** @use HasFactory<\Database\Factories\OutsourcingFactory> */
    use HasFactory;
    use HasUuid;

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function biro(): BelongsTo
    {
        return $this->belongsTo(Biro::class, 'kode_biro', 'kode_biro');
    }



    public function penugasan(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'outsourcing_id')
            ->whereHas('siklus', function ($q) {
                $q->where('is_active', 1);
            });
    }



    public function user(): MorphOne
    {
        return $this->morphOne(
            User::class,
            'userable',
            'userable_type',
            'userable_id',
            'nip'
        );
    }

    public function penugasanAktif(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'outsourcing_id')
            ->whereHas('siklus', function ($q) {
                $q->where('is_active', 1);
            });
    }


    public function displayJabatan(): ?string
    {
        return $this->jabatan?->nama_jabatan;
    }

    public function byOutsourcings()
    {
        return $this->with([
            'penugasan.evaluators.userable',
        ])
            ->orderBy('name', 'asc')
            ->where('is_active', 1)
            ->get()
            ->map(function ($os) {

                return [
                    'outsourcing_name' => $os->name,
                    'outsourcing_image' => $os->image,
                    'outsourcing_jabatan' => optional($os->jabatan)->nama_jabatan,
                    'evaluatorsAtasan' => [
                        'name' => $os->penugasan->firstWhere('tipe_penilai', 'atasan')?->evaluators?->userable?->name,
                        'image' => $os->penugasan->firstWhere('tipe_penilai', 'atasan')?->evaluators?->userable?->image,
                        'uuid' => $os->penugasan->firstWhere('tipe_penilai', 'atasan')?->evaluators?->userable?->uuid,
                        'status' => $os->penugasan->firstWhere('tipe_penilai', 'atasan')?->status,
                    ],
                    'evaluatorsTemanSetingkat' => [
                        'name' => $os->penugasan->firstWhere('tipe_penilai', 'teman_setingkat')?->evaluators?->userable?->name,
                        'image' => $os->penugasan->firstWhere('tipe_penilai', 'teman_setingkat')?->evaluators?->userable?->image,
                        'uuid' => $os->penugasan->firstWhere('tipe_penilai', 'teman_setingkat')?->evaluators?->userable?->uuid,
                        'status' => $os->penugasan->firstWhere('tipe_penilai', 'teman_setingkat')?->status,
                    ],
                    'evaluatorsPenerimaLayanan' => [
                        'name' => $os->penugasan->firstWhere('tipe_penilai', 'penerima_layanan')?->evaluators?->userable?->name,
                        'image' => $os->penugasan->firstWhere('tipe_penilai', 'penerima_layanan')?->evaluators?->userable?->image,
                        'uuid' => $os->penugasan->firstWhere('tipe_penilai', 'penerima_layanan')?->evaluators?->userable?->uuid,
                        'status' => $os->penugasan->firstWhere('tipe_penilai', 'penerima_layanan')?->status,
                    ],
                ];
            });
    }
}
