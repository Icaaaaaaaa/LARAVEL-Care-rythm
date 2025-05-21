<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel jika sudah ada (hanya untuk development, jangan di production)
        Schema::dropIfExists('pencapaian');

        Schema::create('pencapaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama');
            $table->dateTime('waktu_pencapaian');
            $table->integer('target');
            $table->integer('jumlah');
            $table->string('kategori')->nullable(); // tambahkan kolom kategori
            $table->timestamps();
        });

        Schema::table('pencapaian', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('akun')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian');
    }
};
