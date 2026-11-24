<?php

use App\Http\Controllers\Api\OutsourcingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {

    Route::put('/integration-pengemudi', [OutsourcingController::class, 'integrationPengemudi'])
        ->middleware('abilities:os-read')
        ->name('synchronization.pengemudi');
});
