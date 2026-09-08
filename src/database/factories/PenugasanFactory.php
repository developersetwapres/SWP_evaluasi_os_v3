<?php

namespace Database\Factories;

use App\Models\BobotSkor;
use App\Models\Outsourcing;
use App\Models\Penugasan;
use App\Models\Siklus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penugasan>
 */
class PenugasanFactory extends Factory
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
            'bobot_skor_id' => BobotSkor::factory(),
            'siklus_id' => Siklus::factory(),
            'outsourcing_id' => fn() => Outsourcing::factory()->create()->id,
            'penilai_id' => User::factory(),
            'tipe_penilai' => fake()->randomElement(['atasan', 'penerima_layanan1', 'penerima_layanan2']),
            'status' => fake()->randomElement(['completed', 'draft', 'incomplete']),
            'catatan' => fake()->sentence(),
        ];
    }
}
