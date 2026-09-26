<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sampel')->unique(); // auto-generate, contoh: SMP-2026-0001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_sampel');
            $table->text('deskripsi')->nullable();
            $table->string('file_dokumen')->nullable(); // dokumen pendukung upload
            $table->enum('status', [
                'diajukan',
                'diverifikasi',
                'menunggu_pembayaran',
                'dibayar',
                'diproses',
                'hasil_terbit',
                'selesai',
                'ditolak',
            ])->default('diajukan');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
