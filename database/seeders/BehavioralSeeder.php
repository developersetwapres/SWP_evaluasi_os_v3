<?php

namespace Database\Seeders;

use App\Models\Behavioral;
use Illuminate\Database\Seeder;

class BehavioralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Behavioral::factory()->count(100)->create();
    }
}
