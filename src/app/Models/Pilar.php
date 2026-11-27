<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\PilarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'bobot_skor_id'])]
class Pilar extends Model
{
    /** @use HasFactory<PilarFactory> */
    use HasFactory;

    use HasUuid;

    // --------------- BelongsTo-----------------------
    /** @return BelongsTo<BobotSkor, $this> */
    public function bobotSkor(): BelongsTo
    {
        return $this->belongsTo(BobotSkor::class, 'bobot_skor_id');
    }

    // --------------- HasMany-----------------------
    /** @return HasMany<Indikator, $this> */
    public function indikator(): HasMany
    {
        return $this->hasMany(Indikator::class, 'pilar_id');
    }
}
