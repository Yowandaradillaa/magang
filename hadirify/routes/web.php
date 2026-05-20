<?php

use Illuminate\Support\Facades\Route;

// Import Controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;

// Import Controller Admin
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminMapelController;

/*
|--------------------------------------------------------------------------
| rute untuk Guest (Belum Login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/siswa/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/siswa/login', function () { return view('auth.login'); })->name('login');
    Route::get('/guru/login', function () { return view('auth.login'); });
    Route::get('/admin/login', function () { return view('auth.login'); });

    // Proses kirim data login
    Route::post('/login-proses', [AuthController::class, 'login'])->name('login.proses');
});

/*
|--------------------------------------------------------------------------
| rute untuk User yang Sudah Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pengalih dashboard otomatis setelah login
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect('/admin/dashboard');
        if ($role === 'guru') return redirect('/guru/dashboard');
        return redirect('/siswa/dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Akses list absen (Bisa dibuka Guru & Admin)
    Route::get('/absensi-list/{jadwal}', [AbsensiController::class, 'getAttendanceList'])->name('absensi.list');
});

/*
|--------------------------------------------------------------------------
| Rute ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    
    // Rute punyamu (Kelola Akun & Kelas)
    Route::get('/admin/akun', [AdminUserController::class, 'index'])->name('admin.akun');
    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas');
    
    // Tambahan rute proses Admin agar fitur kelola akun jalan
    Route::post('/admin/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset');
    Route::put('/admin/koreksi-absen/{id}', [AdminJadwalController::class, 'koreksiAbsen'])->name('admin.koreksi.update');

    // View rute punyamu yang lain
    Route::get('/admin/koreksi', function () { return view('admin.koreksi'); })->name('admin.koreksi');
    Route::get('/admin/laporan', function () { return view('admin.laporan'); })->name('admin.laporan');
});

/*
|--------------------------------------------------------------------------
| Rute GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->group(function () {
    // Rute view punyamu
    Route::get('/guru/dashboard', [GuruController::class, 'dashboardStats'])->name('guru.dashboard');
    Route::get('/guru/qr', function () { return view('guru.qr'); })->name('guru.qr');
    Route::get('/guru/manual', function () { return view('guru.manual'); })->name('guru.manual');
    Route::get('/guru/izin', [IzinController::class, 'index'])->name('guru.izin');
    Route::get('/guru/rekap', function () { return view('guru.rekap'); })->name('guru.rekap');
    Route::get('/guru/pengumuman', function () { return view('guru.pengumuman'); })->name('guru.pengumuman');

    // Rute proses Guru (Logic)
    Route::get('/guru/generate-qr/{jadwal}', [QRController::class, 'generate'])->name('guru.generate-qr');
    Route::post('/guru/absensi-manual', [GuruController::class, 'storeManual'])->name('guru.absensi-manual');
    Route::post('/guru/izin/{id}/proses', [IzinController::class, 'proses'])->name('guru.izin.proses');
    Route::post('/guru/kirim-pengumuman', [GuruController::class, 'kirimPengumuman'])->name('guru.pengumuman.send');
});

/*
|--------------------------------------------------------------------------
| Rute SISWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])->group(function () {
    // Rute view punyamu
    Route::get('/siswa/dashboard', [SiswaController::class, 'rekap'])->name('siswa.dashboard');
    Route::get('/siswa/scan-qr', function () { return view('siswa.scan-qr'); })->name('siswa.scan-qr');
    Route::get('/siswa/rekap', [SiswaController::class, 'rekap'])->name('siswa.rekap');
    Route::get('/siswa/izin', function () { return view('siswa.izin'); })->name('siswa.izin');
    Route::get('/siswa/notifikasi', [SiswaController::class, 'pengumuman'])->name('siswa.notifikasi');

    // Rute proses Siswa (Logic)
    Route::post('/siswa/scan-proses', [AbsensiController::class, 'scanQR'])->name('siswa.scan.proses');
    Route::post('/siswa/izin/ajukan', [IzinController::class, 'ajukan'])->name('siswa.izin.ajukan');
});

require __DIR__.'/auth.php';