<?php

namespace App\Http\Controllers; 

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    /**
     * Dashboard Utama Guru: Menampilkan statistik dan jadwal mengajar hari ini
     */
    public function dashboard()
    {
        $guruId = auth()->id();
        $today = now()->toDateString();
        
        $hariIni = now()->locale('id')->dayName; 

        // 1. Ambil jadwal mengajar guru khusus hari ini
        $jadwalHariIni = Jadwal::with(['kelas', 'mapel'])
                            ->where('id_guru', $guruId)
                            ->where('hari', $hariIni)
                            ->get();

        // 2. Ambil data absensi hari ini yang terkait dengan jadwal guru ini (atau fallback hari ini)
        $absensiHariIni = Absensi::with(['siswa.kelas', 'jadwal.mapel', 'jadwal.kelas'])
                            ->where('tanggal', $today)
                            ->whereHas('jadwal', function($q) use ($guruId) {
                                $q->where('id_guru', $guruId);
                            })
                            ->latest('waktu_absen')
                            ->get();

        if ($absensiHariIni->isEmpty()) {
            $absensiHariIni = Absensi::with(['siswa.kelas', 'jadwal.mapel', 'jadwal.kelas'])
                                ->where('tanggal', $today)
                                ->latest('waktu_absen')
                                ->get();
        }

        // 3. Hitung statistik kehadiran hari ini
        $stats = [
            'hadir' => $absensiHariIni->where('status', 'H')->count(),
            'izin'  => $absensiHariIni->where('status', 'I')->count(),
            'sakit' => $absensiHariIni->where('status', 'S')->count(),
            'alpa'  => $absensiHariIni->where('status', 'A')->count(),
        ];

        // 4. Struktur data siswa per kategori untuk modal interaktif klik statistik
        $formatSiswa = function($collection, $defaultStatus) {
            return $collection->map(function($a) use ($defaultStatus) {
                return [
                    'nama'   => $a->siswa->name ?? 'Siswa',
                    'nisn'   => $a->siswa->nisn ?? '-',
                    'kelas'  => $a->siswa->kelas->nama_kelas ?? ($a->jadwal->kelas->nama_kelas ?? '-'),
                    'mapel'  => $a->jadwal->mapel->nama_mapel ?? 'Mata Pelajaran',
                    'jam'    => $a->waktu_absen ? \Carbon\Carbon::parse($a->waktu_absen)->format('H:i') . ' WIB' : '-',
                    'metode' => $a->metode ?? 'QR',
                    'status' => $defaultStatus
                ];
            })->values();
        };

        $detailSiswa = [
            'hadir' => $formatSiswa($absensiHariIni->where('status', 'H'), 'Hadir'),
            'izin'  => $formatSiswa($absensiHariIni->where('status', 'I'), 'Izin'),
            'sakit' => $formatSiswa($absensiHariIni->where('status', 'S'), 'Sakit'),
            'alpa'  => $formatSiswa($absensiHariIni->where('status', 'A'), 'Alpa'),
        ];

        return view('guru.dashboard', compact('jadwalHariIni', 'stats', 'detailSiswa'));
    }

    /**
     * Menampilkan daftar jadwal mengajar guru hari ini.
     */
    public function indexJadwal()
    {
        $hariIni = now()->locale('id')->dayName; 
        $jadwals = Jadwal::with(['mapel', 'kelas'])
                         ->where('id_guru', Auth::id())
                         ->where('hari', $hariIni)
                         ->get();

        return view('guru.jadwal-hari-ini', compact('jadwals'));
    }

    /**
     * Menampilkan halaman daftar siswa untuk absen manual berdasarkan jadwal.
     */
    public function manual(Request $request) 
    {
        $jadwals = Jadwal::with(['kelas', 'mapel'])->where('id_guru', auth()->id())->get();
        
        $selectedJadwalId = $request->input('jadwal_id');
        $siswa = collect([]); 

        if ($selectedJadwalId) {
            $selectedJadwal = Jadwal::with('kelas')->find($selectedJadwalId);
            if ($selectedJadwal) {
                $siswa = User::where('role', 'siswa')
                             ->orderBy('name', 'asc')
                             ->get();
            }
        }
        
        return view('guru.manual', compact('jadwals', 'siswa', 'selectedJadwalId'));
    }

    /**
     * Simpan Absensi Manual (Dari Form Blade)
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'absensi_data' => 'required|array', 
        ]);

        foreach ($request->absensi_data as $siswaId => $status) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'jadwal_id' => $request->jadwal_id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'status' => $status, 
                    'metode' => 'Manual',
                    'waktu_absen' => now(),
                ]
            );
        }

        return redirect()->route('guru.dashboard')->with('success', 'Absensi manual berhasil disimpan!');
    }

    public function indexManual()
    {
        $jadwals = Jadwal::with(['mapel', 'kelas'])
                    ->where('id_guru', Auth::id())
                    ->get();

        $siswa = collect([]); 
        
        return view('guru.manual', compact('jadwals', 'siswa'));
    }

    /**
     * Fitur Tutup Absensi (Logika Auto-Alpa)
     */
    public function tutupAbsensi($jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        $today = now()->toDateString();

        $semuaSiswa = User::where('id_kelas', $jadwal->id_kelas)
                          ->where('role', 'siswa')
                          ->get();

        $countAlpa = 0;

        foreach ($semuaSiswa as $siswa) {
            $exists = Absensi::where('siswa_id', $siswa->id)
                           ->where('jadwal_id', $jadwalId)
                           ->where('tanggal', $today)
                           ->exists();

            if (!$exists) {
                Absensi::create([
                    'siswa_id'    => $siswa->id,
                    'jadwal_id'   => $jadwalId,
                    'tanggal'     => $today,
                    'waktu_absen' => now(),
                    'status'      => 'A', 
                    'metode'      => 'Manual',
                ]);
                $countAlpa++;
            }
        }

        return redirect()->route('guru.dashboard')->with('success', "Absensi ditutup. $countAlpa siswa otomatis ditandai Alpa.");
    }

    /**
     * Simpan Pengumuman (Dari Form Blade)
     */
    public function kirimPengumuman(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'judul'    => [
                'required',
                'string',
                'max:200',
                Rule::unique('pengumumen', 'judul')
                    ->where('kelas_id', $request->input('kelas_id'))
                    ->where('isi', $request->input('isi')),
            ],
            'isi'      => 'required|string|max:10000',
        ], [
            'kelas_id.required' => 'Silakan pilih kelas penerima pengumuman.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan. Silakan pilih kelas yang tersedia.',
            'judul.required' => 'Silakan isi subjek atau topik pengumuman.',
            'judul.max' => 'Subjek pengumuman tidak boleh lebih dari 200 karakter.',
            'judul.unique' => 'Pengumuman dengan subjek dan isi yang sama sudah pernah dikirim ke kelas ini.',
            'isi.required' => 'Silakan isi rincian pesan pengumuman.',
            'isi.max' => 'Rincian pengumuman tidak boleh lebih dari 10.000 karakter.',
        ], [
            'kelas_id' => 'kelas penerima',
            'judul' => 'subjek pengumuman',
            'isi' => 'rincian pesan',
        ]);

        Pengumuman::create([
            'guru_id'  => Auth::id(),
            'kelas_id' => $validated['kelas_id'],
            'judul'    => $validated['judul'],
            'isi'      => $validated['isi'],
            'tanggal'  => now(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim ke kelas!');
    }

    /**
     * Tampilan Rekap Presensi Guru secara dinamis.
     */
    public function rekap(Request $request)
    {
        $kelas = \App\Models\Kelas::all();
        $kelasId = $request->input('kelas_id');
        $bulanInput = $request->input('bulan');

        $rekaps = [];

        if ($kelasId) {
            $siswas = User::where('id_kelas', $kelasId)->where('role', 'siswa')->orderBy('name', 'asc')->get();
            $bulan = $bulanInput ? intval($bulanInput) : now()->month;
            $tahun = now()->year;

            foreach ($siswas as $siswa) {
                $hadir = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();
                $sakit = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'S')->count();
                $izin = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'I')->count();
                $alpa = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();

                $rekapObj = new \stdClass();
                $rekapObj->siswa = $siswa;
                $rekapObj->hadir = $hadir;
                $rekapObj->sakit = $sakit;
                $rekapObj->izin = $izin;
                $rekapObj->alpa = $alpa;

                $rekaps[] = $rekapObj;
            }
        }

        return view('guru.rekap', compact('kelas', 'rekaps', 'kelasId', 'bulanInput'));
    }

    /**
     * Tampilan Halaman Pengumuman Guru.
     */
    public function showPengumuman()
    {
        $kelas = \App\Models\Kelas::all();
        $pengumumans = Pengumuman::with('kelas')
            ->where('guru_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.pengumuman', compact('kelas', 'pengumumans'));
    }

    // 1. Method untuk Cetak / View PDF
    public function cetakPdf(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $bulanInput = $request->input('bulan', now()->month);
        
        $kelasDipilih = \App\Models\Kelas::find($kelasId);
        $rekaps = [];

        if ($kelasId) {
            $siswas = User::where('id_kelas', $kelasId)->where('role', 'siswa')->orderBy('name', 'asc')->get();
            $bulan = intval($bulanInput);
            $tahun = now()->year;

            foreach ($siswas as $siswa) {
                $hadir = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();
                $sakit = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'S')->count();
                $izin = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'I')->count();
                $alpa = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();

                $rekapObj = new \stdClass();
                $rekapObj->siswa = $siswa;
                $rekapObj->hadir = $hadir;
                $rekapObj->sakit = $sakit;
                $rekapObj->izin = $izin;
                $rekapObj->alpa = $alpa;

                $rekaps[] = $rekapObj;
            }
        }

        return view('guru.cetak-pdf', compact('kelasDipilih', 'rekaps', 'bulanInput'));
    }

    // 2. Method untuk Download Excel (.xls / CSV)
    public function exportExcel(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $bulanInput = $request->input('bulan', now()->month);
        
        $kelasDipilih = \App\Models\Kelas::find($kelasId);
        $namaKelas = $kelasDipilih ? $kelasDipilih->nama_kelas : 'Semua-Kelas';
        $filename = "Rekap-Absensi-{$namaKelas}-Bulan-{$bulanInput}.xls";

        $siswas = User::where('id_kelas', $kelasId)->where('role', 'siswa')->orderBy('name', 'asc')->get();
        $bulan = intval($bulanInput);
        $tahun = now()->year;

        $rekaps = [];
        foreach ($siswas as $siswa) {
            $hadir = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();
            $sakit = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'S')->count();
            $izin = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'I')->count();
            $alpa = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();

            $rekaps[] = [
                'nama' => $siswa->name,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa
            ];
        }

        $headers = [
            "Content-type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($rekaps, $namaKelas, $bulanInput) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ["Laporan Rekapitulasi Kehadiran Siswa"]);
            fputcsv($file, ["Kelas: $namaKelas", "Bulan: $bulanInput"]);
            fputcsv($file, []); 
            fputcsv($file, ['No', 'Nama Siswa', 'Hadir (H)', 'Sakit (S)', 'Izin (I)', 'Alpa (A)']);
            
            foreach ($rekaps as $index => $r) {
                fputcsv($file, [$index + 1, $r['nama'], $r['hadir'], $r['sakit'], $r['izin'], $r['alpa']]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}