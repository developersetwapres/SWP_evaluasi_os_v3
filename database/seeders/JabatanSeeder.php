<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'id' => 1,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'PRAMUBAKTI',
                'nama_jabatan' => 'Pramubakti / Pramusaji'
            ],
            [
                'id' => 2,
                'kelompok_jabatan_id' => 4,
                'kode_jabatan' => 'TATA_TEMPAT',
                'nama_jabatan' => 'Petugas Tata Tempat'
            ],
            [
                'id' => 3,
                'kelompok_jabatan_id' => 1,
                'kode_jabatan' => 'PENGEMUDI',
                'nama_jabatan' => 'Pengemudi'
            ],
            [
                'id' => 4,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'TEKNISI_KENDARAAN',
                'nama_jabatan' => 'Teknisi Kendaraan'
            ],

            [
                'id' => 5,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'STAF_ADMIN',
                'nama_jabatan' => 'Staf Administrasi'
            ],
            [
                'id' => 6,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'TEKNISI_KOMPUTER',
                'nama_jabatan' => 'Teknisi Komputer'
            ],
            [
                'id' => 7,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'PROGRAMMER',
                'nama_jabatan' => 'Programmer'
            ],

            [
                'id' => 8,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'JURU_FOTO',
                'nama_jabatan' => 'Juru Foto'
            ],
            [
                'id' => 9,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'DESAINER_GRAFIS',
                'nama_jabatan' => 'Desainer Grafis'
            ],
            [
                'id' => 10,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'PETUGAS_KEBERSIHAN',
                'nama_jabatan' => 'Petugas Kebersihan'
            ],
            [
                'id' => 11,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'PETUGAS_TAMAN',
                'nama_jabatan' => 'Petugas Taman'
            ],

            [
                'id' => 12,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'PETUGAS_TEKNIS_ME',
                'nama_jabatan' => 'Petugas Teknisi ME'
            ],
            [
                'id' => 13,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'KOORDINATOR_PENGAWAS',
                'nama_jabatan' => 'Koordinator / Pengawas'
            ],
            [
                'id' => 14,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'RESEPSIONIS',
                'nama_jabatan' => 'Resepsionis'
            ],
            [
                'id' => 15,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'TEKNISI_JARINGAN',
                'nama_jabatan' => 'Teknisi Jaringan'
            ],
            [
                'id' => 16,
                'kelompok_jabatan_id' => 2,
                'kode_jabatan' => 'UMUM',
                'nama_jabatan' => 'Umum'
            ],
        ];

        foreach ($jabatans as $key => $value) {
            Jabatan::create([
                'kelompok_jabatan_id' => $value['kelompok_jabatan_id'],
                'kode_jabatan' => $value['kode_jabatan'],
                'nama_jabatan' => $value['nama_jabatan'],
            ]);
        }
    }
}
