<?php

use App\Http\Resources\StatusPenilaianByEvaluatorResource;
use App\Http\Resources\StatusPenilaianByOutsourcingResource;
use App\Models\Behavioral;
use App\Models\Biro;
use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use App\Models\MasterPegawai;
use App\Models\Outsourcing;
use App\Models\Penilaian;
use App\Models\Penugasan;
use App\Models\Pilar;
use App\Models\Siklus;
use App\Models\User;
use App\Services\Penugasan\PenugasanDashboardService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

function createPenugasanDashboardScenario(): array
{
    $siklus = Siklus::forceCreate([
        'uuid' => (string) Str::uuid(),
        'title' => 'Semester I tahun 2026',
        'tanggal_mulai' => now()->startOfMonth(),
        'tanggal_selesai' => now()->endOfMonth(),
        'is_active' => true,
    ]);

    foreach (['atasan', 'penerima_layanan1', 'penerima_layanan2'] as $kodeBobot) {
        BobotSkor::forceCreate([
            'siklus_id' => $siklus->id,
            'title' => $kodeBobot,
            'kode_bobot' => $kodeBobot,
            'bobot' => 33.33,
        ]);
    }

    $biro = Biro::forceCreate([
        'kode_biro' => '0201',
        'nama_biro' => 'Biro Umum',
    ]);

    $kelompokJabatan = KelompokJabatan::forceCreate([
        'uuid' => (string) Str::uuid(),
        'nama_kelompok' => 'Administrasi',
    ]);

    $jabatan = Jabatan::forceCreate([
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'nama_jabatan' => 'Administrasi Perkantoran',
        'kode_jabatan' => 'ADM',
    ]);

    $outsourcing = Outsourcing::forceCreate([
        'uuid' => (string) Str::uuid(),
        'nip' => 'OS-001',
        'name' => 'Outsourcing Satu',
        'image' => 'os.jpg',
        'jabatan_id' => $jabatan->id,
        'kode_biro' => $biro->kode_biro,
        'is_active' => true,
    ]);

    $evaluatorPegawai = MasterPegawai::forceCreate([
        'uuid' => (string) Str::uuid(),
        'nip' => 'PG-001',
        'name' => 'Evaluator Satu',
        'image' => 'pg.jpg',
        'jabatan' => 'Kepala Subbagian',
        'kode_unit' => '02',
        'kode_biro' => $biro->kode_biro,
    ]);

    $evaluatorUser = User::forceCreate([
        'userable_id' => $evaluatorPegawai->nip,
        'userable_type' => MasterPegawai::class,
        'nip' => 'USER-PG-001',
        'nip_sso' => $evaluatorPegawai->nip,
        'is_ldap' => false,
        'email' => 'evaluator@example.test',
        'role' => ['evaluator'],
        'password' => 'password',
        'email_verified_at' => now(),
    ]);

    $penugasan = Penugasan::forceCreate([
        'uuid' => (string) Str::uuid(),
        'bobot_skor_id' => BobotSkor::where('kode_bobot', 'atasan')->value('id'),
        'siklus_id' => $siklus->id,
        'outsourcing_id' => $outsourcing->id,
        'penilai_id' => $evaluatorUser->id,
        'tipe_penilai' => 'atasan',
        'status' => 'completed',
        'catatan' => 'Kinerja baik.',
    ]);

    $operator = User::forceCreate([
        'userable_id' => null,
        'userable_type' => null,
        'nip' => 'OP-001',
        'is_ldap' => false,
        'email' => 'operator@example.test',
        'role' => ['operator'],
        'password' => 'password',
        'email_verified_at' => now(),
    ]);

    $administrator = User::forceCreate([
        'userable_id' => null,
        'userable_type' => null,
        'nip' => 'ADM-001',
        'is_ldap' => false,
        'email' => 'administrator@example.test',
        'role' => ['administrator'],
        'password' => 'password',
        'email_verified_at' => now(),
    ]);

    return [$operator, $administrator, $evaluatorUser, $penugasan, $outsourcing];
}

it('sends lean evaluator assignment payloads to the penugasan page', function (): void {
    [$operator] = createPenugasanDashboardScenario();

    $response = $this
        ->actingAs($operator)
        ->get(route('penugasan.index'));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('admin/penugasan/page')
            ->where('outsourcing.0.name', 'Outsourcing Satu')
            ->where('outsourcing.0.nama_jabatan', 'Administrasi Perkantoran')
            ->where('outsourcing.0.biro', 'Biro Umum')
            ->where('outsourcing.0.evaluators.atasan.name', 'Evaluator Satu')
            ->where('outsourcing.0.evaluators.atasan.jabatan', 'Kepala Subbagian')
            ->where('evaluators.0.name', 'Evaluator Satu')
            ->where('evaluators.0.biro.nama_biro', 'Biro Umum')
        );
});

it('builds status resources without lazy loading relations', function (): void {
    createPenugasanDashboardScenario();

    Model::preventLazyLoading();

    try {
        $service = app(PenugasanDashboardService::class);

        $byOutsourcings = StatusPenilaianByOutsourcingResource::collection(
            $service->statusByOutsourcings()
        )->resolve();

        $byEvaluators = StatusPenilaianByEvaluatorResource::collection(
            $service->statusByEvaluators()
        )->resolve();
    } finally {
        Model::preventLazyLoading(false);
    }

    expect($byOutsourcings[0]['outsourcing_jabatan'])->toBe('Administrasi Perkantoran')
        ->and($byOutsourcings[0]['evaluatorsAtasan']['name'])->toBe('Evaluator Satu')
        ->and($byEvaluators[0]['outsourcing_jabatan'])->toBe('Administrasi Perkantoran')
        ->and($byEvaluators[0]['evaluator_name'])->toBe('Evaluator Satu');
});

it('resets a penugasan by deleting its scores and restoring incomplete status', function (): void {
    [, $administrator, , $penugasan, $outsourcing] = createPenugasanDashboardScenario();

    $pilarWeight = BobotSkor::forceCreate([
        'siklus_id' => $penugasan->siklus_id,
        'title' => 'Pilar',
        'kode_bobot' => 'pilar',
        'bobot' => 100,
    ]);

    $pilar = Pilar::forceCreate([
        'uuid' => (string) Str::uuid(),
        'title' => 'Orientasi Layanan',
        'bobot_skor_id' => $pilarWeight->id,
    ]);

    $indicator = Indikator::forceCreate([
        'uuid' => (string) Str::uuid(),
        'pilar_id' => $pilar->id,
        'kelompok_jabatan_id' => Jabatan::find($outsourcing->jabatan_id)->kelompok_jabatan_id,
        'title' => 'Keramahan',
        'defenisi' => 'Memberikan layanan dengan ramah.',
        'example' => json_encode(['contoh' => 'Menyapa pengguna'], JSON_THROW_ON_ERROR),
    ]);

    Behavioral::forceCreate([
        'uuid' => (string) Str::uuid(),
        'indikator_id' => $indicator->id,
        'behavioral' => 'Sangat baik',
        'skor' => 4,
    ]);

    Penilaian::forceCreate([
        'uuid' => (string) Str::uuid(),
        'penugasan_id' => $penugasan->id,
        'indikator_id' => $indicator->id,
        'nilai' => 4,
    ]);

    $response = $this
        ->actingAs($administrator)
        ->post(route('penugasan.reset', $penugasan));

    $response->assertRedirect();

    $this->assertDatabaseMissing('penilaians', [
        'penugasan_id' => $penugasan->id,
    ]);

    $this->assertDatabaseHas('penugasans', [
        'id' => $penugasan->id,
        'status' => 'incomplete',
        'catatan' => null,
    ]);
});
