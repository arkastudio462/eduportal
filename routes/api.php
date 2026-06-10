<?php

use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\SiswaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::get('/siswa/{siswa}', [SiswaController::class, 'show']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy']);

    Route::get('/nilai', [NilaiController::class, 'index']);
    Route::get('/nilai/siswa/{siswa}', [NilaiController::class, 'bySiswa']);
    Route::post('/nilai', [NilaiController::class, 'store']);
    Route::put('/nilai/{nilai}', [NilaiController::class, 'update']);
    Route::delete('/nilai/{nilai}', [NilaiController::class, 'destroy']);

    Route::get('/jadwal', [JadwalController::class, 'index']);
    Route::get('/jadwal/kelas/{kelas}', [JadwalController::class, 'byKelas']);
    Route::post('/jadwal', [JadwalController::class, 'store']);
    Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update']);
    Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy']);
});
