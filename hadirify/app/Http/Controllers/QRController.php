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

        $activeQr = QRCode::where('guru_id', Auth::id())
                    ->where('status', 'aktif')
                    ->where('waktu_expired', '>', now())
                    ->with('jadwal.mapel', 'jadwal.kelas')
                    ->latest()
                    ->first();

        if ($activeQr && $activeQr->jadwal) {
            $qrImage = base64_encode(
                QrCodeFacade::format('svg')
                    ->size(450)
                    ->margin(2)
                    ->errorCorrection('M')
                    ->generate($activeQr->kode_qr)
            );

            return view('guru.qr', [
                'jadwals' => $jadwals,
                'qrImage' => $qrImage,
                'qrToken' => $activeQr->kode_qr,
                'jadwalAktif' => $activeQr->jadwal,
                'qrId' => $activeQr->id
            ]);
        }
        
        return view('guru.qr', compact('jadwals'));
    }

    /**
     * Membuat QR Code baru
     */
    public function generate($jadwalId)
    {
        $jadwalAktif = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);
        
        // Tutup semua sesi aktif sebelumnya milik guru ini agar tidak menumpuk
        QRCode::where('guru_id', Auth::id())
            ->where('status', 'aktif')
            ->update([
                'status' => 'expired',
                'waktu_expired' => now()
            ]);

        $token = Str::random(32);
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

        // Error correction 'M' (15%) + margin(2) membuat grid QR jauh lebih renggang, besar, dan mudah dibaca kamera
        $qrImage = base64_encode(
            QrCodeFacade::format('svg')
                ->size(450)
                ->margin(2)
                ->errorCorrection('M')
                ->generate($token)
        );

        $jadwals = Jadwal::with(['kelas', 'mapel'])
                    ->where('id_guru', Auth::id())
                    ->get();

        // Kirim 'qrId' dan 'qrToken' ke view
        return view('guru.qr', [
            'qrImage' => $qrImage,
            'qrToken' => $token,
            'jadwalAktif' => $jadwalAktif,
            'jadwals' => $jadwals,
            'qrId' => $qrRecord->id
        ]);
    }

    /**
     * MENUTUP SESI QR (Fitur yang Anda tanyakan)
     * Ditaruh di dalam class QRController, setelah fungsi generate
     */
    public function stopSession($id)
    {
        // Tutup semua sesi aktif milik guru yang sedang login
        QRCode::where('guru_id', Auth::id())
            ->where('status', 'aktif')
            ->update([
                'status' => 'expired',
                'waktu_expired' => now()
            ]);

        // Pastikan record spesifik berdasarkan ID juga berstatus expired jika ada
        if ($id) {
            QRCode::where('id', $id)->update([
                'status' => 'expired',
                'waktu_expired' => now()
            ]);
        }
        
        return redirect()->route('guru.qr')->with('success', 'Sesi absensi telah berhasil ditutup secara manual.');
    }
}