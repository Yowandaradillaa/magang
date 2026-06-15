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
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis'           => 'required|in:Izin,Sakit',
            'alasan'          => 'required|string',
            'file_surat'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        $path = null;
        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat_izin', 'public');
        }

        PengajuanIzin::create([
            'siswa_id'        => Auth::id(),
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis'           => $request->jenis,
            'alasan'          => $request->alasan,
            'file_surat'      => $path,
            'status'          => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Pengajuan izin berhasil dikirim!');
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