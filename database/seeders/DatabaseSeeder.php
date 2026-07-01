<?php

namespace Database\Seeders;

use App\Models\MasterPegawai;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiklusSeeder::class,
            BehavioralSeeder::class,
            JabatanSeeder::class,
            OutsourcingSeeder::class,
            MasterPegawaiSeeder::class,
            UserSeeder::class,
            BiroSeeder::class,
            BobotSkorSeeder::class,
        ]);
    }
}
