<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50);
            $table->string('kataSandi', 255);
            $table->string('email', 25);
            $table->enum('role', ['user', 'admin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};
