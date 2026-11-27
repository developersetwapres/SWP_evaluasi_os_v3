<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\IndikatorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['pilar_id', 'kelompok_jabatan_id', 'title', 'defenisi', 'example'])]
class Indikator extends Model
{
    /** @use HasFactory<IndikatorFactory> */
    use HasFactory;

    use HasUuid;

    protected $casts = [
        'example' => 'array',
    ];

    // --------------- BelongsTo-----------------------
    /** @return BelongsTo<Pilar, $this> */
    public function pilar(): BelongsTo
    {
        return $this->belongsTo(Pilar::class, 'pilar_id');
    }

    /** @return BelongsTo<KelompokJabatan, $this> */
    public function kelompokJabatan(): BelongsTo
    {
        return $this->belongsTo(KelompokJabatan::class, 'kelompok_jabatan_id');
    }

    // --------------- HasOne------------------------------
    /** @return HasOne<Penilaian, $this> */
    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class);
    }

    // --------------- HasMany------------------------------
    /** @return HasMany<Behavioral, $this> */
    public function behavioral(): HasMany
    {
        return $this->hasMany(Behavioral::class, 'indikator_id');
    }
}
