<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode; // Pastikan model ini ada
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QRController extends Controller
{
    /**
     * Menampilkan halaman awal QR
     */
    public function index()
    {
        $jadwals = Jadwal::with(['kelas', 'mapel'])
                    ->where('id_guru', Auth::id())
                    ->get();
        
        return view('guru.qr', compact('jadwals'));
    }

    /**
     * Membuat QR Code baru
     */
    public function generate($jadwalId)
    {
        $jadwalAktif = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);
        
        $token = Str::random(40);
        $expired = Carbon::now()->addMinutes(15);

        // Simpan data QR ke database
        $qrRecord = QRCode::create([
            'jadwal_id'     => $jadwalId,
            'guru_id'       => Auth::id(),
            'kode_qr'       => $token,
            'waktu_dibuat'  => now(),
            'waktu_expired' => $expired,
            'status'        => 'aktif'
        ]);

        $qrImage = base64_encode(
            QrCodeFacade::format('svg')->size(400)->errorCorrection('H')->generate($token)
        );

        $jadwals = Jadwal::with(['kelas', 'mapel'])
                    ->where('id_guru', Auth::id())
                    ->get();

        // Kirim 'qrId' ke view agar tombol STOP tahu data mana yang mau ditutup
        return view('guru.qr', [
            'qrImage' => $qrImage,
            'jadwalAktif' => $jadwalAktif,
            'jadwals' => $jadwals,
            'qrId' => $qrRecord->id // <--- Tambahan ini penting
        ]);
    }

    /**
     * MENUTUP SESI QR (Fitur yang Anda tanyakan)
     * Ditaruh di dalam class QRController, setelah fungsi generate
     */
    public function stopSession($id)
    {
        // Cari data QR berdasarkan ID-nya
        $qr = QRCode::findOrFail($id);

        // Update status jadi expired dan waktu expired jadi sekarang
        $qr->update([
            'status' => 'expired',
            'waktu_expired' => now()
        ]);
        
        return redirect()->route('guru.qr')->with('success', 'Sesi absensi telah berhasil ditutup secara manual.');
    }
}