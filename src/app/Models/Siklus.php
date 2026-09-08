<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siklus extends Model
{
    /** @use HasFactory<\Database\Factories\SiklusFactory> */
    use HasFactory;
      use HasUuid;

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
