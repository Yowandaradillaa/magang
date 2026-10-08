<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->unique(['nama_kelas', 'tahun_ajaran'], 'kelas_name_year_unique');
        });

        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->unique('nama_mapel', 'mata_pelajarans_name_unique');
        });

        Schema::table('jadwals', function (Blueprint $table) {
            $table->unique(
                ['id_kelas', 'hari', 'jam_mulai', 'jam_selesai'],
                'jadwals_class_slot_unique'
            );
            $table->unique(
                ['id_guru', 'hari', 'jam_mulai', 'jam_selesai'],
                'jadwals_teacher_slot_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropUnique('jadwals_class_slot_unique');
            $table->dropUnique('jadwals_teacher_slot_unique');
        });

        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->dropUnique('mata_pelajarans_name_unique');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropUnique('kelas_name_year_unique');
        });
    }
};
