<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practicum_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('practicum_schedules')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai'])->default('diajukan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // satu mahasiswa tidak bisa booking jadwal yang sama dua kali
            $table->unique(['schedule_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practicum_bookings');
    }
};
