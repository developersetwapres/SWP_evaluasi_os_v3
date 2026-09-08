<?php

namespace Database\Factories;

use App\Models\Jabatan;
use App\Models\Outsourcing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Outsourcing>
 */
class OutsourcingFactory extends Factory
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
            'name' => fake()->name(),
            'image' => fake()->imageUrl(),
            'jabatan_id' => Jabatan::factory(),
            'is_active' => true,
            'nip' => fake()->numerify('##.###.###.#'),
            'kode_biro' => fake()->bothify('????-####'),
        ];
    }
}
