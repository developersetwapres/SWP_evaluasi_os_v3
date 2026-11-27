<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\SiklusFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'tanggal_mulai', 'tanggal_selesai', 'is_active'])]
class Siklus extends Model
{
    /** @use HasFactory<SiklusFactory> */
    use HasFactory;

    use HasUuid;

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];
}
