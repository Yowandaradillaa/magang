<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

/**
 * Import data Jadwal Mengajar dari file Excel/CSV.
 */
class JadwalImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    use ImportResult;

    protected array $kelasCache = [];
    protected array $mapelCache = [];
    protected array $guruCache = [];

    // Lacak slot yang dibuat dalam file yang sama untuk deteksi bentrok in-file
    protected array $batchSlotsGuru = [];
    protected array $batchSlotsKelas = [];

    public function __construct()
    {
        // Pre-cache kelas
        foreach (Kelas::all() as $k) {
            $this->kelasCache[mb_strtolower(trim($k->nama_kelas))] = $k->id;
        }

        // Pre-cache mata pelajaran (bisa dicari via nama_mapel atau kode_mapel)
        foreach (MataPelajaran::all() as $m) {
            $this->mapelCache[mb_strtolower(trim($m->nama_mapel))] = $m->id;
            if (!empty($m->kode_mapel)) {
                $this->mapelCache[mb_strtolower(trim($m->kode_mapel))] = $m->id;
            }
        }

        // Pre-cache guru
        foreach (User::where('role', 'guru')->get() as $g) {
            $this->guruCache[mb_strtolower(trim($g->name))] = $g->id;
            if (!empty($g->nuptk)) {
                $this->guruCache[trim($g->nuptk)] = $g->id;
            }
        }
    }

    public function collection(Collection $rows)
    {
        $validDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        foreach ($rows as $index => $row) {
            $nomorBaris = $index + 2;

            $hariRaw       = trim((string) ($row['hari'] ?? ''));
            $waktuRaw      = trim((string) ($row['waktu_jam'] ?? $row['waktujam'] ?? $row['waktu'] ?? $row['jam'] ?? ''));
            $jamMulaiRaw   = trim((string) ($row['jam_mulai'] ?? ''));
            $jamSelesaiRaw = trim((string) ($row['jam_selesai'] ?? ''));
            $namaKelasRaw  = trim((string) ($row['nama_kelas'] ?? $row['kelas'] ?? ''));
            $namaMapelRaw  = trim((string) ($row['nama_mapel'] ?? $row['mapel'] ?? $row['mata_pelajaran'] ?? ''));
            $namaGuruRaw   = trim((string) ($row['nama_guru'] ?? $row['guru'] ?? $row['guru_pengampu'] ?? ''));

            // Lewati jika seluruh kolom kosong
            if ($hariRaw === '' && $waktuRaw === '' && $namaKelasRaw === '' && $namaMapelRaw === '' && $namaGuruRaw === '') {
                continue;
            }

            // 1. Validasi Kolom Wajib
            if ($hariRaw === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'Hari' wajib diisi.";
                continue;
            }

            $hari = ucfirst(strtolower($hariRaw));
            if (!in_array($hari, $validDays)) {
                $this->errors[] = "Baris {$nomorBaris}: Hari '{$hariRaw}' tidak valid. Pilihan: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu.";
                continue;
            }

            // 2. Parse Waktu/Jam (H:i - H:i)
            $parsedTime = $this->parseTime($waktuRaw, $jamMulaiRaw, $jamSelesaiRaw);
            if (!$parsedTime) {
                $this->errors[] = "Baris {$nomorBaris}: Format waktu '{$waktuRaw}' tidak valid. Gunakan format 'HH:MM - HH:MM' (contoh: 07:30 - 09:00).";
                continue;
            }

            $jamMulai = $parsedTime['mulai'];
            $jamSelesai = $parsedTime['selesai'];

            if ($jamSelesai <= $jamMulai) {
                $this->errors[] = "Baris {$nomorBaris}: Jam selesai ({$jamSelesai}) harus lebih besar dari jam mulai ({$jamMulai}).";
                continue;
            }

            // 3. Cari Relasi Kelas
            if ($namaKelasRaw === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'Nama Kelas' wajib diisi.";
                continue;
            }
            $kelasKey = mb_strtolower($namaKelasRaw);
            if (!isset($this->kelasCache[$kelasKey])) {
                $this->errors[] = "Baris {$nomorBaris}: Kelas '{$namaKelasRaw}' tidak ditemukan di sistem. Pastikan nama kelas sesuai dengan Menu Manajemen Kelas.";
                continue;
            }
            $idKelas = $this->kelasCache[$kelasKey];

            // 4. Cari Relasi Mapel
            if ($namaMapelRaw === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'Nama Mapel' wajib diisi.";
                continue;
            }
            $mapelKey = mb_strtolower($namaMapelRaw);
            if (!isset($this->mapelCache[$mapelKey])) {
                $this->errors[] = "Baris {$nomorBaris}: Mata Pelajaran '{$namaMapelRaw}' tidak ditemukan. Pastikan nama/kode mapel sesuai dengan Menu Mata Pelajaran.";
                continue;
            }
            $idMapel = $this->mapelCache[$mapelKey];

            // 5. Cari Relasi Guru
            if ($namaGuruRaw === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'Nama Guru' wajib diisi.";
                continue;
            }
            $guruKey = mb_strtolower($namaGuruRaw);
            if (!isset($this->guruCache[$guruKey])) {
                $this->errors[] = "Baris {$nomorBaris}: Guru '{$namaGuruRaw}' tidak ditemukan. Pastikan nama guru sesuai dengan data Akun Guru.";
                continue;
            }
            $idGuru = $this->guruCache[$guruKey];

            // 6. Cek Bentrok Jadwal di Database
            $bentrokGuruDb = Jadwal::where('hari', $hari)
                ->where('id_guru', $idGuru)
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists();

            if ($bentrokGuruDb) {
                $this->errors[] = "Baris {$nomorBaris}: Guru '{$namaGuruRaw}' sudah memiliki jadwal lain yang bertabrakan pada {$hari} ({$jamMulai} - {$jamSelesai}).";
                continue;
            }

            $bentrokKelasDb = Jadwal::where('hari', $hari)
                ->where('id_kelas', $idKelas)
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists();

            if ($bentrokKelasDb) {
                $this->errors[] = "Baris {$nomorBaris}: Kelas '{$namaKelasRaw}' sudah memiliki jadwal lain yang bertabrakan pada {$hari} ({$jamMulai} - {$jamSelesai}).";
                continue;
            }

            // 7. Cek Bentrok dalam File yang Sama
            if ($this->hasOverlap($this->batchSlotsGuru[$idGuru][$hari] ?? [], $jamMulai, $jamSelesai)) {
                $this->errors[] = "Baris {$nomorBaris}: Guru '{$namaGuruRaw}' memiliki jadwal ganda/bentrok dalam file ini pada {$hari} ({$jamMulai} - {$jamSelesai}).";
                continue;
            }

            if ($this->hasOverlap($this->batchSlotsKelas[$idKelas][$hari] ?? [], $jamMulai, $jamSelesai)) {
                $this->errors[] = "Baris {$nomorBaris}: Kelas '{$namaKelasRaw}' memiliki jadwal ganda/bentrok dalam file ini pada {$hari} ({$jamMulai} - {$jamSelesai}).";
                continue;
            }

            // 8. Simpan ke Database
            try {
                Jadwal::create([
                    'id_kelas'    => $idKelas,
                    'id_mapel'    => $idMapel,
                    'id_guru'     => $idGuru,
                    'hari'        => $hari,
                    'jam_mulai'   => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                ]);

                $this->batchSlotsGuru[$idGuru][$hari][] = ['mulai' => $jamMulai, 'selesai' => $jamSelesai];
                $this->batchSlotsKelas[$idKelas][$hari][] = ['mulai' => $jamMulai, 'selesai' => $jamSelesai];
                $this->successCount++;
            } catch (QueryException $e) {
                if ($this->isDuplicateDatabaseException($e)) {
                    $this->errors[] = "Baris {$nomorBaris}: Slot jadwal ini duplikat di sistem.";
                } else {
                    $this->errors[] = "Baris {$nomorBaris}: Gagal menyimpan jadwal - " . $e->getMessage();
                }
            }
        }
    }

    /**
     * Mem-parsing format waktu menjadi array ['mulai' => 'HH:MM', 'selesai' => 'HH:MM']
     */
    protected function parseTime(string $waktuRaw, string $jamMulaiRaw = '', string $jamSelesaiRaw = ''): ?array
    {
        if ($jamMulaiRaw !== '' && $jamSelesaiRaw !== '') {
            $mulai = $this->formatTime($jamMulaiRaw);
            $selesai = $this->formatTime($jamSelesaiRaw);
            return ($mulai && $selesai) ? ['mulai' => $mulai, 'selesai' => $selesai] : null;
        }

        if ($waktuRaw === '') {
            return null;
        }

        // Pisahkan string berdasarkan tanda '-' atau '–' atau 's/d'
        $parts = preg_split('/(\s*[-–]\s*|\s+s\/?d\s+|\s+sampai\s+)/i', $waktuRaw);
        if (count($parts) < 2) {
            return null;
        }

        $mulai = $this->formatTime($parts[0]);
        $selesai = $this->formatTime($parts[1]);

        return ($mulai && $selesai) ? ['mulai' => $mulai, 'selesai' => $selesai] : null;
    }

    /**
     * Standarisasi jam ke format HH:MM
     */
    protected function formatTime(string $timeStr): ?string
    {
        $timeStr = trim(str_replace('.', ':', $timeStr));

        // Format H:i atau H:i:s
        if (preg_match('/^(\d{1,2}):(\d{2})/', $timeStr, $matches)) {
            $jam = (int) $matches[1];
            $menit = (int) $matches[2];
            if ($jam >= 0 && $jam <= 23 && $menit >= 0 && $menit <= 59) {
                return sprintf('%02d:%02d', $jam, $menit);
            }
        }

        return null;
    }

    /**
     * Cek apakah rentang waktu bertabrakan dengan rentang waktu yang ada
     */
    protected function hasOverlap(array $slots, string $jamMulai, string $jamSelesai): bool
    {
        foreach ($slots as $slot) {
            if ($slot['mulai'] < $jamSelesai && $slot['selesai'] > $jamMulai) {
                return true;
            }
        }
        return false;
    }
}
