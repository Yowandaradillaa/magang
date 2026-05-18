<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard default (bisa dihapus jika tidak pakai view('dashboard') bawaan Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Scan QR diletakkan di sini agar terlindungi auth
    Route::post('/scan-qr', [AbsensiController::class, 'scanQR']);
});

require __DIR__.'/auth.php';

// --- ROUTE DASHBOARD BERDASARKAN ROLE ---

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'DASHBOARD ADMIN';
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', function () {
        return 'DASHBOARD GURU';
    })->name('guru.dashboard');
});




Route::middleware(['auth', 'role:siswa'])->group(function () {
    
    // 1. Menu Dashboard Siswa
    Route::get('/siswa/dashboard', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');

    // 2. Menu Scan QR
    Route::get('/siswa/scan-qr', function () {
        return view('siswa.scan-qr');
    })->name('siswa.scan-qr');

    // 3. Menu Rekap Kehadiran
    Route::get('/siswa/rekap', function () {
        return view('siswa.rekap');
    })->name('siswa.rekap');

    // 4. Menu Ajukan Izin
    Route::get('/siswa/izin', function () {
        return view('siswa.izin');
    })->name('siswa.izin');

    // 5. Menu Notifikasi
    Route::get('/siswa/notifikasi', function () {
        return view('siswa.notifikasi');
    })->name('siswa.notifikasi');

});