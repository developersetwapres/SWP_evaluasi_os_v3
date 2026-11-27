<?php

namespace App\Models;

use Database\Factories\BobotSkorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['siklus_id', 'title', 'kode_bobot', 'bobot'])]
class BobotSkor extends Model
{
    /** @use HasFactory<BobotSkorFactory> */
    use HasFactory;

    /** @return BelongsTo<Siklus, $this> */
    public function siklus(): BelongsTo
    {
        return $this->belongsTo(Siklus::class, 'siklus_id');
    }
}
