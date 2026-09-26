<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_parameter');       // contoh: pH, COD, Logam Berat
            $table->string('satuan')->nullable();    // contoh: mg/L, %, ppm
            $table->string('metode_uji')->nullable(); // contoh: SNI 06-6989.11-2004
            $table->decimal('harga_internal', 12, 2)->default(0);
            $table->decimal('harga_eksternal', 12, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
