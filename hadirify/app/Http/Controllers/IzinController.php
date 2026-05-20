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
    /**
     * GURU: Menampilkan daftar pengajuan izin yang masuk (Pending)
     */
    public function index()
    {
        $izins = PengajuanIzin::with('siswa')
                ->where('status', 'Pending')
                ->orderBy('tanggal_pengajuan', 'desc')
                ->get();

        return view('guru.izin', compact('izins'));
    }

    /**
     * SISWA: Menyimpan pengajuan izin baru (Sakit/Izin)
     */
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

    /**
     * GURU: Menyetujui atau Menolak Izin
     */
    public function proses(Request $request, $id)
    {
        $request->validate([
            'status'       => 'required|in:Disetujui,Ditolak',
            'catatan_guru' => 'nullable|string'
        ]);

        $izin = PengajuanIzin::findOrFail($id);
        $izin->update([
            'status'            => $request->status,
            'id_guru_approver'  => Auth::id(),
            'catatan_guru'      => $request->catatan_guru,
        ]);

        // REVISI: Jika disetujui, buat baris absen otomatis untuk tiap tanggal yang diajukan
        if ($request->status === 'Disetujui') {
            $period = CarbonPeriod::create($izin->tanggal_mulai, $izin->tanggal_selesai);
            
            // Cari jadwal hari ini untuk siswa tsb atau jadwal default
            $jadwal = Jadwal::where('id_kelas', $izin->siswa->id_kelas)->first();

            foreach ($period as $date) {
                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $izin->siswa_id,
                        'tanggal'  => $date->toDateString(),
                        'jadwal_id' => $jadwal->id ?? 1 // Gunakan ID jadwal yang relevan
                    ],
                    [
                        'status'      => ($izin->jenis == 'Sakit') ? 'S' : 'I',
                        'metode'      => 'Manual',
                        'waktu_absen' => now(),
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Status izin berhasil diperbarui!');
    }
}