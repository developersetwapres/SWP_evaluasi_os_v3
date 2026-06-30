<?php

namespace Database\Seeders;

use App\Models\KelompokJabatan;
use Illuminate\Database\Seeder;

class KelompokJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = [
            ["nama_kelompok" => "BARS_Transportation Support"],
            ["nama_kelompok" => "BARS_Hospitality & Service Support"],
            ["nama_kelompok" => "BARS_Administrative Support"],
            ["nama_kelompok" => "BARS_Environment Support"],
            ["nama_kelompok" => "BARS_Technical Support"],
            ["nama_kelompok" => "BARS_Media & Communication Support"],
            ["nama_kelompok" => "BARS_Supervisory Support"],
        ];

        foreach ($files as $file) {
            KelompokJabatan::create([
                'nama_kelompok' => $file['nama_kelompok'],
            ]);
        }
    }
}
