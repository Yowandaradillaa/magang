<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('q_r_codes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('jadwal_id')
                  ->constrained('jadwals')
                  ->onDelete('cascade');

            $table->foreignId('guru_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('kode_qr');

            $table->dateTime('waktu_dibuat');
            $table->dateTime('waktu_expired');

            $table->enum('status', [
                'aktif',
                'expired'
            ])->default('aktif');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('q_r_codes');
    }
};