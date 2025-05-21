<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// File ini adalah migrasi tambahan untuk menambah kolom 'kategori' pada tabel 'pencapaian'.
// File ini diperlukan jika migrasi awal pencapaian TIDAK memiliki kolom 'kategori'.
// Jika migrasi awal sudah ada kolom 'kategori', file ini boleh dihapus.

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pencapaian', function (Blueprint $table) {
            $table->string('kategori')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pencapaian', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
