<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QRController extends Controller
{
    public function index()
    {
        $classes = Kelas::all();
        $subjects = MataPelajaran::all();
        $schedules = Jadwal::where('id_guru', Auth::id())->get();
        
        return view('guru.qr', compact('classes', 'subjects', 'schedules'));
    }

    public function generate($jadwalId)
    {
        $jadwal = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);
        
        $token = Str::random(40);
        $expired = Carbon::now()->addMinutes(10);

        QRCode::create([
            'jadwal_id'     => $jadwalId,
            'guru_id'       => auth()->id(),
            'kode_qr'       => $token,
            'waktu_dibuat'  => now(),
            'waktu_expired' => $expired,
            'status'        => 'aktif'
        ]);

        $qrImage = base64_encode(
            QrCodeFacade::format('svg')->size(400)->errorCorrection('H')->generate($token)
        );

        $classes = Kelas::all();
        $subjects = MataPelajaran::all();
        $schedules = Jadwal::where('id_guru', Auth::id())->get();

        return view('guru.qr', compact('qrImage', 'token', 'expired', 'jadwal', 'classes', 'subjects', 'schedules'));
    }
}