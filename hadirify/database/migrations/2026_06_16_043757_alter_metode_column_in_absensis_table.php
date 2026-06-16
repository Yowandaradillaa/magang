<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE absensis MODIFY metode VARCHAR(50)");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE absensis MODIFY metode ENUM('QR','Manual')");
    }
};