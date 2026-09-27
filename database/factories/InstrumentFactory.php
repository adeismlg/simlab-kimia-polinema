<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentFactory extends Factory
{
    private static int $counter = 0;

    public function definition(): array
    {
        $alatList = [
            ['nama' => 'Spektrofotometer UV-Vis', 'merk' => 'Shimadzu UV-1800'],
            ['nama' => 'pH Meter Digital', 'merk' => 'Hanna HI2211'],
            ['nama' => 'Oven Laboratorium', 'merk' => 'Memmert UN55'],
            ['nama' => 'Neraca Analitik', 'merk' => 'Ohaus Pioneer PA224'],
            ['nama' => 'COD Reactor', 'merk' => 'Lovibond ET 108'],
            ['nama' => 'Furnace / Tanur', 'merk' => 'Nabertherm L9/11'],
            ['nama' => 'Centrifuge', 'merk' => 'Hettich EBA 200'],
            ['nama' => 'Water Bath', 'merk' => 'Memmert WNB14'],
            ['nama' => 'Incubator BOD', 'merk' => 'Binder KB115'],
            ['nama' => 'Turbidimeter', 'merk' => 'HACH 2100Q'],
        ];

        $item = fake()->unique()->randomElement($alatList);
        self::$counter++;

        return [
            'kode_alat' => 'ALT-' . str_pad((string) self::$counter, 3, '0', STR_PAD_LEFT),
            'nama_alat' => $item['nama'],
            'merk' => $item['merk'],
            'lokasi' => fake()->randomElement(['Lab Kimia Analitik', 'Lab Kimia Dasar', 'Lab Instrumentasi', 'Lab Lingkungan']),
            'status' => fake()->randomElement(['tersedia', 'tersedia', 'tersedia', 'digunakan', 'maintenance']),
        ];
    }
}
