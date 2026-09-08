<?php

namespace Database\Seeders;

use App\Models\Siklus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiklusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Semester I tahun 2026',
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-06-30',
                'is_active' => 1,
            ],
            [
                'title' => 'Semester II tahun 2025',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2025-12-31',
                'is_active' => 0,
            ]
        ];

        foreach ($data as $key => $value) {
            Siklus::create($value);
        }
    }
}
