<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samples', function (Blueprint $table) {
            // lokasi fisik penyimpanan sampel (rak/lemari) agar mudah dicari kembali
            $table->string('lokasi_penyimpanan')->nullable()->after('catatan');
            // dicatat setiap kali label dicetak, untuk audit trail siapa/kapan label terakhir dicetak
            $table->timestamp('label_dicetak_at')->nullable()->after('lokasi_penyimpanan');
        });
    }

    public function down(): void
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn(['lokasi_penyimpanan', 'label_dicetak_at']);
        });
    }
};
