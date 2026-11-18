<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengajuanIzin;
use App\Models\Pengumuman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeminarDummySeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // 1. Gunakan akun Administrator lama (jangan ubah password jika sudah ada)
        $admin = User::where('email', 'admin@gmail.com')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'Admin Hadirify',
                'email' => 'admin@gmail.com',
                'nuptk' => '1234567890123456',
                'password' => $defaultPassword,
                'role' => 'admin',
            ]);
        }

        // 2. Buat 12 Guru baru dengan nama fiktif Indonesia, email @gmail.com, dan NUPTK 16 digit
        $guruSeeds = [
            ['name' => 'Ratna Sari, M.Pd.', 'email' => 'ratna.guru@gmail.com', 'nuptk' => '1984011220090101'],
            ['name' => 'Ahmad Fauzan, S.Kom.', 'email' => 'fauzan.guru@gmail.com', 'nuptk' => '1985031520100102'],
            ['name' => 'Siti Rahmawati, S.Pd.', 'email' => 'siti.guru@gmail.com', 'nuptk' => '1986052020110103'],
            ['name' => 'Budi Hartono, S.Pd.', 'email' => 'budi.guru@gmail.com', 'nuptk' => '1987071020120104'],
            ['name' => 'Hendro Nugroho, S.Ag.', 'email' => 'hendro.guru@gmail.com', 'nuptk' => '1982090420080105'],
            ['name' => 'Nurul Hidayah, Lc.', 'email' => 'nurul.guru@gmail.com', 'nuptk' => '1988111820130106'],
            ['name' => 'Rina Kusuma, M.Pd.', 'email' => 'rina.guru@gmail.com', 'nuptk' => '1989022220140107'],
            ['name' => 'Eko Prasetyo, S.Si.', 'email' => 'eko.guru@gmail.com', 'nuptk' => '1990041420150108'],
            ['name' => 'Dewi Lestari, M.Sc.', 'email' => 'dewi.guru@gmail.com', 'nuptk' => '1991062720160109'],
            ['name' => 'Arif Setiawan, S.E.', 'email' => 'arif.guru@gmail.com', 'nuptk' => '1992080920170110'],
            ['name' => 'Hendra Wijaya, S.Pd.', 'email' => 'hendra.guru@gmail.com', 'nuptk' => '1993101120180111'],
            ['name' => 'Maya Puspita, S.Or.', 'email' => 'maya.guru@gmail.com', 'nuptk' => '1994120520190112'],
        ];

        $gurus = [];
        foreach ($guruSeeds as $gSeed) {
            $gurus[] = User::firstOrCreate(
                ['email' => $gSeed['email']],
                [
                    'name' => $gSeed['name'],
                    'nuptk' => $gSeed['nuptk'],
                    'password' => $defaultPassword,
                    'role' => 'guru',
                ]
            );
        }

        // 3. Buat 17 Mata Pelajaran SMA & hubungkan ke guru pengampunya
        $mapelSpecs = [
            ['nama_mapel' => 'Pendidikan Agama Islam', 'kode_mapel' => 'PAI-SMA', 'deskripsi' => 'Pendidikan Agama Islam dan Budi Pekerti', 'guru_idx' => 4],
            ['nama_mapel' => 'Kemuhammadiyahan', 'kode_mapel' => 'KMD-SMA', 'deskripsi' => 'Pendidikan Kemuhammadiyahan dan Al-Islam', 'guru_idx' => 4],
            ['nama_mapel' => 'Bahasa Arab', 'kode_mapel' => 'ARB-SMA', 'deskripsi' => 'Bahasa Arab Dasar dan Percakapan', 'guru_idx' => 5],
            ['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BIND-SMA', 'deskripsi' => 'Bahasa dan Sastra Indonesia', 'guru_idx' => 2],
            ['nama_mapel' => 'Bahasa Inggris', 'kode_mapel' => 'BING-SMA', 'deskripsi' => 'Bahasa Inggris Komunikasi dan Literasi', 'guru_idx' => 3],
            ['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK-SMA', 'deskripsi' => 'Matematika Umum dan Peminatan', 'guru_idx' => 0],
            ['nama_mapel' => 'Pendidikan Pancasila', 'kode_mapel' => 'PPKN-SMA', 'deskripsi' => 'Pendidikan Pancasila dan Kewarganegaraan', 'guru_idx' => 6],
            ['nama_mapel' => 'Sejarah', 'kode_mapel' => 'SEJ-SMA', 'deskripsi' => 'Sejarah Indonesia dan Dunia', 'guru_idx' => 6],
            ['nama_mapel' => 'Informatika', 'kode_mapel' => 'INF-SMA', 'deskripsi' => 'Algoritma, Pemrograman, dan Literasi Digital', 'guru_idx' => 1],
            ['nama_mapel' => 'PJOK', 'kode_mapel' => 'PJOK-SMA', 'deskripsi' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'guru_idx' => 11],
            ['nama_mapel' => 'Seni Budaya', 'kode_mapel' => 'SBD-SMA', 'deskripsi' => 'Seni Rupa, Musik, dan Budaya Nusantara', 'guru_idx' => 11],
            ['nama_mapel' => 'Fisika', 'kode_mapel' => 'FIS-SMA', 'deskripsi' => 'Ilmu Pengetahuan Alam Fisika', 'guru_idx' => 7],
            ['nama_mapel' => 'Kimia', 'kode_mapel' => 'KIM-SMA', 'deskripsi' => 'Kimia Dasar dan Terapan', 'guru_idx' => 7],
            ['nama_mapel' => 'Biologi', 'kode_mapel' => 'BIO-SMA', 'deskripsi' => 'Ilmu Pengetahuan Alam Biologi', 'guru_idx' => 8],
            ['nama_mapel' => 'Ekonomi', 'kode_mapel' => 'EKO-SMA', 'deskripsi' => 'Ilmu Ekonomi dan Akuntansi Dasar', 'guru_idx' => 9],
            ['nama_mapel' => 'Sosiologi', 'kode_mapel' => 'SOS-SMA', 'deskripsi' => 'Sosiologi dan Dinamika Masyarakat', 'guru_idx' => 9],
            ['nama_mapel' => 'Geografi', 'kode_mapel' => 'GEO-SMA', 'deskripsi' => 'Geografi Fisik dan Sosial', 'guru_idx' => 10],
        ];

        $mapelsWithGuru = [];
        foreach ($mapelSpecs as $spec) {
            $mapel = MataPelajaran::firstOrCreate(
                ['nama_mapel' => $spec['nama_mapel']],
                [
                    'kode_mapel' => $spec['kode_mapel'],
                    'deskripsi' => $spec['deskripsi'],
                ]
            );
            $mapelsWithGuru[] = [
                'mapel' => $mapel,
                'guru' => $gurus[$spec['guru_idx']],
            ];
        }

        // 4. Buat 15 Kelas Tingkat X, XI, XII (X F1-F5, XI F1-F5, XII F1-F5)
        $kelasNames = [
            'X F1', 'X F2', 'X F3', 'X F4', 'X F5',
            'XI F1', 'XI F2', 'XI F3', 'XI F4', 'XI F5',
            'XII F1', 'XII F2', 'XII F3', 'XII F4', 'XII F5',
        ];

        $kelasList = [];
        foreach ($kelasNames as $idx => $namaKelas) {
            $wali = $gurus[$idx % count($gurus)];
            $kelasList[] = Kelas::firstOrCreate(
                ['nama_kelas' => $namaKelas, 'tahun_ajaran' => '2026/2027'],
                ['id_wali_kelas' => $wali->id]
            );
        }

        // 5. Buat 5 Siswa Unik untuk Setiap Kelas (75 Siswa total, 1 siswa = 1 kelas)
        $namaDepan = [
            'Aditya', 'Aisyah', 'Alif', 'Amalia', 'Bagas', 'Bintang', 'Cahya', 'Daffa', 'Dina', 'Erlangga',
            'Farah', 'Fathur', 'Galih', 'Gisela', 'Hafiz', 'Hanifah', 'Ilham', 'Intan', 'Jihan', 'Kamil',
            'Keisha', 'Laras', 'Maulana', 'Mutiara', 'Naufal', 'Nayla', 'Oktavian', 'Putri', 'Qori', 'Rafi',
            'Rahma', 'Raka', 'Rayhan', 'Riska', 'Salma', 'Satria', 'Syifa', 'Taufik', 'Tiara', 'Vino',
            'Wulan', 'Yusuf', 'Zahra', 'Zaki', 'Zalfa', 'Zidan', 'Anisa', 'Bayu', 'Cinta', 'Deva',
            'Fadlan', 'Ghina', 'Hilmi', 'Irfan', 'Keyla', 'Luthfi', 'Nadia', 'Rendra', 'Salsabila', 'Tania',
            'Abyan', 'Belinda', 'Calista', 'Danang', 'Elvira', 'Fikri', 'Gilang', 'Hana', 'Iqbal', 'Jasmine',
            'Kania', 'Lukman', 'Melati', 'Nizar', 'Olivia',
        ];
        $namaBelakang = [
            'Pratama', 'Wijaya', 'Saputra', 'Ramadhani', 'Kusuma', 'Hidayat', 'Nugroho', 'Sari', 'Permata', 'Fauzan',
            'Lestari', 'Setiawan', 'Utami', 'Mahendra', 'Puspita', 'Hakim', 'Kurniawan', 'Rahmawati', 'Santoso', 'Aulia',
            'Prameswari', 'Wibowo', 'Kartika', 'Firmansyah', 'Anggraini', 'Purnama', 'Subagyo', 'Maharani', 'Cahyono', 'Handayani',
        ];

        $siswaPerKelas = [];
        $studentCounter = 0;

        foreach ($kelasList as $kIdx => $kelas) {
            for ($s = 1; $s <= 5; $s++) {
                $slug = strtolower(str_replace(' ', '', $kelas->nama_kelas));
                $email = "siswa.{$slug}.0{$s}@gmail.com";
                $nisn = '2026' . str_pad((string) ($kIdx + 1), 2, '0', STR_PAD_LEFT) . str_pad((string) $s, 4, '0', STR_PAD_LEFT);

                $first = $namaDepan[$studentCounter % count($namaDepan)];
                $last = $namaBelakang[($studentCounter * 3 + $kIdx) % count($namaBelakang)];

                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => "{$first} {$last}",
                        'nisn' => $nisn,
                        'password' => $defaultPassword,
                        'role' => 'siswa',
                        'id_kelas' => $kelas->id,
                    ]
                );

                $studentCounter++;
            }

            $siswaPerKelas[$kelas->id] = User::where('role', 'siswa')
                ->where('id_kelas', $kelas->id)
                ->orderBy('id')
                ->get();
        }

        // 6. Buat Jadwal Pelajaran Realistis Tanpa Bentrok (Guru maupun Kelas)
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $timeSlots = [
            ['07:00:00', '08:30:00'],
            ['08:30:00', '10:00:00'],
            ['10:15:00', '11:45:00'],
            ['12:30:00', '14:00:00'],
            ['14:00:00', '15:30:00'],
        ];

        $hariIniId = $this->resolveDayName(now());

        foreach ($kelasList as $kIdx => $kelas) {
            // Kombinasi mapel yang sesuai tingkat X, XI, XII agar seluruh 17 mapel terpakai merata
            $mapelIndices = match (true) {
                $kIdx < 5 => [5, 3, 4, 0, 8, 11, 9, 1],    // Kelas X: MTK, B.Indo, B.Ing, PAI, Informatika, Fisika, PJOK, Kemuhammadiyahan
                $kIdx < 10 => [5, 3, 4, 2, 6, 12, 13, 10], // Kelas XI: MTK, B.Indo, B.Ing, B.Arab, Pancasila, Kimia, Biologi, Seni Budaya
                default => [5, 3, 4, 0, 7, 14, 15, 16],    // Kelas XII: MTK, B.Indo, B.Ing, PAI, Sejarah, Ekonomi, Sosiologi, Geografi
            };

            $preferredDays = array_values(array_unique(array_merge([$hariIniId, 'Jumat'], $hariList)));

            foreach ($mapelIndices as $mOrder => $mIdx) {
                $targetMapel = $mapelsWithGuru[$mIdx]['mapel'];
                $targetGuru = $mapelsWithGuru[$mIdx]['guru'];

                $alreadyHasMapel = Jadwal::where('id_kelas', $kelas->id)
                    ->where('id_mapel', $targetMapel->id)
                    ->exists();

                if ($alreadyHasMapel) {
                    continue;
                }

                $placed = false;
                $dayStartOffset = ($kIdx + $mOrder) % count($preferredDays);
                for ($d = 0; $d < count($preferredDays) && ! $placed; $d++) {
                    $hari = ($mOrder === 0 && $d === 0)
                        ? $hariIniId
                        : (($mOrder === 1 && $d === 0)
                            ? 'Jumat'
                            : $preferredDays[($d + $dayStartOffset) % count($preferredDays)]);

                    foreach ($timeSlots as [$start, $end]) {
                        $kelasConflict = Jadwal::where('hari', $hari)
                            ->where('id_kelas', $kelas->id)
                            ->where('jam_mulai', '<', $end)
                            ->where('jam_selesai', '>', $start)
                            ->exists();

                        $guruConflict = Jadwal::where('hari', $hari)
                            ->where('id_guru', $targetGuru->id)
                            ->where('jam_mulai', '<', $end)
                            ->where('jam_selesai', '>', $start)
                            ->exists();

                        if (! $kelasConflict && ! $guruConflict) {
                            Jadwal::create([
                                'id_kelas' => $kelas->id,
                                'id_mapel' => $targetMapel->id,
                                'id_guru' => $targetGuru->id,
                                'hari' => $hari,
                                'jam_mulai' => $start,
                                'jam_selesai' => $end,
                            ]);
                            $placed = true;
                            break;
                        }
                    }
                }
            }
        }

        // 7. Data Presensi 1 Bulan Terakhir (Ketat: siswa.id_kelas === jadwal.id_kelas & hari sesuai tanggal)
        $statusPool = ['H', 'H', 'H', 'H', 'H', 'H', 'H', 'H', 'I', 'S', 'A'];
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->startOfDay();
        $correctedCount = 0;

        foreach ($kelasList as $kelas) {
            $classStudents = $siswaPerKelas[$kelas->id] ?? collect();
            $classSchedules = Jadwal::where('id_kelas', $kelas->id)->get()->groupBy('hari');

            if ($classStudents->isEmpty() || $classSchedules->isEmpty()) {
                continue;
            }

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                if ($date->isSunday()) {
                    continue;
                }

                $dayName = $this->resolveDayName($date);
                $daySchedules = $classSchedules->get($dayName);
                if (! $daySchedules || $daySchedules->isEmpty()) {
                    continue;
                }

                foreach ($daySchedules->take(2) as $jadwal) {
                    foreach ($classStudents as $sIdx => $siswa) {
                        $dateStr = $date->toDateString();

                        $exists = Absensi::where('siswa_id', $siswa->id)
                            ->where('jadwal_id', $jadwal->id)
                            ->where('tanggal', $dateStr)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        $seedNum = ($siswa->id * 7 + $jadwal->id * 3 + $date->day) % count($statusPool);
                        $status = $statusPool[$seedNum];
                        $metode = ($status === 'H') ? (($sIdx % 4 === 0) ? 'Manual' : 'QR') : 'Manual';

                        $jamMulai = Carbon::parse($dateStr . ' ' . $jadwal->jam_mulai);
                        $waktuAbsen = $jamMulai->copy()->addMinutes(($siswa->id + $date->day) % 14);

                        $dikoreksiOleh = null;
                        if ($status !== 'H' && $correctedCount < 15 && ($date->day % 3 === 0)) {
                            $dikoreksiOleh = $admin->id;
                            $correctedCount++;
                        }

                        Absensi::create([
                            'siswa_id' => $siswa->id,
                            'jadwal_id' => $jadwal->id,
                            'tanggal' => $dateStr,
                            'status' => $status,
                            'metode' => $metode,
                            'latitude' => $metode === 'QR' ? -7.8013890 : null,
                            'longitude' => $metode === 'QR' ? 110.3647220 : null,
                            'waktu_absen' => $waktuAbsen,
                            'dikoreksi_oleh' => $dikoreksiOleh,
                        ]);
                    }
                }
            }
        }

        // 8. Pengajuan Izin (Pending, Disetujui, Ditolak) untuk siswa X F1 - XII F5
        $izinTemplates = [
            ['jenis' => 'Sakit', 'status' => 'Pending', 'alasan' => 'Demam dan flu berat sejak malam hari, perlu istirahat di rumah.'],
            ['jenis' => 'Izin', 'status' => 'Pending', 'alasan' => 'Mengikuti lomba olimpiade sains tingkat kota mewakili sekolah.'],
            ['jenis' => 'Sakit', 'status' => 'Disetujui', 'alasan' => 'Kontrol kesehatan rutin di puskesmas dengan surat keterangan dokter.'],
            ['jenis' => 'Izin', 'status' => 'Disetujui', 'alasan' => 'Keperluan keluarga menghadiri acara wisuda kakak di luar kota.'],
            ['jenis' => 'Izin', 'status' => 'Ditolak', 'alasan' => 'Permohonan izin pulang lebih awal tanpa surat dari orang tua/wali.'],
            ['jenis' => 'Sakit', 'status' => 'Pending', 'alasan' => 'Sakit gigi dan harus menjalani perawatan di klinik pagi ini.'],
        ];

        foreach ($kelasList as $kIdx => $kelas) {
            $student = $siswaPerKelas[$kelas->id]->first();
            if (! $student) {
                continue;
            }
            $tpl = $izinTemplates[$kIdx % count($izinTemplates)];
            $waliGuru = $kelas->id_wali_kelas ? User::find($kelas->id_wali_kelas) : $gurus[0];
            $alasan = "[{$kelas->nama_kelas}] {$tpl['alasan']}";

            if (! PengajuanIzin::where('siswa_id', $student->id)->where('alasan', $alasan)->exists()) {
                $tgl = now()->subDays($kIdx % 10)->toDateString();
                PengajuanIzin::create([
                    'siswa_id' => $student->id,
                    'id_guru_approver' => $tpl['status'] === 'Pending' ? null : $waliGuru?->id,
                    'tanggal_mulai' => $tgl,
                    'tanggal_selesai' => $tgl,
                    'jenis' => $tpl['jenis'],
                    'alasan' => $alasan,
                    'status' => $tpl['status'],
                    'catatan_guru' => $tpl['status'] === 'Pending' ? null : ($tpl['status'] === 'Disetujui' ? 'Disetujui oleh wali kelas.' : 'Mohon sertakan konfirmasi wali murid.'),
                    'tanggal_pengajuan' => now()->subDays($kIdx % 10)->setTime(7, 30),
                ]);
            }
        }

        // Tambah 2 pengajuan izin lagi pada siswa demo utama (X F1 siswa 1) agar status Pending, Disetujui, Ditolak terlihat di Dashboard Siswa
        $demoSiswa = $siswaPerKelas[$kelasList[0]->id]->first();
        if ($demoSiswa) {
            $extraDemoIzins = [
                ['jenis' => 'Izin', 'status' => 'Disetujui', 'alasan' => '[X F1] Mewakili sekolah pada lomba debat bahasa Indonesia tingkat kota.', 'days' => 5],
                ['jenis' => 'Izin', 'status' => 'Ditolak', 'alasan' => '[X F1] Izin keperluan pribadi mendadak tanpa lampiran surat wali.', 'days' => 8],
            ];
            foreach ($extraDemoIzins as $item) {
                if (! PengajuanIzin::where('siswa_id', $demoSiswa->id)->where('alasan', $item['alasan'])->exists()) {
                    $tgl = now()->subDays($item['days'])->toDateString();
                    PengajuanIzin::create([
                        'siswa_id' => $demoSiswa->id,
                        'id_guru_approver' => $gurus[0]->id,
                        'tanggal_mulai' => $tgl,
                        'tanggal_selesai' => $tgl,
                        'jenis' => $item['jenis'],
                        'alasan' => $item['alasan'],
                        'status' => $item['status'],
                        'catatan_guru' => $item['status'] === 'Disetujui' ? 'Disetujui, selamat bertanding.' : 'Silakan ajukan ulang dengan surat orang tua.',
                        'tanggal_pengajuan' => now()->subDays($item['days'])->setTime(7, 15),
                    ]);
                }
            }
        }

        // 9. Pengumuman kelas untuk X F1 - XII F5 (plus beberapa pengumuman dari Guru demo #1 untuk menu Pengumuman Guru)
        foreach ($kelasList as $kIdx => $kelas) {
            $waliGuru = $kelas->id_wali_kelas ? User::find($kelas->id_wali_kelas) : $gurus[0];
            $judul = "Informasi Pembelajaran Kelas {$kelas->nama_kelas}";

            if (! Pengumuman::where('kelas_id', $kelas->id)->where('judul', $judul)->exists()) {
                Pengumuman::create([
                    'guru_id' => $waliGuru->id,
                    'kelas_id' => $kelas->id,
                    'judul' => $judul,
                    'isi' => "Seluruh siswa kelas {$kelas->nama_kelas} diharapkan melakukan presensi QR tepat waktu sebelum jam pelajaran dimulai.",
                    'tanggal' => now()->subDays($kIdx % 5),
                ]);
            }
        }

        $extraPengumumanGuru1 = [
            ['judul' => 'Jadwal Latihan Soal Matematika Pekan Ini', 'isi' => 'Silakan kerjakan latihan soal bab fungsi komposisi sebelum pertemuan berikutnya.'],
            ['judul' => 'Persiapan Penilaian Tengah Semester Genap', 'isi' => 'Materi kisi-kisi PTS sudah tersedia dan akan dibahas pada sesi tatap muka minggu depan.'],
            ['judul' => 'Sosialisasi Tata Tertib Presensi QR Sekolah', 'isi' => 'Pastikan GPS perangkat aktif dan siswa berada di area sekolah saat memindai kode QR di kelas.'],
            ['judul' => 'Pengingat Batas Waktu Pengajuan Izin', 'isi' => 'Pengajuan izin atau sakit melalui sistem dilakukan maksimal pukul 09.00 WIB dengan keterangan yang jelas.'],
        ];

        foreach ($extraPengumumanGuru1 as $idx => $pData) {
            $targetKelas = $kelasList[$idx % 3]; // X F1, X F2, X F3
            if (! Pengumuman::where('kelas_id', $targetKelas->id)->where('judul', $pData['judul'])->exists()) {
                Pengumuman::create([
                    'guru_id' => $gurus[0]->id,
                    'kelas_id' => $targetKelas->id,
                    'judul' => $pData['judul'],
                    'isi' => $pData['isi'],
                    'tanggal' => now()->subDays($idx + 1),
                ]);
            }
        }
    }

    private function resolveDayName(Carbon $date): string
    {
        return match ($date->dayOfWeekIso) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            default => 'Senin',
        };
    }
}
