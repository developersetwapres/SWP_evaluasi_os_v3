<?php

use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use App\Models\Outsourcing;
use App\Models\User;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

function createSaranPerbaikanExportData(): array
{
    $kelompokJabatan = KelompokJabatan::forceCreate([
        'uuid' => (string) Str::uuid(),
        'nama_kelompok' => 'Administrasi',
    ]);

    $jabatanPertama = Jabatan::forceCreate([
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'nama_jabatan' => 'Administrasi Perkantoran',
        'kode_jabatan' => 'ADMIN',
    ]);

    $jabatanKedua = Jabatan::forceCreate([
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'nama_jabatan' => 'Desainer Grafis',
        'kode_jabatan' => 'DESAIN',
    ]);

    foreach ([
        ['jabatan' => $jabatanPertama, 'name' => 'Outsourcing Pertama'],
        ['jabatan' => $jabatanKedua, 'name' => 'Outsourcing Kedua'],
    ] as $data) {
        Outsourcing::forceCreate([
            'uuid' => (string) Str::uuid(),
            'nip' => Str::random(10),
            'name' => $data['name'],
            'image' => 'outsourcing.jpg',
            'jabatan_id' => $data['jabatan']->id,
            'is_active' => true,
        ]);
    }

    Outsourcing::forceCreate([
        'uuid' => (string) Str::uuid(),
        'nip' => Str::random(10),
        'name' => 'Outsourcing Nonaktif',
        'image' => 'outsourcing.jpg',
        'jabatan_id' => $jabatanKedua->id,
        'is_active' => false,
    ]);

    $operator = User::forceCreate([
        'nip' => 'OP-EXPORT-SARAN',
        'is_ldap' => false,
        'email' => 'operator-export-saran@example.test',
        'role' => ['operator'],
        'password' => 'password',
        'email_verified_at' => now(),
    ]);

    return [$operator, $jabatanPertama, $jabatanKedua];
}

it('loads every active job position for the feedback export', function (): void {
    [$operator, $jabatanPertama, $jabatanKedua] =
        createSaranPerbaikanExportData();

    $response = $this
        ->actingAs($operator)
        ->get(route('os.exportSaranEvaluator', [
            'jabatan_id' => $jabatanPertama->id,
        ]));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('exportexcel/exportSaranPerbaikan')
            ->has('outsourcings', 2)
            ->where('outsourcings.0.jabatan_id', $jabatanPertama->id)
            ->where('outsourcings.1.jabatan_id', $jabatanKedua->id)
            ->where('outsourcings.0.evaluators.0.name', 'Outsourcing Pertama')
            ->where('outsourcings.1.evaluators.0.name', 'Outsourcing Kedua'));
});
