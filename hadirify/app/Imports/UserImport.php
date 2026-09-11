<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

/**
 * Import massal user (siswa/guru/admin) dari file Excel.
 * Baris yang error DILEWATI (tidak menggagalkan seluruh proses),
 * pesan errornya dikumpulkan untuk ditampilkan ke admin.
 */
class UsersImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    protected array $errors = [];
    protected int $successCount = 0;

    // Cegah duplikat ANTAR baris di dalam file yang sama
    protected array $emailTerpakai = [];
    protected array $nisnTerpakai = [];
    protected array $nuptkTerpakai = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Baris 1 = header, jadi data pertama = baris ke-2
            $nomorBaris = $index + 2;

            $nama      = trim((string) ($row['nama'] ?? ''));
            $role      = strtolower(trim((string) ($row['role'] ?? '')));
            $email     = trim((string) ($row['email'] ?? ''));
            $nisn      = trim((string) ($row['nisn'] ?? ''));
            $nuptk     = trim((string) ($row['nuptk'] ?? ''));
            $namaKelas = trim((string) ($row['kelas'] ?? ''));

            // Lewati baris kosong (sisa baris kosong di Excel)
            if ($nama === '' && $role === '' && $email === '' && $nisn === '' && $nuptk === '') {
                continue;
            }

            // ---------- Validasi dasar ----------
            if ($nama === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'nama' wajib diisi.";
                continue;
            }

            if (!in_array($role, ['siswa', 'guru', 'admin'])) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Kolom 'role' harus 'siswa', 'guru', atau 'admin'.";
                continue;
            }

            if ($role === 'siswa' && $nisn === '') {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Siswa wajib mengisi NISN.";
                continue;
            }

            if (in_array($role, ['guru', 'admin']) && $nuptk === '') {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): {$role} wajib mengisi NUPTK.";
                continue;
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Format email '{$email}' tidak valid.";
                continue;
            }

            // ---------- Cek duplikat DI DALAM file yang sama ----------
            if ($email !== '' && in_array($email, $this->emailTerpakai)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Email '{$email}' dipakai lebih dari satu baris di file ini.";
                continue;
            }
            if ($nisn !== '' && in_array($nisn, $this->nisnTerpakai)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NISN '{$nisn}' dipakai lebih dari satu baris di file ini.";
                continue;
            }
            if ($nuptk !== '' && in_array($nuptk, $this->nuptkTerpakai)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NUPTK '{$nuptk}' dipakai lebih dari satu baris di file ini.";
                continue;
            }

            // ---------- Cek duplikat DI DATABASE ----------
            if ($email !== '' && User::where('email', $email)->exists()) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Email '{$email}' sudah terdaftar.";
                continue;
            }
            if ($nisn !== '' && User::where('nisn', $nisn)->exists()) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NISN '{$nisn}' sudah terdaftar.";
                continue;
            }
            if ($nuptk !== '' && User::where('nuptk', $nuptk)->exists()) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NUPTK '{$nuptk}' sudah terdaftar.";
                continue;
            }

            // ---------- Cari kelas dari NAMA kelas (khusus siswa) ----------
            $idKelas = null;
            if ($role === 'siswa') {
                if ($namaKelas === '') {
                    $this->errors[] = "Baris {$nomorBaris} ({$nama}): Kolom 'kelas' wajib diisi untuk siswa.";
                    continue;
                }
                $kelas = Kelas::whereRaw('LOWER(nama_kelas) = ?', [strtolower($namaKelas)])->first();
                if (!$kelas) {
                    $this->errors[] = "Baris {$nomorBaris} ({$nama}): Kelas '{$namaKelas}' tidak ditemukan. Cek ejaan di halaman Manajemen Kelas.";
                    continue;
                }
                $idKelas = $kelas->id;
            }

            // ---------- Simpan ----------
            $passwordDefault = $role === 'siswa' ? $nisn : $nuptk;

            User::create([
                'name'     => $nama,
                'email'    => $email !== '' ? $email : null,
                'role'     => $role,
                'nisn'     => $nisn !== '' ? $nisn : null,
                'nuptk'    => $nuptk !== '' ? $nuptk : null,
                'id_kelas' => $idKelas,
                'password' => Hash::make($passwordDefault),
            ]);

            if ($email !== '') $this->emailTerpakai[] = $email;
            if ($nisn !== '') $this->nisnTerpakai[] = $nisn;
            if ($nuptk !== '') $this->nuptkTerpakai[] = $nuptk;

            $this->successCount++;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
}