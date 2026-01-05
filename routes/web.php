<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DokumenHukumController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RiwayatPerubahanController;
use App\Http\Controllers\LampiranDokumenController;








Route::get('/', function () {
    return redirect()->route('login.index');
});

Route::get('dashboard', [DashboardController::class, 'index'])-> name('dashboard');

Route::resource('warga', WargaController::class);
Route::resource('jenis-dokumen', JenisDokumenController::class);
Route::resource('user', UserController::class);
Route::resource('login', AuthController::class);
Route::resource('register', RegisterController::class);
Route::resource('dokumen', DokumenHukumController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('riwayat-perubahan', RiwayatPerubahanController::class);
Route::resource('lampiran-dokumen', LampiranDokumenController::class);
Route::get('lampiran-dokumen/{id}/download', [LampiranDokumenController::class, 'download'])->name('lampiran-dokumen.download');
Route::get('lampiran-dokumen/{id}/preview', [LampiranDokumenController::class, 'preview'])->name('lampiran-dokumen.preview');


Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
