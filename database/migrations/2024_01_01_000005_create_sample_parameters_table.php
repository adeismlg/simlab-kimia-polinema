<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sample_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parameter_id')->constrained()->cascadeOnDelete();
            $table->string('hasil')->nullable();        // diisi laboran setelah uji selesai
            $table->string('satuan_hasil')->nullable();
            $table->decimal('harga_saat_daftar', 12, 2)->default(0); // snapshot harga saat pendaftaran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_parameters');
    }
};
