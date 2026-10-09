<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PelangganController;

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
        // barang
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/tambah', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang/simpan', [BarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/edit/{id}', [BarangController::class, 'edit'])->name('barang.edit');
        Route::post('/barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::get('/barang/hapus/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

        // gudang
        Route::get('/gudang', [GudangController::class, 'index'])->name('gudang.index');
        Route::get('/gudang/tambah', [GudangController::class, 'create'])->name('gudang.create');
        Route::post('/gudang/simpan', [GudangController::class, 'store'])->name('gudang.store');
        Route::get('/gudang/edit/{id}', [GudangController::class, 'edit'])->name('gudang.edit');
        Route::post('/gudang/update/{id}', [GudangController::class, 'update'])->name('gudang.update');
        Route::get('/gudang/hapus/{id}', [GudangController::class, 'destroy'])->name('gudang.destroy');

        // pelanggan
        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/tambah', [PelangganController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan/simpan', [PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::post('/pelanggan/update/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::get('/pelanggan/hapus/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    });

    // admin & operator: transaksi (diisi di Fase 5 & 6)

});