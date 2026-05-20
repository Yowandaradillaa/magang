<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade; // Pakai Facade lebih stabil
use Illuminate\Support\Str;
use Carbon\Carbon;

class QRController extends Controller
{
    public function generate($jadwalId)
    {
        $token = Str::random(40);
        $expired = Carbon::now()->addMinutes(5);

        // 1. Simpan ke Database
        // Pastikan key (sebelah kiri) sama dengan $fillable di Model
        QRCode::create([
            'jadwal_id'     => $jadwalId,
            'guru_id'       => auth()->id(),
            'kode_qr'       => $token,
            'waktu_dibuat'  => now(),
            'waktu_expired' => $expired,
            'status'        => 'aktif'
        ]);

        // 2. Generate Gambar QR (Format SVG Base64)
        // Kita gunakan QrCodeFacade agar lebih ringkas
        $qrImage = base64_encode(
            QrCodeFacade::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($token)
        );

        // 3. Respon JSON (Untuk Postman atau AJAX)
        return response()->json([
            'message'  => 'QR berhasil dibuat dan tersimpan di database',
            'token'    => $token,
            'expired'  => $expired,
            'qr_image' => $qrImage
        ]);
    }
}