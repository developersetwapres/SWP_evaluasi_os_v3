<?php

namespace Database\Seeders;

use App\Models\Pilar;
use Illuminate\Database\Seeder;

class PilarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pilar::factory()->count(3)->create();
    }
}
