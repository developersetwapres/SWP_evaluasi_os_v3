<?php

namespace Database\Factories;

use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jabatan>
 */
class JabatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelompok_jabatan_id' => KelompokJabatan::factory(),
            'nama_jabatan' => fake()->unique()->jobTitle(),
            'kode_jabatan' => fake()->unique()->bothify('JBT###'),
        ];
    }
}
