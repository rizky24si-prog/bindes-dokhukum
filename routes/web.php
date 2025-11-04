<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;




Route::get('/', function () {
    return redirect()->route('login.index');
});

Route::get('dashboard', [DashboardController::class, 'index'])-> name('dashboard');

Route::resource('warga', WargaController::class);
Route::resource('jenis-dokumen', JenisDokumenController::class);
Route::resource('user', UserController::class);
Route::resource('login', AuthController::class);
Route::resource('register', RegisterController::class);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
