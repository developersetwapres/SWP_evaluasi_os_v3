<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\KelompokJabatanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_kelompok'])]
class KelompokJabatan extends Model
{
    /** @use HasFactory<KelompokJabatanFactory> */
    use HasFactory;
    use HasUuid;

    public function jabatan(): HasMany
    {
        return $this->hasMany(Jabatan::class, 'kelompok_jabatan_id');
    }

    public function indikator(): HasMany
    {
        return $this->hasMany(Indikator::class, 'kelompok_jabatan_id');
    }
}
