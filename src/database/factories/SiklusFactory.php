<?php

namespace Database\Factories;

use App\Models\Siklus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siklus>
 */
class SiklusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->word(),
            'uuid' => fake()->uuid(),
            'tanggal_mulai' => fake()->dateTime(),
            'tanggal_selesai' => fake()->dateTime(),
            'is_active' => false,
        ];
    }
}
