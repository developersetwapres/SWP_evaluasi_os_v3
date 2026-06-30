<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Fillable([
    'uuid',
    'bobot_skor_id',
    'siklus_id',
    'outsourcing_id',
    'penilai_id',
    'tipe_penilai',
    'status',
    'catatan',
])]

class Penugasan extends Model
{
    /** @use HasFactory<\Database\Factories\PenugasanFactory> */
    use HasFactory;
    use HasUuid;

    protected $with = [
        'bobotSkor',
        'siklus',
        'outsourcings',
        'evaluators',

        'penilaian',
    ];


    //------------------------ BelongsTo------------------------------
    public function bobotSkor(): BelongsTo
    {
        return $this->belongsTo(BobotSkor::class, 'bobot_skor_id');
    }

    public function siklus(): BelongsTo
    {
        return $this->belongsTo(Siklus::class, 'siklus_id');
    }

    public function outsourcings(): BelongsTo
    {
        return $this->belongsTo(Outsourcing::class, 'outsourcing_id');
    }

    public function evaluators(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }




    //------------------------ HasMany------------------------------
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'penugasan_id');
    }


    public static function byEvaluators()
    {
        return self::select(['siklus_id', 'status', 'outsourcing_id', 'penilai_id', 'tipe_penilai'])
            ->whereHas('siklus', fn($q) => $q->where('is_active', 1))
            ->withOnly([
                'outsourcings:id,name,uuid,image,jabatan_id,nip',
                'evaluators.userable',
            ])
            ->get()
            ->map(function ($item) {
                $userable = $item->evaluators?->userable;

                return [
                    'outsourcing_name' => $item->outsourcings->name,
                    'outsourcing_image' => $item->outsourcings->image,
                    'outsourcing_jabatan' => optional($item->outsourcings->jabatan)->nama_jabatan,
                    'tipe_penilai' => $item->tipe_penilai,
                    'status' => $item->status,
                    'evaluator_name' => $userable?->name,
                    'evaluator_image' => $userable?->image,
                    'evaluator_uuid' => $userable?->uuid,
                ];
            });
    }
}
