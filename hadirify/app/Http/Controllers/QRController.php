<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode;
use SimpleSoftwareIO\QrCode\Generator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QRController extends Controller
{
    public function generate($jadwalId)
    {
        $token = Str::random(40);
        $expired = Carbon::now()->addMinutes(5);

        // REVISI: Gunakan jadwal_id dan guru_id (sesuai database kamu)
        QRCode::create([
            'jadwal_id'     => $jadwalId,
            'guru_id'       => auth()->id(),
            'kode_qr'       => $token,
            'waktu_dibuat'  => now(),
            'waktu_expired' => $expired,
            'status'        => 'aktif'
        ]);

        $qrCode = new Generator();

        $qrImage = base64_encode(
            $qrCode->format('svg')
                   ->size(300)
                   ->generate($token)
        );

        return response()->json([
            'message'  => 'QR berhasil dibuat',
            'token'    => $token,
            'expired'  => $expired,
            'qr_image' => $qrImage
        ]);
    }
}