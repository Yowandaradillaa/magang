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

            $table->foreignId('siswa_id');
            $table->foreignId('id_guru_approver');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->enum('jenis', ['izin', 'sakit']);

            $table->text('alasan');

            $table->string('file_surat')->nullable();

            $table->enum('status', [

                'pending',
                'disetujui',
                'ditolak'

            ])->default('pending');

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