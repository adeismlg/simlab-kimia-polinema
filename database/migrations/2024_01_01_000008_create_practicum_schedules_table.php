<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practicum_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('nama_praktikum');
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedInteger('kapasitas')->default(20);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practicum_schedules');
    }
};
