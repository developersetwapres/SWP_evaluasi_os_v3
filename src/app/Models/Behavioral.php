<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Behavioral extends Model
{
    /** @use HasFactory<\Database\Factories\BehavioralFactory> */
    use HasFactory;
    use HasUuid;
}
