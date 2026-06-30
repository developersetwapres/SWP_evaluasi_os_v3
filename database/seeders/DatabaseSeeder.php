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
        // User::factory(10)->create();

        User::create(
            [
                'nip' => '7777777333',
                'nip_sso' => null,
                'userable_id' => '7777777333',
                'userable_type' => MasterPegawai::class,
                'role' => ['administrator', 'operator'],
                'is_ldap' => false,
                'email' => 'it@set.wapresri.go.id',
                'password' => Hash::make('7777777333'),
            ]
        );

        $this->call([
            MasterPegawaiSeeder::class,
            UserSeeder::class,
            KelompokJabatanSeeder::class,
            JabatanSeeder::class,
            OutsourcingSeeder::class,
            PilarSeeder::class,
            IndikatorSeeder::class,
            BehavioralSeeder::class,
        ]);
    }
}
