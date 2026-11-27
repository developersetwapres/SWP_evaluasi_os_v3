<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\BehavioralFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['indikator_id', 'behavioral', 'skor'])]
class Behavioral extends Model
{
    /** @use HasFactory<BehavioralFactory> */
    use HasFactory;

    use HasUuid;

    /** @return BelongsTo<Indikator, $this> */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }
}
