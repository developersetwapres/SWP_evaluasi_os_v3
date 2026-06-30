<?php

namespace Database\Seeders;

use App\Models\BobotSkor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BobotSkorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            [
                'siklus_id'  => 1,
                'title' => 'Aspek teknis dan kualitas kerja',
                'kode_bobot' => 'aspek-teknis-dan-kualitas-kerja',
                'bobot' => 60.00,
            ],
            [
                'siklus_id'  => 1,
                'title' => 'Aspek Perilaku',
                'kode_bobot' => 'aspek-perilaku',
                'bobot' => 40.00,
            ],
            [
                'siklus_id'  => 1,
                'title' => 'Penilai Atasan',
                'kode_bobot' => 'atasan',
                'bobot' => 50.00,
            ],
            [
                'siklus_id'  => 1,
                'title' => 'Penilai Penerima Layanan 1',
                'kode_bobot' => 'penerima_layanan1',
                'bobot' => 30.00,
            ],
            [
                'siklus_id'  => 1,
                'title' => 'Penilai Penerima Layanan 2',
                'kode_bobot' => 'penerima_layanan2',
                'bobot' => 30.00,
            ],
            [
                'siklus_id'  => 1,
                'title' => 'Penilai Teman',
                'kode_bobot' => 'teman_setingkat',
                'bobot' => 20.00,
            ]
        ];

        foreach ($data as $key => $value) {
            BobotSkor::create($value);
        }
    }
}
