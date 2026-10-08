<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\QRCode as QRCodeModel;
use Illuminate\Database\QueryException;

class AbsensiController extends Controller
{
    public function scanQR(Request $request)
    {
        $request->validate([
            'kode_qr' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $qr = QRCodeModel::where('kode_qr', $request->kode_qr)
                    ->where('status', 'aktif')
                    ->where('waktu_expired', '>', now())
                    ->with('jadwal')
                    ->first();

        if (!$qr) {
            return response()->json(['message' => 'QR Code tidak valid atau sudah expired!'], 404);
        }

        $siswa = $request->user();
        if (!$qr->jadwal || (int) $qr->jadwal->id_kelas !== (int) $siswa->id_kelas) {
            return response()->json(['message' => 'QR Code ini bukan untuk jadwal kelas Anda.'], 403);
        }

        $tanggal = now()->toDateString();
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
            ->where('jadwal_id', $qr->jadwal_id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($sudahAbsen) {
            return response()->json([
                'message' => 'Absensi untuk jadwal ini sudah tercatat hari ini dengan status '
                    . $this->namaStatus($sudahAbsen->status) . '.',
            ], 422);
        }

        $jarak = $this->hitungJarak(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) config('attendance.school.latitude'),
            (float) config('attendance.school.longitude')
        );
        $batasJarak = (float) config('attendance.school.radius_meters');

        if ($jarak > $batasJarak) {
            return response()->json([
                'message' => 'Absensi hanya dapat dilakukan dalam radius maksimal '
                    . $batasJarak . ' meter dari sekolah.',
                'jarak_kamu' => (int) round($jarak) . ' meter',
            ], 403);
        }

        try {
            $absen = Absensi::create([
                'siswa_id' => $siswa->id,
                'jadwal_id' => $qr->jadwal_id,
                'tanggal' => $tanggal,
                'waktu_absen' => now(),
                'status' => 'H',
                'metode' => 'QR',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        } catch (QueryException $exception) {
            if (!$this->isDuplicateAttendanceException($exception)) {
                throw $exception;
            }

            return response()->json([
                'message' => 'Absensi untuk jadwal ini sudah tercatat hari ini.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil! Selamat belajar.',
            'data' => $absen
        ]);
    }

    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;

        return 6371000 * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function namaStatus(string $status): string
    {
        return match ($status) {
            'H' => 'Hadir',
            'I' => 'Izin',
            'S' => 'Sakit',
            'A' => 'Alfa',
            default => $status,
        };
    }

    private function isDuplicateAttendanceException(QueryException $exception): bool
    {
        $sqlState = (string) $exception->getCode();
        $driverCode = $exception->errorInfo[1] ?? null;
        $message = strtolower($exception->getMessage());

        return ($sqlState === '23000' && $driverCode === 1062)
            || ($sqlState === '23505')
            || ($sqlState === '23000' && str_contains($message, 'unique constraint failed'));
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