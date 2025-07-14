<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('karyawans', function (Blueprint $table) {
        $table->string('foto_wajah')->nullable()->after('jabatan');
    });
}

public function down(): void
{
    Schema::table('karyawans', function (Blueprint $table) {
        $table->dropColumn('foto_wajah');
    });
}

};
