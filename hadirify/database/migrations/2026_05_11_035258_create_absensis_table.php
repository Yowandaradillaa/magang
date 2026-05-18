<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('jadwal_id')
                  ->constrained('jadwals')
                  ->onDelete('cascade');

            $table->date('tanggal');

            // REVISI: Sesuaikan dengan yang dikirim Controller (H/A/S/I)
            $table->enum('status', ['H', 'A', 'S', 'I']);

            // REVISI: Sesuaikan dengan yang dikirim Controller (QR/Manual)
            $table->enum('metode', ['QR', 'Manual'])->default('QR');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamp('waktu_absen');

            $table->foreignId('dikoreksi_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};