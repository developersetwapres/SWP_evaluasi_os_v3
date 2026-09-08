<?php

namespace Database\Seeders;

use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\Pilar;
use App\Models\Siklus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'title' => 'Attitude & Service',
                'slug' => 'attitude-service',
                'bobot' => 20,
            ],
            [
                'title' => 'Work Behavior',
                'slug' => 'work-behavior',
                'bobot' => 40,
            ],
            [
                'title' => 'Task Performance',
                'slug' => 'task-performance',
                'bobot' => 40,
            ],
        ];

        $siklusId = Siklus::where('is_active', 1)->first()->id;

        DB::transaction(function () use ($datas, $siklusId) {
            foreach ($datas as $value) {
                $bobot = BobotSkor::create([
                    'siklus_id' => $siklusId,
                    'title' => $value['title'],
                    'kode_bobot' => $value['slug'],
                    'bobot' => $value['bobot'],
                ]);

                $pilar = Pilar::create([
                    'title' => $value['title'],
                    'bobot_skor_id' => $bobot->id,
                ]);

                $indikator = Indikator::create([]);
            }
        });
    }
}
