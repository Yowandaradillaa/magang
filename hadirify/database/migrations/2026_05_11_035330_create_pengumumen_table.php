<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumumen', function (Blueprint $table) {

            $table->id();

            $table->foreignId('guru_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('kelas_id')
                  ->constrained('kelas')
                  ->onDelete('cascade');

            $table->string('judul');

            $table->text('isi');

            $table->timestamp('tanggal');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumumen');
    }
};