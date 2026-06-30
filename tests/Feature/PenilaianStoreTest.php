<?php

use App\Models\Behavioral;
use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use App\Models\Outsourcing;
use App\Models\Penugasan;
use App\Models\Pilar;
use App\Models\Siklus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function createPenilaianScenario(): array
{
    $user = User::forceCreate([
        'nip' => 'EV-001',
        'is_ldap' => false,
        'email' => 'evaluator@example.com',
        'email_verified_at' => now(),
        'role' => ['evaluator'],
        'password' => 'password',
        'is_active' => true,
    ]);

    $siklus = Siklus::forceCreate([
        'title' => 'Semester I 2026',
        'uuid' => (string) Str::uuid(),
        'tanggal_mulai' => now()->startOfMonth(),
        'tanggal_selesai' => now()->endOfMonth(),
        'is_active' => true,
    ]);

    $evaluatorWeight = BobotSkor::forceCreate([
        'siklus_id' => $siklus->id,
        'title' => 'Atasan',
        'kode_bobot' => 'atasan',
        'bobot' => 100,
    ]);

    $kelompokJabatan = KelompokJabatan::forceCreate([
        'nama_kelompok' => 'Outsourcing',
        'uuid' => (string) Str::uuid(),
    ]);

    $jabatan = Jabatan::forceCreate([
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'nama_jabatan' => 'Administrative Staff',
        'kode_jabatan' => 'ADM',
    ]);

    $outsourcing = Outsourcing::forceCreate([
        'uuid' => (string) Str::uuid(),
        'name' => 'Outsourcing Staff',
        'image' => 'staff.jpg',
        'jabatan_id' => $jabatan->id,
        'is_active' => true,
        'nip' => 'OS-001',
        'kode_biro' => 'BR-001',
    ]);

    $penugasan = Penugasan::forceCreate([
        'uuid' => (string) Str::uuid(),
        'bobot_skor_id' => $evaluatorWeight->id,
        'siklus_id' => $siklus->id,
        'outsourcing_id' => $outsourcing->id,
        'penilai_id' => $user->id,
        'tipe_penilai' => 'atasan',
        'status' => 'incomplete',
    ]);

    $indicatorCounts = [3, 4, 2];
    $indicators = collect();

    foreach ($indicatorCounts as $pillarIndex => $indicatorCount) {
        $pillarWeight = BobotSkor::forceCreate([
            'siklus_id' => $siklus->id,
            'title' => 'Pillar '.($pillarIndex + 1),
            'kode_bobot' => 'pillar-'.($pillarIndex + 1),
            'bobot' => [40, 40, 20][$pillarIndex],
        ]);

        $pillar = Pilar::forceCreate([
            'title' => 'Pillar '.($pillarIndex + 1),
            'uuid' => (string) Str::uuid(),
            'bobot_skor_id' => $pillarWeight->id,
        ]);

        foreach (range(1, $indicatorCount) as $indicatorIndex) {
            $indicator = Indikator::forceCreate([
                'pilar_id' => $pillar->id,
                'jabatan_id' => $jabatan->id,
                'title' => 'Indicator '.($indicators->count() + 1),
                'defenisi' => 'Indicator definition',
                'uuid' => (string) Str::uuid(),
                'example' => json_encode(['example' => 'Example behavior'], JSON_THROW_ON_ERROR),
            ]);

            foreach (range(1, 4) as $score) {
                Behavioral::forceCreate([
                    'indikator_id' => $indicator->id,
                    'behavioral' => 'Behavior '.$score,
                    'uuid' => (string) Str::uuid(),
                    'skor' => $score,
                ]);
            }

            $indicators->push($indicator);
        }
    }

    return [$user, $penugasan, $indicators];
}

it('stores BARS scores and completes the assignment', function (): void {
    [$user, $penugasan, $indicators] = createPenilaianScenario();

    $response = $this
        ->actingAs($user)
        ->post(route('penilaian.store', $penugasan), [
            'evaluator_id' => $user->id,
            'outsourcing_id' => $penugasan->outsourcing_id,
            'scores' => $indicators
                ->map(fn (Indikator $indicator): array => [
                    'indicator_id' => $indicator->id,
                    'value' => 4,
                ])
                ->all(),
            'notes' => 'Consistent service quality.',
        ]);

    $response->assertRedirect(route('home'));

    $this->assertDatabaseCount('penilaians', 9);
    $this->assertDatabaseHas('penilaians', [
        'penugasan_id' => $penugasan->id,
        'indikator_id' => $indicators->first()->id,
        'nilai' => 4,
    ]);
    $this->assertDatabaseHas('penugasans', [
        'id' => $penugasan->id,
        'status' => 'completed',
        'catatan' => 'Consistent service quality.',
    ]);
});

it('requires every expected indicator score', function (): void {
    [$user, $penugasan, $indicators] = createPenilaianScenario();

    $response = $this
        ->actingAs($user)
        ->from(route('penilaian.create', $penugasan))
        ->post(route('penilaian.store', $penugasan), [
            'scores' => $indicators
                ->take(8)
                ->map(fn (Indikator $indicator): array => [
                    'indicator_id' => $indicator->id,
                    'value' => 3,
                ])
                ->all(),
            'notes' => null,
        ]);

    $response
        ->assertRedirect(route('penilaian.create', $penugasan))
        ->assertSessionHasErrors('scores');

    $this->assertDatabaseCount('penilaians', 0);
    $this->assertDatabaseHas('penugasans', [
        'id' => $penugasan->id,
        'status' => 'incomplete',
    ]);
});
