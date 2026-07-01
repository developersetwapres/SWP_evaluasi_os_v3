<?php

namespace Database\Seeders;

use App\Models\MasterPegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterPegawaiSkwp extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Dr. Drs. Suwardi, M.Si.',
                'email' => 'suwardi@set.wapresri.go.id',
                'nip' => '20241129001',
                'jabatan' => 'Staf Khusus Wakil Presiden',
                'biro' => 'KMX_0201',
            ],
            [
                'nama' => 'Achmad Adhitya, S.T., M.Sc., Ph.D.',
                'email' => 'achmad.adhitya@set.wapresri.go.id',
                'nip' => '20241129002',
                'jabatan' => 'Staf Khusus Wakil Presiden',
                'biro' => 'KMX_0201',
            ],
            [
                'nama' => 'Tina Talisa, S.K.G., M.I.Kom.',
                'email' => 'tina.talisa@set.wapresri.go.id',
                'nip' => '20241129003',
                'jabatan' => 'Staf Khusus Wakil Presiden',
                'biro' => 'KMX_0201',
            ],
            [
                'nama' => 'Nicolaus Teguh Budi Harjanto, S.IP., M.A., Ph.D.',
                'email' => 'nicolaus.teguh.budi.harjanto@set.wapresri.go.id',
                'nip' => '20250505001',
                'jabatan' => 'Staf Khusus Wakil Presiden',
                'biro' => 'KMX_0201',
            ],
            [
                'nama' => 'Devid Agus Yunanto',
                'email' => 'devid.agus.yunanto@set.wapresri.go.id',
                'nip' => '20241021001',
                'jabatan' => 'Staf Khusus Wakil Presiden',
                'biro' => 'KMX_0201',
            ],
        ];

        DB::transaction(function () use ($data) {
            foreach ($data as $value) {
                MasterPegawai::create([
                    'nip' => $value['nip'],
                    'name' => $value['nama'],
                    'image' => 'images/asn/' . $value['nip'] . '.jpg',
                    'jabatan' => $value['jabatan'],
                    'kode_instansi' => null,
                    'kode_unit' => 02,
                    'kode_deputi' => null,
                    'kode_biro' => $value['biro'],
                    'kode_bagian' => null,
                    'kode_subbagian' => null,
                ]);

                User::create([
                    'email' => $value['email'],
                    'nip' => $value['nip'],
                    'password' => Hash::make($value['nip']),
                    'role' => ['evaluator'],
                    'userable_type' => MasterPegawai::class,
                    'userable_id' => $value['nip'],
                    'is_ldap' => false,
                ]);
            }
        });
    }
}
