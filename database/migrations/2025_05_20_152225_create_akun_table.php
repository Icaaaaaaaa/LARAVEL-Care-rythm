<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Membuat tabel 'akun' saat migrasi dijalankan
        Schema::create('akun', function (Blueprint $table) {
            $table->id(); // Kolom primary key otomatis, tipe BIGINT auto-increment
            $table->string('username', 50); // Kolom untuk username dengan panjang maksimum 50 karakter
            $table->string('kataSandi', 255); // Kolom untuk kata sandi (sebaiknya gunakan nama 'password')
            $table->string('email', 25); // Kolom email dengan panjang maksimum 25 karakter (terlalu pendek untuk beberapa email)
            $table->enum('role', ['user', 'admin']); // Kolom enum untuk menyimpan peran user ('user' atau 'admin')
        });
    }

    public function down(): void
    {
        // Menghapus tabel 'akun' jika migrasi di-rollback
        Schema::dropIfExists('akun');
    }
};

