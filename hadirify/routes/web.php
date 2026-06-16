<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;

// Import Controller Admin
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminMapelController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/siswa/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/siswa/login', function () { return view('auth.login'); })->name('login');
    Route::get('/guru/login', function () { return view('auth.login'); });
    Route::get('/admin/login', function () { return view('auth.login'); });
    Route::post('/login-proses', [AuthController::class, 'login'])->name('login.proses');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect('/admin/dashboard');
        if ($role === 'guru') return redirect('/guru/dashboard');
        return redirect('/siswa/dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('profile.password.update');

    // Akses list absen (Guru & Admin)
    Route::get('/absensi-list/{jadwal}', [AbsensiController::class, 'getAttendanceList'])->name('absensi.list');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminUserController::class, 'dashboardStats'])->name('admin.dashboard');

    // Manajemen Akun
    Route::get('/admin/akun', [AdminUserController::class, 'index'])->name('admin.akun.index');
    Route::post('/admin/akun', [AdminUserController::class, 'store'])->name('admin.akun.store');
    Route::put('/admin/akun/{id}', [AdminUserController::class, 'update'])->name('admin.akun.update');
    Route::delete('/admin/akun/{id}', [AdminUserController::class, 'destroy'])->name('admin.akun.destroy');
    Route::post('/admin/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset');

    // Manajemen Kelas
    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas.index');
    Route::post('/admin/kelas', [AdminKelasController::class, 'store'])->name('admin.kelas.store');
    Route::put('/admin/kelas/{id}', [AdminKelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{id}', [AdminKelasController::class, 'destroy'])->name('admin.kelas.destroy');

    // Lain-lain
    Route::get('/admin/mapel', [AdminMapelController::class, 'index'])->name('admin.mapel');
    Route::get('/admin/jadwal', [AdminJadwalController::class, 'index'])->name('admin.jadwal');
    Route::get('/admin/koreksi', [AdminUserController::class, 'koreksiAbsenList'])->name('admin.koreksi');
    Route::put('/admin/koreksi-absen/{id}', [AdminJadwalController::class, 'koreksiAbsen'])->name('admin.koreksi.update');
    Route::get('/admin/laporan', [AdminUserController::class, 'laporan'])->name('admin.laporan');
});

/*
|--------------------------------------------------------------------------
| GURU ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'dashboardStats'])->name('guru.dashboard');
    Route::get('/guru/qr', [QRController::class, 'index'])->name('guru.qr');
    Route::get('/guru/generate-qr/{jadwalId}', [QRController::class, 'generate'])->name('guru.generate-qr');
    Route::get('/guru/manual', [GuruController::class, 'indexManual'])->name('guru.manual');
    Route::get('/guru/izin', [IzinController::class, 'index'])->name('guru.izin');
    Route::get('/guru/rekap', [GuruController::class, 'rekap'])->name('guru.rekap');
    Route::get('/guru/pengumuman', [GuruController::class, 'showPengumuman'])->name('guru.pengumuman');
    Route::post('/guru/tutup-absensi/{jadwalId}', [GuruController::class, 'tutupAbsensi'])->name('guru.tutup-absensi');
    Route::get('/guru/rekap-kelas', [LaporanController::class, 'index'])->name('guru.rekap.index');

    
    Route::post('/guru/stop-qr/{id}', [QRController::class, 'stopSession'])->name('guru.stop-qr');
    Route::post('/guru/absensi-manual', [GuruController::class, 'storeManual'])->name('guru.absensi-manual');
    Route::post('/guru/izin/{id}/proses', [IzinController::class, 'proses'])->name('guru.izin.proses');
    Route::post('/guru/kirim-pengumuman', [GuruController::class, 'kirimPengumuman'])->name('guru.pengumuman.send');
});

/*
|--------------------------------------------------------------------------
| SISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
   
    // Ganti yang lama (yang return view direct) menjadi ini:
Route::get('/siswa/scan-qr', [SiswaController::class, 'scanQR'])->name('siswa.scan-qr');
    Route::get('/siswa/rekap', [SiswaController::class, 'rekap'])->name('siswa.rekap');
    Route::get('/siswa/notifikasi', [SiswaController::class, 'pengumuman'])->name('siswa.notifikasi');
    Route::get('/siswa/izin', function () { return view('siswa.izin'); })->name('siswa.izin');
    Route::post('/siswa/izin/ajukan', [IzinController::class, 'ajukan'])->name('siswa.izin.ajukan');
    Route::post('/siswa/scan-proses', [AbsensiController::class, 'scanQR'])->name('siswa.scan.proses');
});

require __DIR__.'/auth.php';