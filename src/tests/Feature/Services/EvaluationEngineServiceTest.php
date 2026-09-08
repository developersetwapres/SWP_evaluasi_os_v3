<?php

use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use App\Models\Outsourcing;
use App\Models\Penilaian;
use App\Models\Penugasan;
use App\Models\Pilar;
use App\Models\Siklus;
use App\Services\Penilaian\EvaluationEngineService;
use Tests\TestCase;

it('getEvaluationData filters indikators by kelompok_jabatan_id from jabatan', function () {
    // Create kelompok jabatan
    $kelompokJabatan1 = KelompokJabatan::factory()->create(['nama_kelompok' => 'Kelompok 1']);
    $kelompokJabatan2 = KelompokJabatan::factory()->create(['nama_kelompok' => 'Kelompok 2']);

    // Create jabatan with different kelompok_jabatan
    $jabatan1 = Jabatan::factory()->create(['kelompok_jabatan_id' => $kelompokJabatan1->id]);
    $jabatan2 = Jabatan::factory()->create(['kelompok_jabatan_id' => $kelompokJabatan2->id]);

    // Create pilars and bobot skor
    $bobotSkor = BobotSkor::factory()->create();
    $pilar = Pilar::factory()->create(['bobot_skor_id' => $bobotSkor->id]);

    // Create indikators for both kelompok jabatan
    $indikator1 = Indikator::factory()->create([
        'pilar_id' => $pilar->id,
        'kelompok_jabatan_id' => $kelompokJabatan1->id,
    ]);
    $indikator2 = Indikator::factory()->create([
        'pilar_id' => $pilar->id,
        'kelompok_jabatan_id' => $kelompokJabatan2->id,
    ]);

    // Create outsourcing and penugasan
    $outsourcing = Outsourcing::factory()->create(['jabatan_id' => $jabatan1->id]);
    $penugasan = Penugasan::factory()->create(['outsourcing_id' => $outsourcing->id]);

    // Call the service
    $engine = new EvaluationEngineService;
    $result = $engine->getEvaluationData($penugasan, $jabatan1->id);

    // Assert that only indikators from kelompok_jabatan1 are returned
    $indikatorIds = $result[0]->indikator->pluck('id')->toArray();

    expect($indikatorIds)->toContain($indikator1->id);
    expect($indikatorIds)->not->toContain($indikator2->id);
})->uses(TestCase::class);

it('builds detailed export rows with pilar and indicator values', function () {
    $kelompokJabatan = KelompokJabatan::factory()->create(['nama_kelompok' => 'Kelompok A']);
    $jabatan = Jabatan::factory()->create(['kelompok_jabatan_id' => $kelompokJabatan->id]);
    $outsourcing = Outsourcing::factory()->create(['jabatan_id' => $jabatan->id]);
    $penugasan = Penugasan::factory()->create([
        'outsourcing_id' => $outsourcing->id,
        'siklus_id' => Siklus::factory()->create(['is_active' => true])->id,
    ]);

    $pilar1 = Pilar::factory()->create(['title' => 'Pilar 1']);
    $pilar2 = Pilar::factory()->create(['title' => 'Pilar 2']);

    $indikator1 = Indikator::factory()->create([
        'pilar_id' => $pilar1->id,
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'title' => 'Indikator 1',
    ]);
    $indikator2 = Indikator::factory()->create([
        'pilar_id' => $pilar1->id,
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'title' => 'Indikator 2',
    ]);
    $indikator3 = Indikator::factory()->create([
        'pilar_id' => $pilar2->id,
        'kelompok_jabatan_id' => $kelompokJabatan->id,
        'title' => 'Indikator 3',
    ]);
    Indikator::factory()->create([
        'pilar_id' => $pilar1->id,
        'kelompok_jabatan_id' => KelompokJabatan::factory()->create()->id,
        'title' => 'Indikator Kelompok Lain',
    ]);

    Penilaian::factory()->create([
        'uuid' => fake()->uuid(),
        'penugasan_id' => $penugasan->id,
        'indikator_id' => $indikator1->id,
        'nilai' => 4.0,
    ]);
    Penilaian::factory()->create([
        'uuid' => fake()->uuid(),
        'penugasan_id' => $penugasan->id,
        'indikator_id' => $indikator2->id,
        'nilai' => 3.5,
    ]);

    $engine = new EvaluationEngineService;
    $exportData = $engine->buildExportRows($outsourcing);

    expect($exportData['kelompokJabatan'])->toBe('Kelompok A')
        ->and($exportData['columns'])->toBe([
            'Nama Outsourcing',
            'Jabatan Outsourcing',
            'Kelompok Jabatan Outsourcing',
            'Pilar 1 - I.1',
            'Pilar 1 - I.2',
            'Pilar 2 - I.1',
        ])
        ->and($exportData['rows'])->toHaveCount(1)
        ->and($exportData['rows'][0]['Nama Outsourcing'])->toBe($outsourcing->name)
        ->and($exportData['rows'][0]['Kelompok Jabatan Outsourcing'])->toBe('Kelompok A')
        ->and($exportData['rows'][0]['Pilar 1 - I.1'])->toBe(4.0)
        ->and($exportData['rows'][0]['Pilar 1 - I.2'])->toBe(3.5)
        ->and($exportData['rows'][0]['Pilar 2 - I.1'])->toBe(0);
})->uses(TestCase::class);

it('builds different indicator columns per kelompok jabatan', function () {
    $kelompokJabatan1 = KelompokJabatan::factory()->create(['nama_kelompok' => 'Kelompok 1']);
    $kelompokJabatan2 = KelompokJabatan::factory()->create(['nama_kelompok' => 'Kelompok 2']);

    $jabatan1 = Jabatan::factory()->create(['kelompok_jabatan_id' => $kelompokJabatan1->id]);
    $jabatan2 = Jabatan::factory()->create(['kelompok_jabatan_id' => $kelompokJabatan2->id]);

    $outsourcing1 = Outsourcing::factory()->create(['jabatan_id' => $jabatan1->id]);
    $outsourcing2 = Outsourcing::factory()->create(['jabatan_id' => $jabatan2->id]);

    Penugasan::factory()->create([
        'outsourcing_id' => $outsourcing1->id,
        'siklus_id' => Siklus::factory()->create(['is_active' => true])->id,
    ]);
    Penugasan::factory()->create([
        'outsourcing_id' => $outsourcing2->id,
        'siklus_id' => Siklus::factory()->create(['is_active' => true])->id,
    ]);

    $pilar = Pilar::factory()->create();

    Indikator::factory()->count(2)->create([
        'pilar_id' => $pilar->id,
        'kelompok_jabatan_id' => $kelompokJabatan1->id,
    ]);
    Indikator::factory()->count(3)->create([
        'pilar_id' => $pilar->id,
        'kelompok_jabatan_id' => $kelompokJabatan2->id,
    ]);

    $engine = new EvaluationEngineService;

    $exportData1 = $engine->buildExportRows($outsourcing1);
    $exportData2 = $engine->buildExportRows($outsourcing2);

    expect($exportData1['columns'])->toHaveCount(5)
        ->and($exportData2['columns'])->toHaveCount(6)
        ->and($exportData1['kelompokJabatan'])->toBe('Kelompok 1')
        ->and($exportData2['kelompokJabatan'])->toBe('Kelompok 2');
})->uses(TestCase::class);
