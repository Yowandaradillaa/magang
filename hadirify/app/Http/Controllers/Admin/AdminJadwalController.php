<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Imports\JadwalImport;
use App\Exports\JadwalTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
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

    /**
     * Unduh template file Excel untuk import Jadwal
     */
    public function downloadTemplate()
    {
        return Excel::download(new JadwalTemplateExport(), 'template_import_jadwal.xlsx');
    }

    /**
     * Import massal data Jadwal dari Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Silakan pilih file terlebih dahulu.',
            'file.mimes'    => 'Format file harus Excel (.xlsx / .xls) atau CSV.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new JadwalImport();
        Excel::import($import, $request->file('file'));

        $sukses = $import->getSuccessCount();
        $errors = $import->getErrors();

        if ($sukses > 0 && empty($errors)) {
            return redirect()->back()->with('success', "{$sukses} jadwal berhasil ditambahkan dari file Excel!");
        }

        if ($sukses > 0 && !empty($errors)) {
            return redirect()->back()
                ->with('success', "{$sukses} jadwal berhasil ditambahkan.")
                ->with('import_errors', $errors);
        }

        return redirect()->back()
            ->with('error', 'Tidak ada jadwal yang berhasil ditambahkan. Cek detail error di bawah.')
            ->with('import_errors', $errors);
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

    /**
     * Memperbarui data Jadwal Mengajar
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'id_kelas' => ['required', 'exists:kelas,id'],
            'id_mapel' => ['required', 'exists:mata_pelajarans,id'],
            'id_guru' => ['required', Rule::exists('users', 'id')->where('role', 'guru')],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'id_kelas.required' => 'Silakan pilih kelas untuk jadwal ini.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'id_mapel.required' => 'Silakan pilih mata pelajaran untuk jadwal ini.',
            'id_mapel.exists' => 'Mata pelajaran yang dipilih tidak ditemukan.',
            'id_guru.required' => 'Silakan pilih guru pengampu untuk jadwal ini.',
            'id_guru.exists' => 'Guru yang dipilih tidak valid.',
            'hari.required' => 'Silakan pilih hari pelaksanaan jadwal.',
            'hari.in' => 'Hari yang dipilih tidak valid. Pilih hari Senin sampai Sabtu.',
            'jam_mulai.required' => 'Silakan isi jam mulai pelajaran.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid (HH:MM).',
            'jam_selesai.required' => 'Silakan isi jam selesai pelajaran.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid (HH:MM).',
            'jam_selesai.after' => 'Jam selesai harus lebih akhir daripada jam mulai.',
        ]);

        // Cek bentrok guru (kecualikan ID jadwal yang sedang diedit)
        $jadwalBentrokGuru = Jadwal::where('hari', $validated['hari'])
            ->where('id_guru', $validated['id_guru'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->where('id', '!=', $jadwal->id)
            ->exists();

        // Cek bentrok kelas (kecualikan ID jadwal yang sedang diedit)
        $jadwalBentrokKelas = Jadwal::where('hari', $validated['hari'])
            ->where('id_kelas', $validated['id_kelas'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->where('id', '!=', $jadwal->id)
            ->exists();

        if ($jadwalBentrokGuru || $jadwalBentrokKelas) {
            $errorMessage = $jadwalBentrokGuru && $jadwalBentrokKelas
                ? 'Guru dan kelas tersebut sudah memiliki jadwal yang bertumpang tindih pada hari dan waktu yang dipilih.'
                : ($jadwalBentrokGuru
                    ? 'Guru tersebut sudah memiliki jadwal mengajar lain pada hari dan rentang waktu yang dipilih.'
                    : 'Kelas tersebut sudah memiliki sesi belajar lain pada hari dan rentang waktu yang dipilih.');

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }

        $jadwal->update($validated);

        return redirect()->back()->with('success', 'Jadwal mengajar berhasil diperbarui!');
    }

    public function destroy($id) {
        Jadwal::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
    }
    // Menampilkan halaman Koreksi Absensi dan hasil pencarian
    public function koreksiIndex(Request $request) 
    {
        $search = $request->input('search');
        $periode = $request->input('periode', 'semua');

        $query = Absensi::with(['siswa', 'jadwal.mapel', 'siswa.kelas'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            });

        if ($periode === 'hari_ini') {
            $query->whereDate('tanggal', now()->toDateString());
        } elseif ($periode === 'minggu_ini') {
            $query->whereBetween('tanggal', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
        } elseif ($periode === 'bulan_ini') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        }

        $absensis = $query->latest('tanggal')->latest('id')->paginate(10)->withQueryString();

        return view('admin.koreksi', compact('absensis', 'periode'));
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