<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
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
    use ImportResult;

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
            if ($nama === '' && $role === '' && $email === '' && $nisn === '' && $nuptk === '' && $namaKelas === '') {
                continue;
            }

            // ---------- Validasi dasar ----------
            if ($nama === '') {
                $this->errors[] = "Baris {$nomorBaris}: Kolom 'nama' wajib diisi.";
                continue;
            }

            if (mb_strlen($nama) > 255 || !preg_match("/^(?=.*\\p{L})[\\p{L}\\p{M}\\s.'-]+$/u", $nama)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Nama hanya boleh berisi huruf, spasi, titik, apostrof, atau tanda hubung.";
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

            if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL)
                || !preg_match('/^[^@\s]+@gmail\.com$/i', $email))) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Email harus menggunakan format yang valid dan domain @gmail.com.";
                continue;
            }

            if ($role === 'siswa' && !preg_match('/^\d{10}$/', $nisn)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NISN siswa harus terdiri dari 10 angka.";
                continue;
            }

            if (in_array($role, ['guru', 'admin'], true) && !preg_match('/^\d{16}$/', $nuptk)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NUPTK {$role} harus terdiri dari 16 angka.";
                continue;
            }

            // ---------- Cek duplikat DI DALAM file yang sama ----------
            $emailKey = mb_strtolower($email);
            $nisnKey = mb_strtolower($nisn);
            $nuptkKey = mb_strtolower($nuptk);
            if ($email !== '' && isset($this->emailTerpakai[$emailKey])) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Email '{$email}' dipakai lebih dari satu baris di file ini.";
                continue;
            }
            if ($nisn !== '' && isset($this->nisnTerpakai[$nisnKey])) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NISN '{$nisn}' dipakai lebih dari satu baris di file ini.";
                continue;
            }
            if ($nuptk !== '' && isset($this->nuptkTerpakai[$nuptkKey])) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NUPTK '{$nuptk}' dipakai lebih dari satu baris di file ini.";
                continue;
            }

            // ---------- Cek duplikat DI DATABASE ----------
            if ($email !== '' && $this->duplicateKeyExists('users', 'email', $email)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Email '{$email}' sudah terdaftar.";
                continue;
            }
            if ($nisn !== '' && $this->duplicateKeyExists('users', 'nisn', $nisn)) {
                $this->errors[] = "Baris {$nomorBaris} ({$nama}): NISN '{$nisn}' sudah terdaftar.";
                continue;
            }
            if ($nuptk !== '' && $this->duplicateKeyExists('users', 'nuptk', $nuptk)) {
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

            try {
                User::create([
                    'name'     => $nama,
                    'email'    => $email !== '' ? $email : null,
                    'role'     => $role,
                    'nisn'     => $nisn !== '' ? $nisn : null,
                    'nuptk'    => $nuptk !== '' ? $nuptk : null,
                    'id_kelas' => $idKelas,
                    'password' => Hash::make($passwordDefault),
                ]);
            } catch (QueryException $exception) {
                if (!$this->isDuplicateDatabaseException($exception)) {
                    throw $exception;
                }

                $this->errors[] = "Baris {$nomorBaris} ({$nama}): Email, NISN, atau NUPTK sudah digunakan akun lain.";
                continue;
            }

            if ($email !== '') $this->emailTerpakai[$emailKey] = true;
            if ($nisn !== '') $this->nisnTerpakai[$nisnKey] = true;
            if ($nuptk !== '') $this->nuptkTerpakai[$nuptkKey] = true;

            $this->successCount++;
        }
    }
}