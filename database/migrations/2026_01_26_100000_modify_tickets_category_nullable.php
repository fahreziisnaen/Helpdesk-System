<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom category menjadi nullable untuk menghindari error ENUM
        // Karena sekarang kita menggunakan category_id saja
        Schema::table('tickets', function (Blueprint $table) {
            // Ubah kolom category menjadi nullable
            DB::statement("ALTER TABLE tickets MODIFY COLUMN category ENUM('hardware', 'software', 'network', 'other') NULL DEFAULT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Kembalikan ke default 'other' jika diperlukan
            DB::statement("ALTER TABLE tickets MODIFY COLUMN category ENUM('hardware', 'software', 'network', 'other') NOT NULL DEFAULT 'other'");
        });
    }
};
