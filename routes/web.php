<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// halaman login (hanya untuk yang BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

// semua route di bawah ini WAJIB login
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home.dashboard');
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // khusus admin: master data & laporan (diisi di Fase 4 & 8)
    Route::middleware('role:admin')->group(function () {

    });

    // admin & operator: transaksi (diisi di Fase 5 & 6)

});