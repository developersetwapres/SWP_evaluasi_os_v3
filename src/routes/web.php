<?php

use App\Http\Controllers\BehavioralController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndikatorController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KelompokJabatanController;
use App\Http\Controllers\MasterPegawaiController;
use App\Http\Controllers\OutsourcingController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PilarController;
use App\Http\Controllers\Synchronization\UserController as SynchronizationUserController;
use App\Http\Controllers\UserController;
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
    Route::get('/dashboard/saran-perbaikan-outsourcing/export', [PenugasanController::class, 'exportSaranPerbaikan'])->name('os.exportSaranEvaluator');
    Route::get('/dashboard/saran-perbaikan-outsourcing', [PenugasanController::class, 'saranPerbaikan'])->name('os.saranEvaluator');

    Route::get('/dashboard/user-management/{user}', [UserController::class, 'index'])->name('user.index');

    Route::get('/dashboard/nilai-akhir/{outsourcing:uuid}', [OutsourcingController::class, 'nilaiAkhir'])->name('os.rekapaspekevaluator');
    Route::get('/dashboard/rekap-nilai/{outsourcing:uuid}', [OutsourcingController::class, 'rekapNilai'])->name('os.detailperaspek');
    Route::get('/dashboard/catatan-evaluator/{outsourcing:uuid}', [OutsourcingController::class, 'catatanEvaluator'])->name('os.catatanEvaluator');
    Route::get('/dashboard/nilai-perkriteria/{outsourcing:uuid}/{tipePenilai?}', [OutsourcingController::class, 'nilaiPerkriteria'])->name('os.nilaiPerkriteria');

    Route::get('/dashboard/nilai-mentahan', [OutsourcingController::class, 'exportNilaiMentahan'])->name('os.exportMentahan');
});

Route::middleware(['auth', 'verified', 'role:administrator'])->group(function () {
    Route::put('/dashboard/outsourcing-update/{outsourcing:uuid}', [OutsourcingController::class, 'update'])->name('outsourcing.update');
    Route::post('/dashboard/outsourcing-store', [OutsourcingController::class, 'store'])->name('outsourcing.store');

    Route::put('/dashboard/pegawai-update/{pegawai:uuid}', [MasterPegawaiController::class, 'update'])->name('pegawai.update');
    Route::post('/dashboard/pegawai-store', [MasterPegawaiController::class, 'store'])->name('pegawai.store');

    Route::get('/dashboard/status-penilaian', [PenugasanController::class, 'statusPenilaian'])->name('penugasan.statuspenilaian');
    Route::get('/dashboard/status-penilaian-by-evaluators', [PenugasanController::class, 'byEvaluators'])->name('penugasan.evaluators');
    Route::get('/dashboard/status-penilaian-by-outsourcing', [PenugasanController::class, 'byOutsourcings'])->name('penugasan.outsourcings');

    Route::post('/dashboard/penugasan-peer/reset/{penugasan:uuid}', [PenugasanController::class, 'reset'])->name('penugasan.reset');

    Route::post('/upload-temp-image', [FotoUserService::class, 'uploadTempImage'])->name('upload.tempImage');

    Route::put('synchronization-data-user/{type}', [SynchronizationUserController::class, 'update'])->name('synchronization.datauser');


    // Dashboard Administrator -------------------------------------------------
    //--------------------------------------------------------------------------
    Route::get('/dashboard/administrator', [DashboardController::class, 'home'])
        ->name('administrator.home');

    // User ------------------------------------------------------------------
    Route::get('/dashboard/administrator/user-management', [UserController::class, 'indexAdministrator'])
        ->name('user.indexAdministrator');
    Route::put('/dashboard/administrator/user-management/{user}', [UserController::class, 'updateAdministrator'])
        ->name('user.updateAdministrator');
    Route::delete('/dashboard/administrator/user-management/{user}', [UserController::class, 'destroyAdministrator'])
        ->name('user.destroyAdministrator');

    // Pilar -----------------------------------------------------------------
    Route::get('/dashboard/administrator/pilar', [PilarController::class, 'index'])->name('pilar.index');
    Route::get('/dashboard/administrator/pilar/create', [PilarController::class, 'create'])->name('pilar.create');
    Route::post('/dashboard/administrator/pilar/store', [PilarController::class, 'store'])->name('pilar.store');
    Route::get('/dashboard/administrator/pilar/{pilar:uuid}/edit', [PilarController::class, 'edit'])->name('pilar.edit');
    Route::put('/dashboard/administrator/pilar/{pilar:uuid}', [PilarController::class, 'update'])->name('pilar.update');
    Route::delete('/dashboard/administrator/pilar/{pilar:uuid}', [PilarController::class, 'destroy'])->name('pilar.destroy');

    // Indikator -------------------------------------------------------------
    Route::get('/dashboard/administrator/indikator', [IndikatorController::class, 'index'])->name('indikator.index');
    Route::get('/dashboard/administrator/indikator/create', [IndikatorController::class, 'create'])->name('indikator.create');
    Route::post('/dashboard/administrator/indikator/store', [IndikatorController::class, 'store'])->name('indikator.store');
    Route::get('/dashboard/administrator/indikator/{indikator:uuid}/edit', [IndikatorController::class, 'edit'])->name('indikator.edit');
    Route::put('/dashboard/administrator/indikator/{indikator:uuid}', [IndikatorController::class, 'update'])->name('indikator.update');
    Route::delete('/dashboard/administrator/indikator/{indikator:uuid}', [IndikatorController::class, 'destroy'])->name('indikator.destroy');

    // Behavioral ------------------------------------------------------------
    Route::get('/dashboard/administrator/behavioral', [BehavioralController::class, 'index'])->name('behavioral.index');
    Route::get('/dashboard/administrator/behavioral/create', [BehavioralController::class, 'create'])->name('behavioral.create');
    Route::post('/dashboard/administrator/behavioral/store', [BehavioralController::class, 'store'])->name('behavioral.store');
    Route::get('/dashboard/administrator/behavioral/{behavioral:uuid}/edit', [BehavioralController::class, 'edit'])->name('behavioral.edit');
    Route::put('/dashboard/administrator/behavioral/{behavioral:uuid}', [BehavioralController::class, 'update'])->name('behavioral.update');
    Route::delete('/dashboard/administrator/behavioral/{behavioral:uuid}', [BehavioralController::class, 'destroy'])->name('behavioral.destroy');

    // Kelompok Jabatan Os ---------------------------------------------------
    Route::get('/dashboard/administrator/kelompok-jabatan', [KelompokJabatanController::class, 'index'])->name('kelompokJabatan.index');
    Route::get('/dashboard/administrator/kelompok-jabatan/create', [KelompokJabatanController::class, 'create'])->name('kelompokJabatan.create');
    Route::post('/dashboard/administrator/kelompok-jabatan/store', [KelompokJabatanController::class, 'store'])->name('kelompokJabatan.store');
    Route::get('/dashboard/administrator/kelompok-jabatan/{kelompokJabatan:uuid}/edit', [KelompokJabatanController::class, 'edit'])->name('kelompokJabatan.edit');
    Route::put('/dashboard/administrator/kelompok-jabatan/{kelompokJabatan:uuid}', [KelompokJabatanController::class, 'update'])->name('kelompokJabatan.update');
    Route::delete('/dashboard/administrator/kelompok-jabatan/{kelompokJabatan:uuid}', [KelompokJabatanController::class, 'destroy'])->name('kelompokJabatan.destroy');

    // Jabatan Outsourcing ---------------------------------------------------
    Route::get('/dashboard/administrator/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::get('/dashboard/administrator/jabatan/create', [JabatanController::class, 'create'])->name('jabatan.create');
    Route::post('/dashboard/administrator/jabatan/store', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('/dashboard/administrator/jabatan/{jabatan}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('/dashboard/administrator/jabatan/{jabatan}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/dashboard/administrator/jabatan/{jabatan}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
});

require __DIR__ . '/settings.php';
