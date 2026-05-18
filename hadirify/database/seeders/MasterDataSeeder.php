<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT MATA PELAJARAN (Sesuai ERD: nama_mapel, deskripsi)
        $mapel = MataPelajaran::create([
            'nama_mapel' => 'Pemrograman Web',
            'deskripsi'  => 'Materi Backend Laravel'
        ]);

        // 2. BUAT GURU (Sesuai ERD: NUPTK, nama, email)
        $guru = User::create([
            'name'     => 'Guru Joko, S.Kom',
            'email'    => 'guru@gmail.com',
            'nuptk'    => '1122334455667788',
            'password' => Hash::make('password'),
            'role'     => 'guru',
        ]);

        // 3. BUAT KELAS (Sesuai ERD: nama_kelas, tahun_ajaran, id_wali_kelas)
        // id_wali_kelas merujuk ke id si Guru Joko
        $kelas = Kelas::create([
            'nama_kelas'    => 'XII PPLG 1',
            'tahun_ajaran'  => '2023/2024',
            'id_wali_kelas' => $guru->id 
        ]);

        // 4. BUAT SISWA (Sesuai ERD: NISN, nama, id_kelas)
        User::create([
            'name'     => 'Asep Kasep',
            'email'    => 'asep@gmail.com',
            'nisn'     => '0011223344',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
            'id_kelas' => $kelas->id // Masukkan Asep ke kelas XII PPLG 1
        ]);

        // 5. BUAT ADMIN
        User::create([
            'name'     => 'Admin Hadirify',
            'email'    => 'admin@gmail.com',
            'nuptk'    => '1234567890123456',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // 6. BUAT JADWAL (Sesuai ERD: id_kelas, id_mapel, id_guru, hari, jam)
        // Kita set hari ini agar kamu bisa langsung tes Scan QR
        $hariIni = now()->locale('id')->dayName; // Mengambil nama hari (Senin/Selasa/...)

        Jadwal::create([
            'id_kelas'    => $kelas->id,
            'id_mapel'    => $mapel->id,
            'id_guru'     => $guru->id,
            'hari'        => $hariIni,
            'jam_mulai'   => '07:00:00',
            'jam_selesai' => '15:00:00',
        ]);
    }
}