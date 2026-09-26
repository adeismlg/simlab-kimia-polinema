<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // tipe: internal (mahasiswa/dosen) atau eksternal (industri/umum)
            $table->enum('tipe', ['internal', 'eksternal'])->default('internal')->after('email');
            $table->string('instansi')->nullable()->after('tipe'); // asal instansi/jurusan
            $table->string('no_hp')->nullable()->after('instansi');
            $table->string('nim_nip')->nullable()->after('no_hp'); // NIM/NIP untuk internal
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'instansi', 'no_hp', 'nim_nip']);
        });
    }
};
