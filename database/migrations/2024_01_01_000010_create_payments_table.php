<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // polymorphic: bisa terhubung ke sample ATAU practicum_booking
            $table->morphs('payable'); // payable_id, payable_type
            $table->decimal('jumlah', 12, 2);
            $table->decimal('ppn', 12, 2)->default(0); // hanya diisi untuk pelanggan eksternal
            $table->decimal('total', 12, 2);
            $table->enum('metode', ['transfer_manual', 'gateway'])->default('transfer_manual');
            $table->enum('status', ['menunggu_verifikasi', 'lunas', 'ditolak'])->default('menunggu_verifikasi');
            $table->string('bukti_bayar')->nullable(); // path file bukti transfer
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
