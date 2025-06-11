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
        Schema::create('teman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('teman_id');
            $table->timestamps();

            // Tambahkan foreign key, diasumsikan tabelnya bernama 'akun'
            $table->foreign('user_id')->references('id')->on('akun')->onDelete('cascade');
            $table->foreign('teman_id')->references('id')->on('akun')->onDelete('cascade');

            // Optional: agar tidak ada relasi ganda yang sama
            $table->unique(['user_id', 'teman_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teman');
    }
};
