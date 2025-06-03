<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id(); // id jadwal (primary key)
            $table->unsignedBigInteger('user_id'); // foreign key ke akun.id
            $table->string('nama_jadwal', 100);
            $table->string('kategori', 50);
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('hari', 50); 
            $table->string('catatan', 255)->nullable();
            $table->foreign('user_id')->references('id')->on('akun')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
