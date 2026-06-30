<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\KelompokJabatanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_kelompok'])]
class KelompokJabatan extends Model
{
    /** @use HasFactory<KelompokJabatanFactory> */
    use HasFactory;

    use HasUuid;
}
