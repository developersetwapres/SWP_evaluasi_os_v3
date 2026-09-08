<?php

namespace Database\Factories;

use App\Models\Indikator;
use App\Models\KelompokJabatan;
use App\Models\Pilar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Indikator>
 */
class IndikatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pilar_id' => Pilar::factory(),
            'kelompok_jabatan_id' => KelompokJabatan::factory(),
            'title' => fake()->sentence(6),
            'defenisi' => fake()->sentence(12),
            'uuid' => fake()->uuid(),
            'example' => json_encode([
                'contoh' => fake()->sentence(6),
                'nilai' => fake()->randomElement(['Baik', 'Cukup', 'Sangat Baik']),
            ], JSON_THROW_ON_ERROR),
        ];
    }
}
