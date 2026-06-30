<?php

use App\Http\Controllers\OutsourcingController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterPegawaiController;
use App\Services\Uploadfile\FotoUserService;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified', 'role:evaluator'])->group(function () {
    Route::get('/', [PenugasanController::class, 'home'])->name('home');

    Route::get('/penilaian/{penugasan:uuid}', [PenilaianController::class, 'create'])->name('penilaian.create');

    Route::post('/penilaian/store/{penugasan:uuid}', [PenilaianController::class, 'store'])->name('penilaian.store');
});


Route::middleware(['auth', 'verified', 'role:operator'])->group(function () {
    Route::get('/dashboard', [PenilaianController::class, 'rekaphasil'])->name('dashboard');
    Route::get('/dashboard/ranking-skor', [PenilaianController::class, 'ranking'])->name('os.ranking');

    Route::get('/dashboard/penugasan-peer', [PenugasanController::class, 'index'])->name('penugasan.index');
    Route::post('/dashboard/penugasan-peer/store/{outsourcing:uuid}', [PenugasanController::class, 'store'])->name('penugasan.store');
    Route::get('/dashboard/saran-perbaikan-outsourcing', [PenugasanController::class, 'saranPerbaikan'])->name('os.saranEvaluator');

    Route::get('/dashboard/user-management/{user}', [UserController::class, 'index'])->name('user.index');

    Route::get('/dashboard/nilai-akhir/{outsourcing:uuid}', [OutsourcingController::class, 'nilaiAkhir'])->name('os.rekapaspekevaluator');
    Route::get('/dashboard/rekap-nilai/{outsourcing:uuid}', [OutsourcingController::class, 'rekapNilai'])->name('os.detailperaspek');
    Route::get('/dashboard/catatan-evaluator/{outsourcing:uuid}', [OutsourcingController::class, 'catatanEvaluator'])->name('os.catatanEvaluator');
    Route::get('/dashboard/nilai-perkriteria/{outsourcing:uuid}/{tipePenilai?}', [OutsourcingController::class, 'nilaiPerkriteria'])->name('os.nilaiPerkriteria');
});

Route::middleware(['auth', 'verified', 'role:administrator'])->group(function () {
    Route::put('/dashboard/outsourcing-update/{outsourcing:uuid}', [OutsourcingController::class, 'update'])->name('outsourcing.update');
    Route::post('/dashboard/outsourcing-store', [OutsourcingController::class, 'store'])->name('outsourcing.store');

    Route::put('/dashboard/pegawai-update/{pegawai:uuid}', [MasterPegawaiController::class, 'update'])->name('pegawai.update');
    Route::post('/dashboard/pegawai-store', [MasterPegawaiController::class, 'store'])->name('pegawai.store');

    Route::get('/dashboard/status-penilaian', [PenugasanController::class, 'statusPenilaian'])->name('penugasan.statuspenilaian');
    Route::get('/dashboard/status-penilaian-by-evaluators', [PenugasanController::class, 'byEvaluators'])->name('penugasan.evaluators');
    Route::get('/dashboard/status-penilaian-by-outsourcing', [PenugasanController::class, 'byOutsourcings'])->name('penugasan.outsourcings');

    Route::post('/dashboard/penugasan-peer/reset/{Penugasan:uuid}', [PenugasanController::class, 'reset'])->name('penugasan.reset');

    Route::post('/upload-temp-image', [FotoUserService::class, 'uploadTempImage'])->name('upload.tempImage');
});

require __DIR__ . '/settings.php';
