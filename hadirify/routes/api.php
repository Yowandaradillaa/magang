<?php

use Illuminate\Support\Facades\Route;

// Import Semua Controller
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\API\IzinController;
use App\Http\Controllers\API\GuruController;
use App\Http\Controllers\API\SiswaController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminMapelController;
use App\Http\Controllers\Admin\AdminJadwalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Login (Public - Tanpa Token)
Route::post('/login', [AuthController::class, 'login']);

// 2. Proteksi Token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // --- FITUR KHUSUS ADMIN ---
    Route::middleware('role:admin')->group(function () {
        // Kelola Akun (Users)
        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::post('/admin/users', [AdminUserController::class, 'store']);
        Route::put('/admin/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);
        Route::post('/admin/users/{id}/reset-password', [AdminUserController::class, 'resetPassword']);

        // Kelola Kelas
        Route::get('/admin/kelas', [AdminKelasController::class, 'index']);
        Route::post('/admin/kelas', [AdminKelasController::class, 'store']);
        Route::put('/admin/kelas/{id}', [AdminKelasController::class, 'update']);
        Route::delete('/admin/kelas/{id}', [AdminKelasController::class, 'destroy']);

        // Kelola Mapel
        Route::get('/admin/mapel', [AdminMapelController::class, 'index']);
        Route::post('/admin/mapel', [AdminMapelController::class, 'store']);
        Route::delete('/admin/mapel/{id}', [AdminMapelController::class, 'destroy']);

        // Kelola Jadwal & Koreksi Absensi
        Route::get('/admin/jadwal', [AdminJadwalController::class, 'index']);
        Route::post('/admin/jadwal', [AdminJadwalController::class, 'store']);
        Route::put('/admin/koreksi-absen/{id}', [AdminJadwalController::class, 'koreksiAbsen']);
    });

    // --- FITUR KHUSUS GURU ---
    Route::middleware('role:guru')->group(function () {
        // Core Absensi (QR)
        Route::get('/generate-qr/{jadwal}', [QRController::class, 'generate']);
        
        // Absensi Manual (Flowchart: Input Manual H/A/S/I)
        Route::get('/guru/siswa-kelas/{jadwalId}', [GuruController::class, 'getSiswaByJadwal']);
        Route::post('/guru/absensi-manual', [GuruController::class, 'storeManual']);
        
        // Izin Siswa (Flowchart: Proses Izin)
        Route::get('/izin/list', [IzinController::class, 'index']);
        Route::post('/izin/proses/{id}', [IzinController::class, 'proses']);

        // Pengumuman (UC_Pengumuman)
        Route::post('/guru/pengumuman', [GuruController::class, 'kirimPengumuman']);
    });

    // --- FITUR KHUSUS SISWA ---
    Route::middleware('role:siswa')->group(function () {
        // Core Absensi (Scan)
        Route::post('/scan-qr', [AbsensiController::class, 'scanQR']);
        
        // Ajukan Izin (UC_Ajukan + UC_Surat)
        Route::post('/izin/ajukan', [IzinController::class, 'ajukan']);

        // Dashboard & Rekap (Flowchart: Lihat Rekap)
        Route::get('/siswa/rekap', [SiswaController::class, 'rekap']);
    });

    // --- AKSES BERSAMA (GURU & ADMIN) ---
    Route::middleware('role:guru,admin')->group(function () {
        Route::get('/absensi-list/{jadwal}', [AbsensiController::class, 'getAttendanceList']);
    });

});