<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            
            // REVISI: Tambahkan nullable() karena saat awal diajukan, belum ada gurunya
            $table->foreignId('id_guru_approver')->nullable()->constrained('users')->onDelete('set null');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            // REVISI: Gunakan huruf besar di awal agar sama dengan Controller
            $table->enum('jenis', ['Izin', 'Sakit']);
            $table->text('alasan');
            $table->string('file_surat')->nullable();

            // REVISI: Gunakan huruf besar di awal agar sama dengan Controller
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');

            $table->text('catatan_guru')->nullable();
            $table->dateTime('tanggal_pengajuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izins');
    }
};