<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode;
use App\Models\Jadwal;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QRController extends Controller
{
    /**
     * Menampilkan halaman Generate QR untuk Guru.
     */
    public function generate($jadwalId)
    {
        // 1. Pastikan jadwal ada
        $jadwal = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);

        // 2. Buat token unik dan waktu expired
        $token = Str::random(40);
        $expired = Carbon::now()->addMinutes(10); // Kita beri waktu 10 menit agar tidak terburu-buru

        // 3. Simpan ke Database
        QRCode::create([
            'jadwal_id'     => $jadwalId,
            'guru_id'       => auth()->id(),
            'kode_qr'       => $token,
            'waktu_dibuat'  => now(),
            'waktu_expired' => $expired,
            'status'        => 'aktif'
        ]);

        // 4. Generate Gambar QR (Format SVG Base64)
        $qrImage = base64_encode(
            QrCodeFacade::format('svg')
                ->size(400)
                ->errorCorrection('H')
                ->generate($token)
        );

        // 5. Lempar ke View guru/qr.blade.php
        return view('guru.qr', compact('qrImage', 'token', 'expired', 'jadwal'));
    }
}