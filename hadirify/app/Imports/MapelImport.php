<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

/**
 * Import data Mata Pelajaran dari file Excel/CSV.
 */
class MapelImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    use ImportResult;

    protected array $namaTerpakai = [];
    protected array $kodeTerpakai = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $nomorBaris = $index + 2;

            $namaMapel = trim((string) ($row['nama_mapel'] ?? $row['mapel'] ?? $row['nama'] ?? ''));
            $kodeMapel = trim((string) ($row['kode_mapel'] ?? $row['kode'] ?? ''));
            $deskripsi = trim((string) ($row['deskripsi'] ?? $row['keterangan'] ?? ''));

            // Lewati baris kosong
            if ($namaMapel === '' && $kodeMapel === '' && $deskripsi === '') {
                continue;
            }

            // Validasi nama mapel
            if ($namaMapel === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'Nama Mapel' wajib diisi.";
                continue;
            }

            if (mb_strlen($namaMapel) > 255) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Nama mata pelajaran maksimal 255 karakter.";
                continue;
            }

            // Cek duplikasi Nama Mapel di file yang sama
            $namaKey = mb_strtolower($namaMapel);
            if (isset($this->namaTerpakai[$namaKey])) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Mata pelajaran ini duplikat dalam file yang sama.";
                continue;
            }

            // Cek duplikasi Nama Mapel di Database
            if (MataPelajaran::whereRaw('LOWER(TRIM(nama_mapel)) = ?', [$namaKey])->exists()) {
                $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Mata pelajaran sudah terdaftar di sistem.";
                continue;
            }

            // Validasi kode mapel jika diisi
            $kodeKey = mb_strtolower($kodeMapel);
            if ($kodeMapel !== '') {
                if (mb_strlen($kodeMapel) > 50) {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Kode mapel maksimal 50 karakter.";
                    continue;
                }

                if (isset($this->kodeTerpakai[$kodeKey])) {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Kode mapel '{$kodeMapel}' duplikat dalam file ini.";
                    continue;
                }

                if (MataPelajaran::whereRaw('LOWER(TRIM(kode_mapel)) = ?', [$kodeKey])->exists()) {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Kode mapel '{$kodeMapel}' sudah digunakan.";
                    continue;
                }
            }

            try {
                MataPelajaran::create([
                    'nama_mapel' => $namaMapel,
                    'kode_mapel' => $kodeMapel !== '' ? $kodeMapel : null,
                    'deskripsi'  => $deskripsi !== '' ? $deskripsi : null,
                ]);

                $this->namaTerpakai[$namaKey] = true;
                if ($kodeKey !== '') {
                    $this->kodeTerpakai[$kodeKey] = true;
                }
                $this->successCount++;
            } catch (QueryException $e) {
                if ($this->isDuplicateDatabaseException($e)) {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Data mata pelajaran duplikat di sistem.";
                } else {
                    $this->errors[] = "Baris {$nomorBaris} ({$namaMapel}): Terjadi kesalahan database - " . $e->getMessage();
                }
            }
        }
    }
}
