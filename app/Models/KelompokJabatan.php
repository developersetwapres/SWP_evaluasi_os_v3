<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['uuid', 'nama_kelompok'])]
class KelompokJabatan extends Model
{
    /** @use HasFactory<\Database\Factories\KelompokJabatanFactory> */
    use HasFactory;
    use HasUuid;
}
