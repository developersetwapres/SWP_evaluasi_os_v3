<?php

namespace Database\Seeders;

use App\Models\Indikator;
use Illuminate\Database\Seeder;

class IndikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Indikator::factory()->count(50)->create();
    }
}
