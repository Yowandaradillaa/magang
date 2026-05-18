<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id(); // Ini akan jadi id_jadwal secara logic

            // Sesuaikan nama kolom dengan ERD dan Seeder (id_nama_tabel)
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_guru');

            $table->enum('hari', [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            ]);

            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();

            // Set Foreign Key secara manual karena nama kolom tidak standar Laravel (id_...)
            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('id_mapel')->references('id')->on('mata_pelajarans')->onDelete('cascade');
            $table->foreign('id_guru')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};