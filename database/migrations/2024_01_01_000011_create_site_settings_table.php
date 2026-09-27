<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('hero_badge')->default('Terakreditasi ISO/IEC 17025');
            $table->string('hero_title')->default('Laboratorium Kimia Politeknik Negeri Malang');
            $table->text('hero_subtitle')->default('Layanan pengujian sampel, kalibrasi alat, dan praktikum kimia yang cepat, akurat, dan terpercaya.');

            $table->string('about_title')->default('Tentang Laboratorium Kami');
            $table->text('about_text')->default('Laboratorium Kimia Polinema melayani pengujian sampel air, limbah, dan bahan kimia lainnya untuk civitas akademika maupun industri, didukung alat-alat modern dan tenaga ahli berpengalaman.');

            $table->string('feature_1_icon')->default('beaker');
            $table->string('feature_1_title')->default('Pengujian Sampel');
            $table->string('feature_1_description')->default('Pengujian parameter kimia air & limbah sesuai standar SNI.');

            $table->string('feature_2_icon')->default('wrench');
            $table->string('feature_2_title')->default('Kalibrasi Alat');
            $table->string('feature_2_description')->default('Layanan kalibrasi instrumen laboratorium secara berkala.');

            $table->string('feature_3_icon')->default('calendar');
            $table->string('feature_3_title')->default('Booking Praktikum');
            $table->string('feature_3_description')->default('Jadwalkan sesi praktikum lab dengan mudah secara online.');

            $table->string('feature_4_icon')->default('clipboard-check');
            $table->string('feature_4_title')->default('Sertifikat Digital');
            $table->string('feature_4_description')->default('Unduh sertifikat hasil uji resmi dalam format PDF.');

            $table->string('contact_email')->default('labkimia@polinema.ac.id');
            $table->string('contact_phone')->default('(0341) 404424');
            $table->string('contact_address')->default('Jl. Soekarno Hatta No.9, Malang, Jawa Timur');

            $table->string('footer_text')->default('© ' . date('Y') . ' Laboratorium Kimia Politeknik Negeri Malang.');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
