<?php

namespace Database\Factories;

use App\Models\BobotSkor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BobotSkor>
 */
class BobotSkorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siklus_id' => null,
            'title' => fake()->randomElement([
                'Bobot Kompetensi',
                'Bobot Kinerja',
                'Bobot Disiplin',
            ]),
            'kode_bobot' => fake()->unique()->bothify('BOB###'),
            'bobot' => fake()->randomFloat(2, 10, 30),
        ];
    }
}
