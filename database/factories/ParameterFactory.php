<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ParameterFactory extends Factory
{
    public function definition(): array
    {
        $items = [
            ['nama' => 'pH', 'satuan' => '-', 'metode' => 'SNI 06-6989.11-2004'],
            ['nama' => 'COD (Chemical Oxygen Demand)', 'satuan' => 'mg/L', 'metode' => 'SNI 6989.2:2009'],
            ['nama' => 'BOD (Biological Oxygen Demand)', 'satuan' => 'mg/L', 'metode' => 'SNI 6989.72:2009'],
            ['nama' => 'TSS (Total Suspended Solid)', 'satuan' => 'mg/L', 'metode' => 'SNI 06-6989.3-2004'],
            ['nama' => 'Kadar Logam Berat (Pb)', 'satuan' => 'mg/L', 'metode' => 'SNI 6989.8:2009'],
            ['nama' => 'Kadar Logam Berat (Cd)', 'satuan' => 'mg/L', 'metode' => 'SNI 6989.16:2009'],
            ['nama' => 'Kadar Minyak & Lemak', 'satuan' => 'mg/L', 'metode' => 'SNI 06-6989.10-2004'],
            ['nama' => 'Kadar Klorida', 'satuan' => 'mg/L', 'metode' => 'SNI 06-6989.19-2004'],
            ['nama' => 'Kadar Sulfat', 'satuan' => 'mg/L', 'metode' => 'SNI 6989.20:2009'],
            ['nama' => 'Kekeruhan (Turbidity)', 'satuan' => 'NTU', 'metode' => 'SNI 06-6989.25-2005'],
        ];

        $item = fake()->unique()->randomElement($items);

        return [
            'nama_parameter' => $item['nama'],
            'satuan' => $item['satuan'],
            'metode_uji' => $item['metode'],
            'harga_internal' => fake()->randomElement([25000, 35000, 50000, 75000]),
            'harga_eksternal' => fake()->randomElement([75000, 100000, 150000, 200000]),
            'deskripsi' => 'Pengujian parameter ' . $item['nama'] . ' sesuai standar nasional.',
            'aktif' => true,
        ];
    }
}
