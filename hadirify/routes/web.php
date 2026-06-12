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
use App\Http\Controllers\LaporanController;
 // <-- INI TAMBAHAN PENTING

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
    

// Tampil Halaman Akun
Route::get('/admin/akun', [AdminUserController::class, 'index'])->name('admin.akun.index');

// Proses Simpan Data Baru
Route::post('/admin/akun', [AdminUserController::class, 'store'])->name('admin.akun.store');

// Proses Hapus Data
Route::delete('/admin/akun/{id}', [AdminUserController::class, 'destroy'])->name('admin.akun.destroy');
    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas');
    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas.index');
    Route::post('/admin/kelas', [AdminKelasController::class, 'store'])->name('admin.kelas.store');
    Route::delete('/admin/kelas/{id}', [AdminKelasController::class, 'destroy'])->name('admin.kelas.destroy');

// TAMBAHKAN BARIS INI (Ini pintu untuk proses Update/Edit)
    Route::put('/admin/kelas/{id}', [AdminKelasController::class, 'update'])->name('admin.kelas.update');

    Route::get('/admin/mapel', [AdminMapelController::class, 'index'])->name('admin.mapel');
    Route::get('/admin/jadwal', [AdminJadwalController::class, 'index'])->name('admin.jadwal');
    Route::get('/admin/koreksi', [AdminUserController::class, 'koreksiAbsenList'])->name('admin.koreksi');
    Route::get('/admin/laporan', [AdminUserController::class, 'laporan'])->name('admin.laporan');
    Route::post('/admin/kelas/store', [AdminKelasController::class, 'store'])->name('admin.kelas.store');

    // Logic Admin
    Route::post('/admin/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset');
    Route::put('/admin/koreksi-absen/{id}', [AdminJadwalController::class, 'koreksiAbsen'])->name('admin.koreksi.update');
});

/*
|--------------------------------------------------------------------------
| Rute GURU (Bersihkan bagian ini di web.php Anda)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'dashboardStats'])->name('guru.dashboard');
    
    // RUTE QR YANG BENAR (Hapus yang duplikat)
    Route::get('/guru/qr', [QRController::class, 'index'])->name('guru.qr');
    Route::get('/guru/generate-qr/{jadwalId}', [QRController::class, 'generate'])->name('guru.generate-qr');

    Route::get('/guru/manual', [GuruController::class, 'indexManual'])->name('guru.manual');
    Route::get('/guru/izin', [IzinController::class, 'index'])->name('guru.izin');
    Route::get('/guru/rekap', [GuruController::class, 'rekap'])->name('guru.rekap');
    Route::get('/guru/pengumuman', [GuruController::class, 'showPengumuman'])->name('guru.pengumuman');
    Route::post('/guru/tutup-absensi/{jadwalId}', [GuruController::class, 'tutupAbsensi'])->name('guru.tutup-absensi');
    Route::get('/guru/rekap-kelas', [LaporanController::class, 'index'])->name('guru.rekap.index');

    // Logic Guru
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
    // 👇👇👇 INI YANG TADI SALAH, SUDAH AKU PERBAIKI JADI 'dashboard'
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    
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