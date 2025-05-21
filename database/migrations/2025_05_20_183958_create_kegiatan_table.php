<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cegah error jika tabel sudah ada
        if (!Schema::hasTable('kegiatan')) {
            Schema::create('kegiatan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // relasi ke akun
                $table->string('kegiatan', 255);
                $table->text('catatan')->nullable();
                $table->date('tanggal');
                $table->time('waktu_mulai');
                $table->time('waktu_selesai');
                $table->string('tempat', 255)->nullable();
                // Foreign key constraint
                $table->foreign('user_id')->references('id')->on('akun')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};

