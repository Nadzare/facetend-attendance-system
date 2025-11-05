<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Cek dulu kalau kolom face_token belum ada biar nggak error
        Schema::table('karyawans', function (Blueprint $table) {
            if (!Schema::hasColumn('karyawans', 'face_token')) {
                $table->string('face_token')->nullable()->after('foto_wajah');
            }
        });
    }

    /**
     * Balikkan migrasi (hapus kolom face_token).
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            if (Schema::hasColumn('karyawans', 'face_token')) {
                $table->dropColumn('face_token');
            }
        });
    }
};
