<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

/**
 * Import data Kelas dari file Excel/CSV.
 */
class KelasImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    use ImportResult;

    protected array $kelasTerpakai = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $nomorBaris = $index + 2;

            $namaKelas       = trim((string) ($row['nama_kelas'] ?? $row['kelas'] ?? $row['nama'] ?? ''));
            $tahunAjaran     = trim((string) ($row['tahun_ajaran'] ?? $row['tahun'] ?? ''));
            $waliKelasInput  = trim((string) ($row['wali_kelas'] ?? $row['wali'] ?? $row['nama_wali_kelas'] ?? ''));

            // Lewati baris kosong
            if ($namaKelas === '' && $tahunAjaran === '' && $waliKelasInput === '') {
                continue;
            }

            // Validasi dasar
            if ($namaKelas === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'Nama Kelas' wajib diisi.";
                continue;
            }

            if ($tahunAjaran === '') {
                $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Kolom 'Tahun Ajaran' wajib diisi (contoh: 2026/2027).";
                continue;
            }

            if (mb_strlen($namaKelas) > 255) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Nama kelas maksimal 255 karakter.";
                continue;
            }

            if (mb_strlen($tahunAjaran) > 20) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Format tahun ajaran maksimal 20 karakter.";
                continue;
            }

            // Cek duplikasi kombinasi nama kelas & tahun ajaran di file
            $comboKey = mb_strtolower($namaKelas) . '|' . mb_strtolower($tahunAjaran);
            if (isset($this->kelasTerpakai[$comboKey])) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Kelas dengan tahun ajaran '{$tahunAjaran}' duplikat dalam file ini.";
                continue;
            }

            // Cek duplikasi di database
            $existsInDb = Kelas::whereRaw('LOWER(TRIM(nama_kelas)) = ?', [mb_strtolower($namaKelas)])
                ->whereRaw('LOWER(TRIM(tahun_ajaran)) = ?', [mb_strtolower($tahunAjaran)])
                ->exists();

            if ($existsInDb) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Kelas dengan tahun ajaran '{$tahunAjaran}' sudah terdaftar di sistem.";
                continue;
            }

            // Cari ID Wali Kelas jika nama wali kelas diisi
            $idWaliKelas = null;
            if ($waliKelasInput !== '') {
                $guru = User::where('role', 'guru')
                    ->where(function ($q) use ($waliKelasInput) {
                        $q->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($waliKelasInput)])
                          ->orWhere('nuptk', $waliKelasInput);
                    })
                    ->first();

                if ($guru) {
                    $idWaliKelas = $guru->id;
                }
            }

            try {
                Kelas::create([
                    'nama_kelas'    => $namaKelas,
                    'tahun_ajaran'  => $tahunAjaran,
                    'id_wali_kelas' => $idWaliKelas,
                ]);

                $this->kelasTerpakai[$comboKey] = true;
                $this->successCount++;
            } catch (QueryException $e) {
                if ($this->isDuplicateDatabaseException($e)) {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Kelas '{$namaKelas}' ({$tahunAjaran}) sudah terdaftar.";
                } else {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaKelas}): Gagal menyimpan - " . $e->getMessage();
                }
            }
        }
    }
}
