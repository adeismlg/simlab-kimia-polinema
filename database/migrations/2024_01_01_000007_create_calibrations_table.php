<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calibrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_kalibrasi');
            $table->date('tanggal_jatuh_tempo'); // kapan harus kalibrasi ulang
            $table->string('file_sertifikat_kalibrasi')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'terlambat'])->default('terjadwal');
            $table->string('vendor_kalibrasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calibrations');
    }
};
