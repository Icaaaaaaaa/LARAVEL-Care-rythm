<?php

use App\Http\Controllers\KegiatanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//kegiatan
Route::resource('/kegiatan', KegiatanController::class);

// Pencapaian
use App\Http\Controllers\PencapaianController;

Route::get('/pencapaian', [PencapaianController::class, 'index']);
Route::post('/pencapaian/tambah', [PencapaianController::class, 'tambah']);
Route::post('/pencapaian/kurang', [PencapaianController::class, 'kurang']);
Route::post('/pencapaian/reset', [PencapaianController::class, 'reset']);
Route::post('/pencapaian/tambah-kegiatan', [PencapaianController::class, 'tambahKegiatan']);
Route::post('/pencapaian/hapus', [PencapaianController::class, 'hapus']);

