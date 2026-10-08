<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminJadwalController extends Controller
{
    public function index() {
    $jadwals = Jadwal::with(['kelas', 'mapel', 'guru'])->get();
    $kelas = Kelas::all();
    $gurus = User::where('role', 'guru')->get();
    $mapels = MataPelajaran::all();

    return view('admin.kelas', compact('jadwals', 'kelas', 'gurus', 'mapels'));
}

    public function store(Request $request) {
        $validated = $request->validateWithBag('jadwal', [
            'id_kelas' => ['required', 'exists:kelas,id'],
            'id_mapel' => ['required', 'exists:mata_pelajarans,id'],
            'id_guru' => ['required', Rule::exists('users', 'id')->where('role', 'guru')],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'id_kelas.required' => 'Silakan pilih kelas untuk jadwal ini.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak ditemukan. Silakan pilih kelas yang tersedia.',
            'id_mapel.required' => 'Silakan pilih mata pelajaran untuk jadwal ini.',
            'id_mapel.exists' => 'Mata pelajaran yang dipilih tidak ditemukan. Silakan pilih mata pelajaran yang tersedia.',
            'id_guru.required' => 'Silakan pilih guru pengampu untuk jadwal ini.',
            'id_guru.exists' => 'Guru yang dipilih tidak valid. Silakan pilih guru yang tersedia.',
            'hari.required' => 'Silakan pilih hari pelaksanaan jadwal.',
            'hari.in' => 'Hari yang dipilih tidak valid. Pilih hari Senin sampai Sabtu.',
            'jam_mulai.required' => 'Silakan isi jam mulai pelajaran.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid. Gunakan format jam dan menit, misalnya 08:00.',
            'jam_selesai.required' => 'Silakan isi jam selesai pelajaran.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid. Gunakan format jam dan menit, misalnya 09:30.',
            'jam_selesai.after' => 'Jam selesai harus lebih akhir daripada jam mulai pada hari yang sama.',
        ], [
            'id_kelas' => 'kelas',
            'id_mapel' => 'mata pelajaran',
            'id_guru' => 'guru pengampu',
            'hari' => 'hari',
            'jam_mulai' => 'jam mulai',
            'jam_selesai' => 'jam selesai',
        ]);

        $jadwalBentrokGuru = Jadwal::where('hari', $validated['hari'])
            ->where('id_guru', $validated['id_guru'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        $jadwalBentrokKelas = Jadwal::where('hari', $validated['hari'])
            ->where('id_kelas', $validated['id_kelas'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        if ($jadwalBentrokGuru || $jadwalBentrokKelas) {
            $errors = [];

            if ($jadwalBentrokGuru) {
                $errors['id_guru'] = 'Guru tersebut sudah memiliki jadwal yang bertumpang tindih pada hari dan waktu yang dipilih.';
            }

            if ($jadwalBentrokKelas) {
                $errors['id_kelas'] = 'Kelas tersebut sudah memiliki jadwal yang bertumpang tindih pada hari dan waktu yang dipilih.';
            }

            return redirect()->back()
                ->withErrors($errors, 'jadwal')
                ->withInput();
        }

        Jadwal::create($validated);
        return redirect()->back()->with('success', 'Jadwal berhasil dibuat!');
    }

    public function destroy($id) {
    Jadwal::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
}
    // Menampilkan halaman Koreksi Absensi dan hasil pencarian
    public function koreksiIndex(Request $request) 
    {
        $search = $request->input('search');

        $absensis = Absensi::with(['siswa', 'jadwal.mapel', 'siswa.kelas'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.koreksi', compact('absensis'));
    }

    // Koreksi Absensi Siswa oleh Admin
    public function koreksiAbsen(Request $request, $idAbsensi) {
        $request->validate([
            'status' => 'required|in:H,A,S,I',
        ]);

        $absen = Absensi::findOrFail($idAbsensi);
        $absen->update([
            'status' => $request->status,
            'dikoreksi_oleh' => auth()->id() 
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil dikoreksi oleh Admin!');
    }
}