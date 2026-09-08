<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\IndikatorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Indikator extends Model
{
    /** @use HasFactory<IndikatorFactory> */
    use HasFactory;

    use HasUuid;

    protected $casts = [
        'example' => 'array',
    ];

    // --------------- BelongsTo-----------------------
    public function pilar(): BelongsTo
    {
        return $this->belongsTo(Pilar::class, 'pilar_id');
    }

    public function kelompokJabatan(): BelongsTo
    {
        return $this->belongsTo(KelompokJabatan::class, 'kelompok_jabatan_id');
    }

    // --------------- HasOne------------------------------
    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class);
    }

    // --------------- HasMany------------------------------
    public function behavioral(): HasMany
    {
        return $this->hasMany(Behavioral::class, 'indikator_id');
    }
}
