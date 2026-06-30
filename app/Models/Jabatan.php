<?php

namespace App\Models;

use Database\Factories\JabatanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    /** @use HasFactory<JabatanFactory> */
    use HasFactory;

    public function kelompokJabatan(): BelongsTo
    {
        return $this->belongsTo(KelompokJabatan::class, 'kelompok_jabatan_id');
    }

    public function outsourcings(): HasMany
    {
        return $this->hasMany(Outsourcing::class, 'jabatan_id');
    }
}
