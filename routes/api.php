<?php

use App\Http\Controllers\Api\KegiatanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PencapaianController;
use App\Http\Controllers\Api\TemanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::get('/users', [TemanController::class, 'listUsers']);

Route::post('/users', [TemanController::class, 'tambahUser']);

Route::get('/teman/user/{userId}', [TemanController::class, 'show']);

Route::apiResource('jadwal', JadwalController::class);

Route::apiResource('kegiatan', KegiatanController::class);

Route::apiResource('pencapaian', PencapaianController::class);

Route::apiResource('teman', TemanController::class);