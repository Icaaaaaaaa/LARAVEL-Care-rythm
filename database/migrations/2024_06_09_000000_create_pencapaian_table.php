<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pencapaian', function (Blueprint $table) {
            $table->id();
            // ...tambahkan kolom lain sesuai kebutuhan...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian');
    }
};
