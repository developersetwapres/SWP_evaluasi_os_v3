<?php

namespace Database\Factories;

use App\Models\KelompokJabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KelompokJabatan>
 */
class KelompokJabatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'nama_kelompok' => ucwords(fake()->unique()->words(2, true)),
        ];
    }
}
