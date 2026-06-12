<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Absensi; // Tambahkan import Model Absensi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * BARU: Menampilkan Dashboard Statistik Admin (Sesuai Flowchart)
     */
    public function dashboardStats()
    {
        $today = now()->toDateString();
        
        $stats = [
            'total_siswa'    => User::where('role', 'siswa')->count(),
            'total_guru'     => User::where('role', 'guru')->count(),
            'hadir_hari_ini' => Absensi::where('tanggal', $today)->where('status', 'H')->count(),
            'izin_hari_ini'  => Absensi::where('tanggal', $today)->where('status', 'I')->count(),
            'sakit_hari_ini' => Absensi::where('tanggal', $today)->where('status', 'S')->count(),
            'alpa_hari_ini'  => Absensi::where('tanggal', $today)->where('status', 'A')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Tampilkan semua user ke halaman Blade Kelola Akun
     */
    public function index()
    {
        $users = User::with('kelas')->get();
        $kelas = Kelas::all(); // Untuk dropdown pilih kelas
        
        return view('admin.akun', compact('users', 'kelas'));
    }

    /**
     * Simpan User Baru dari Form (Password otomatis NISN/NUPTK)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|in:admin,guru,siswa',
            'email' => 'nullable|email|unique:users,email',
            'nisn'  => 'nullable|string|unique:users,nisn|max:10',
            'nuptk' => 'nullable|string|unique:users,nuptk|max:16',
            'id_kelas' => 'nullable|exists:kelas,id',
        ]);

        $passwordDefault = $request->role === 'siswa' ? $request->nisn : $request->nuptk;

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'nisn'     => $request->nisn,
            'nuptk'    => $request->nuptk,
            'id_kelas' => $request->id_kelas,
            'password' => Hash::make($passwordDefault ?? 'password123'),
        ]);

        return redirect()->back()->with('success', 'Akun berhasil dibuat!');
    }

    /**
     * Update Data User
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'nisn'  => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'nuptk' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'id_kelas' => 'nullable|exists:kelas,id',
        ]);

        $user->update($request->all());

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
    }

    /**
     * Hapus User
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus!');
    }

    /**
     * Reset Password ke Default (NISN/NUPTK)
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = $user->role === 'siswa' ? $user->nisn : $user->nuptk;

        if (!$newPassword) {
            return redirect()->back()->with('error', 'Gagal! User tidak memiliki NISN/NUPTK');
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return redirect()->back()->with('success', "Password {$user->name} berhasil direset!");
    }

    /**
     * Menampilkan data absensi untuk dikoreksi (pencarian)
     */
    public function koreksiAbsenList(Request $request)
    {
        $search = $request->input('search');
        $absensis = [];

        if ($search) {
            $absensis = Absensi::with(['user', 'jadwal.mapel'])
                ->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('nisn', 'like', "%{$search}%");
                })
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('admin.koreksi', compact('absensis', 'search'));
    }

    /**
     * Menampilkan laporan kehadiran seluruh sekolah/kelas secara dinamis
     */
    public function laporan(Request $request)
    {
        $kelas = Kelas::all();
        $kelasId = $request->input('kelas_id');
        $bulanInput = $request->input('bulan');

        $bulan = $bulanInput ? intval($bulanInput) : now()->month;
        $tahun = now()->year;

        // Query Stats Kehadiran Sekolah
        $totalHadir = Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();
        $totalIzinSakit = Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereIn('status', ['I', 'S'])->count();
        $totalAlpa = Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();
        
        $totalSemua = $totalHadir + $totalIzinSakit + $totalAlpa;
        $rataKehadiran = $totalSemua > 0 ? round(($totalHadir / $totalSemua) * 100) : 0;

        $stats = [
            'rata_kehadiran' => $rataKehadiran,
            'total_hadir' => $totalHadir,
            'total_izin_sakit' => $totalIzinSakit,
            'total_alpa' => $totalAlpa,
        ];

        // Hitung rekap per kelas
        $laporans = [];
        foreach ($kelas as $k) {
            $hadir = Absensi::whereHas('user', function($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();

            $sakit = Absensi::whereHas('user', function($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'S')->count();

            $izin = Absensi::whereHas('user', function($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'I')->count();

            $alpa = Absensi::whereHas('user', function($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();

            $laporanObj = new \stdClass();
            $laporanObj->kelas = $k;
            $laporanObj->hadir = $hadir;
            $laporanObj->sakit = $sakit;
            $laporanObj->izin = $izin;
            $laporanObj->alpa = $alpa;

            $laporans[] = $laporanObj;
        }

        return view('admin.laporan', compact('kelas', 'stats', 'laporans', 'kelasId', 'bulanInput'));
    }
}