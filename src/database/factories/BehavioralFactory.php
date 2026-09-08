<?php

namespace Database\Factories;

use App\Models\Behavioral;
use App\Models\Indikator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Behavioral>
 */
class BehavioralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'indikator_id' => Indikator::factory(),
            'behavioral' => fake()->randomElement([
                'Menyampaikan informasi dengan jelas dan tepat waktu',
                'Bekerja sama dengan rekan tim secara proaktif',
                'Menunjukkan tanggung jawab atas hasil kerja',
                'Mengambil inisiatif dalam pemecahan masalah',
            ]),
            'uuid' => fake()->uuid(),
            'skor' => fake()->numberBetween(1, 5),
        ];
    }
}
