<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode as QRCodeModel;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function scanQR(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $qr = QRCodeModel::where('kode_qr', $request->kode_qr)
                    ->where('status', 'aktif')
                    ->where('waktu_expired', '>', now())
                    ->first();

        if (!$qr) {
            return response()->json(['message' => 'QR Code tidak valid atau sudah expired!'], 404);
        }

        $sudahAbsen = Absensi::where('siswa_id', Auth::id())
                            ->where('jadwal_id', $qr->jadwal_id)
                            ->where('tanggal', now()->toDateString())
                            ->first();

        if ($sudahAbsen) {
            return response()->json(['message' => 'Kamu sudah absen di mata pelajaran ini!'], 422);
        }

        $latSekolah = -7.801533; 
        $longSekolah = 110.352726;
        $jarak = $this->hitungJarak($request->latitude, $request->longitude, $latSekolah, $longSekolah);

        if ($jarak > 5000) { // 5000 mil = radius seluruh Indonesia bisa absen 😂
            return response()->json([
                'message' => 'Gagal! Kamu berada di luar radius sekolah.',
                'jarak_kamu' => round($jarak * 1000) . ' meter'
            ], 403);
        }

        // SIMPAN DATA (Pastikan isinya H dan QR sesuai migration)
        $absen = Absensi::create([
            'siswa_id'    => Auth::id(),
            'jadwal_id'   => $qr->jadwal_id,
            'tanggal'     => now()->toDateString(),
            'waktu_absen' => now(),
            'status'      => 'H',  // Hadir
            'metode'      => 'QR', // Metode QR
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
        ]);

        return response()->json([
            'message' => 'Absensi berhasil! Selamat belajar.',
            'data' => $absen
        ]);
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }

    public function getAttendanceList($jadwalId)
    {
        $list = Absensi::where('jadwal_id', $jadwalId)
                       ->where('tanggal', now()->toDateString())
                       ->with('user') 
                       ->get();

        return response()->json($list);
    }
}