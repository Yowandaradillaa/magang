<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzin;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;

class IzinController extends Controller
{
    public function index()
    {
        // Gunakan .with('siswa.kelas') agar data kelas siswa juga ikut terbawa (N+1 protection)
        $izins = PengajuanIzin::with(['siswa.kelas'])
                    ->where('status', 'Pending')
                    ->orderBy('tanggal_pengajuan', 'desc')
                    ->get();

        $riwayat = PengajuanIzin::with(['siswa.kelas'])
                    ->where('status', '!=', 'Pending')
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return view('guru.izin', compact('izins', 'riwayat'));
    }

    public function ajukan(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai'   => ['required', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['required', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'jenis'           => ['required', 'in:Izin,Sakit'],
            'alasan'          => ['required', 'string', 'max:10000'],
            'file_surat'      => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'tanggal_mulai.required' => 'Silakan pilih tanggal mulai izin.',
            'tanggal_mulai.date_format' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Silakan pilih tanggal selesai izin.',
            'tanggal_selesai.date_format' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai izin tidak boleh lebih awal daripada tanggal mulai.',
            'jenis.required' => 'Silakan pilih jenis pengajuan izin.',
            'jenis.in' => 'Jenis pengajuan harus Izin atau Sakit.',
            'alasan.required' => 'Silakan isi alasan pengajuan izin.',
            'alasan.max' => 'Alasan izin tidak boleh lebih dari 10.000 karakter.',
            'file_surat.mimes' => 'Lampiran harus berupa file JPG, PNG, atau PDF.',
            'file_surat.max' => 'Ukuran lampiran maksimal 2 MB.',
        ]);

        $duplikat = PengajuanIzin::where('siswa_id', Auth::id())
            ->whereDate('tanggal_mulai', $validated['tanggal_mulai'])
            ->whereDate('tanggal_selesai', $validated['tanggal_selesai'])
            ->where('jenis', $validated['jenis'])
            ->where('alasan', $validated['alasan'])
            ->exists();

        if ($duplikat) {
            return redirect()->back()
                ->withErrors([
                    'tanggal_mulai' => 'Pengajuan izin dengan tanggal, jenis, dan alasan yang sama sudah pernah dikirim.',
                ])
                ->withInput();
        }

        $path = null;
        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat_izin', 'public');
        }

        PengajuanIzin::create([
            'siswa_id'        => Auth::id(),
            'tanggal_mulai'   => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jenis'           => $validated['jenis'],
            'alasan'          => $validated['alasan'],
            'file_surat'      => $path,
            'status'          => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('siswa.izin')->with('success', 'Pengajuan izin berhasil dikirim!');
    }

    public function proses(Request $request, $id)
    {
        $request->validate([
            'status'       => 'required|in:Disetujui,Ditolak',
            'catatan_guru' => 'nullable|string'
        ]);

        $izin = PengajuanIzin::with('siswa')->findOrFail($id);
        
        $izin->update([
            'status'            => $request->status,
            'id_guru_approver'  => Auth::id(),
            'catatan_guru'      => $request->catatan_guru,
        ]);

        if ($request->status === 'Disetujui') {
            // Logika Absensi Otomatis
            $period = CarbonPeriod::create($izin->tanggal_mulai, $izin->tanggal_selesai);
            
            // Ambil semua jadwal untuk kelas siswa tersebut
            $jadwals = Jadwal::where('id_kelas', $izin->siswa->id_kelas)->get();

            foreach ($period as $date) {
                // Untuk setiap hari dalam rentang izin, buat record absen untuk SEMUA jadwal di hari itu
                foreach ($jadwals as $j) {
                    Absensi::updateOrCreate(
                        [
                            'siswa_id'  => $izin->siswa_id,
                            'tanggal'   => $date->toDateString(),
                            'jadwal_id' => $j->id
                        ],
                        [
                            'status'      => ($izin->jenis == 'Sakit') ? 'S' : 'I',
                            'metode'      => 'Sistem (Izin)',
                            'waktu_absen' => now(),
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Status izin berhasil diperbarui!');
    }
}