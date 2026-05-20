<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminMapelController;

use App\Http\Controllers\AuthController;

Route::post('/login-proses', [AuthController::class, 'login'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {

    $role = auth()->user()->role;
 
    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($role === 'guru') {
        return redirect('/guru/dashboard');
    } else {
        return redirect('/siswa/dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    Route::post('/scan-qr', [AbsensiController::class, 'scanQR']);
});

require __DIR__.'/auth.php';


Route::middleware(['auth', 'role:admin'])->group(function () {
    

    Route::get('/admin/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('admin.dashboard');

    Route::get('/admin/akun', [AdminUserController::class, 'index'])->name('admin.akun');
    
    Route::get('/admin/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas');
 
    Route::get('/admin/koreksi', function () { 
        return view('admin.koreksi'); 
    })->name('admin.koreksi');
    

    Route::get('/admin/laporan', function () { 
        return view('admin.laporan'); 
    })->name('admin.laporan');
    
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', function () { return view('guru.dashboard'); })->name('guru.dashboard');
    Route::get('/guru/qr', function () { return view('guru.qr'); })->name('guru.qr');
    Route::get('/guru/manual', function () { return view('guru.manual'); })->name('guru.manual');
    Route::get('/guru/izin', function () { return view('guru.izin'); })->name('guru.izin');
    Route::get('/guru/rekap', function () { return view('guru.rekap'); })->name('guru.rekap');
    Route::get('/guru/pengumuman', function () { return view('guru.pengumuman'); })->name('guru.pengumuman');
});


Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', function () { return view('siswa.dashboard'); })->name('siswa.dashboard');
    Route::get('/siswa/scan-qr', function () { return view('siswa.scan-qr'); })->name('siswa.scan-qr');
    Route::get('/siswa/rekap', function () { return view('siswa.rekap'); })->name('siswa.rekap');
    Route::get('/siswa/izin', function () { return view('siswa.izin'); })->name('siswa.izin');
    Route::get('/siswa/notifikasi', function () { return view('siswa.notifikasi'); })->name('siswa.notifikasi');
});


Route::middleware('guest')->group(function () {
    Route::get('/siswa/login', function () { return view('auth.login'); });
    Route::get('/guru/login', function () { return view('auth.login'); });
    Route::get('/admin/login', function () { return view('auth.login'); });
});


Route::get('/login', function () {
    return redirect('/siswa/login');
})->name('login');

Route::get('/', function () {
    return redirect('/siswa/login');
});