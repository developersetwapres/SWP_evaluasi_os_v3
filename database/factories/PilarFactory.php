<?php

namespace Database\Factories;

use App\Models\BobotSkor;
use App\Models\Pilar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pilar>
 */
class PilarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->unique()->words(2, true)),
            'uuid' => fake()->uuid(),
            'bobot_skor_id' => BobotSkor::factory(),
        ];
    }
}
