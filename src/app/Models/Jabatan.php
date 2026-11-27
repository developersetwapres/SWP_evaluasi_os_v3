<?php

namespace App\Models;

use Database\Factories\JabatanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kelompok_jabatan_id', 'nama_jabatan', 'kode_jabatan'])]
class Jabatan extends Model
{
    /** @use HasFactory<JabatanFactory> */
    use HasFactory;

    /** @return BelongsTo<KelompokJabatan, $this> */
    public function kelompokJabatan(): BelongsTo
    {
        return $this->belongsTo(KelompokJabatan::class, 'kelompok_jabatan_id');
    }

    /** @return HasMany<Outsourcing, $this> */
    public function outsourcings(): HasMany
    {
        return $this->hasMany(Outsourcing::class, 'jabatan_id');
    }
}
