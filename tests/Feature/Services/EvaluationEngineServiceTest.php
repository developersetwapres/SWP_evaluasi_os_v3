<?php

use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\Jabatan;
use App\Models\KelompokJabatan;
use App\Models\Outsourcing;
use App\Models\Penugasan;
use App\Models\Pilar;
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
