<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PencapaianController;
use App\Http\Controllers\JadwalController;

Route::get('/home', function () {
    return view('welcome');
});

// Login
Route::get('/', [LoginController::class, 'landing'])->name('landing');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Kegiatan
Route::resource('/kegiatan', KegiatanController::class);

// Pencapaian
Route::get('/pencapaian', [PencapaianController::class, 'index']);
Route::post('/pencapaian/tambah', [PencapaianController::class, 'tambah']);
Route::post('/pencapaian/kurang', [PencapaianController::class, 'kurang']);
Route::post('/pencapaian/reset', [PencapaianController::class, 'reset']);
Route::post('/pencapaian/tambah-kegiatan', [PencapaianController::class, 'tambahKegiatan']);
Route::post('/pencapaian/hapus', [PencapaianController::class, 'hapus']);
Route::get('/pencapaian/tambah-counter/{index}', [PencapaianController::class, 'formTambahCounter']);
Route::post('/pencapaian/tambah-counter/{index}', [PencapaianController::class, 'simpanTambahCounter']);


// Jadwal
Route::prefix('jadwal')->group(function () {
    Route::get('/', [JadwalController::class, 'index'])->name('jadwal.index'); // ✅ ditambahkan
    Route::get('/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy'); // ✅ Hanya satu versi
});
