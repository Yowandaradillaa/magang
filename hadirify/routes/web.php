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
    
    // TAMBAHAN: Rute Ganti Password (Universal)
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('profile.password.update');

    // Akses list absen (Bisa dibuka Guru & Admin)
    Route::get('/absensi-list/{jadwal}', [AbsensiController::class, 'getAttendanceList'])->name('absensi.list');
});

Route::middleware('role:admin,guru')->group(function () {
        Route::get('/laporan/rekap', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-csv', [LaporanController::class, 'exportCSV'])->name('laporan.export.csv');
    });

/*
|--------------------------------------------------------------------------
| Rute ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminUserController::class, 'dashboardStats'])->name('admin.dashboard');
    Route::get('/admin/akun', [AdminUserController::class, 'index'])->name('admin.akun');
    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas');
    Route::get('/admin/mapel', [AdminMapelController::class, 'index'])->name('admin.mapel');
    Route::get('/admin/jadwal', [AdminJadwalController::class, 'index'])->name('admin.jadwal');
    Route::get('/admin/koreksi', function () { return view('admin.koreksi'); })->name('admin.koreksi');
    Route::get('/admin/laporan', function () { return view('admin.laporan'); })->name('admin.laporan');

    // Logic Admin
    Route::post('/admin/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset');
    Route::put('/admin/koreksi-absen/{id}', [AdminJadwalController::class, 'koreksiAbsen'])->name('admin.koreksi.update');
});

/*
|--------------------------------------------------------------------------
| Rute GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'dashboardStats'])->name('guru.dashboard');
    Route::get('/guru/qr', function () { return view('guru.qr'); })->name('guru.qr');
    Route::get('/guru/manual', function () { return view('guru.manual'); })->name('guru.manual');
    Route::get('/guru/izin', [IzinController::class, 'index'])->name('guru.izin');
    Route::get('/guru/rekap', function () { return view('guru.rekap'); })->name('guru.rekap');
    Route::get('/guru/pengumuman', function () { return view('guru.pengumuman'); })->name('guru.pengumuman');
    Route::post('/guru/tutup-absensi/{jadwalId}', [GuruController::class, 'tutupAbsensi'])->name('guru.tutup-absensi');
    Route::get('/guru/rekap-kelas', [LaporanController::class, 'index'])->name('guru.rekap.index');

    // Logic Guru
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
    Route::get('/siswa/dashboard', [SiswaController::class, 'rekap'])->name('siswa.dashboard');
    Route::get('/siswa/scan-qr', function () { return view('siswa.scan-qr'); })->name('siswa.scan-qr');
    Route::get('/siswa/rekap', [SiswaController::class, 'rekap'])->name('siswa.rekap');
    Route::get('/siswa/notifikasi', [SiswaController::class, 'pengumuman'])->name('siswa.notifikasi');
    
    // Tampilan Form Izin
    Route::get('/siswa/izin', function () { return view('siswa.izin'); })->name('siswa.izin');
    // Proses Logic Simpan Izin
    Route::post('/siswa/izin/ajukan', [IzinController::class, 'ajukan'])->name('siswa.izin.ajukan');

    Route::post('/siswa/scan-proses', [AbsensiController::class, 'scanQR'])->name('siswa.scan.proses');
});

require __DIR__.'/auth.php';