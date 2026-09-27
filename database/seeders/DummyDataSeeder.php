<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\Parameter;
use App\Models\Payment;
use App\Models\PracticumBooking;
use App\Models\PracticumSchedule;
use App\Models\Sample;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        SiteSetting::current(); // pastikan baris konten landing page sudah ada

        // ── USERS ──────────────────────────────────────────────
        $mahasiswa = User::factory()->mahasiswa()->count(6)->create();
        $mahasiswa->each(fn ($u) => $u->assignRole('mahasiswa'));

        $dosen = User::factory()->count(2)->create(['instansi' => 'D4 Teknik Kimia, Polinema']);
        $dosen->each(fn ($u) => $u->assignRole('dosen'));

        $eksternalCompanies = ['PT Petrokimia Gresik', 'PT Semen Indonesia', 'CV Tirta Jaya Malang'];
        $eksternal = collect($eksternalCompanies)->map(
            fn ($company) => User::factory()->eksternal($company)->create()
        );
        $eksternal->each(fn ($u) => $u->assignRole('eksternal'));

        $laboran = User::factory()->count(2)->create(['instansi' => 'Laboratorium Kimia Polinema']);
        $laboran->each(fn ($u) => $u->assignRole('laboran'));

        $kepalaLab = User::factory()->create([
            'name' => 'Dr. Bambang Sutrisno, S.T., M.T.',
            'email' => 'kepalalab@polinema.ac.id',
            'instansi' => 'Laboratorium Kimia Polinema',
        ]);
        $kepalaLab->assignRole('kepala_lab');

        $admin = User::where('email', 'admin@polinema.ac.id')->first();

        $pemohonPool = $mahasiswa->concat($dosen)->concat($eksternal);

        // ── PARAMETERS ─────────────────────────────────────────
        $parameters = Parameter::factory()->count(8)->create();

        // ── INSTRUMENTS + CALIBRATIONS ─────────────────────────
        $instruments = Instrument::factory()->count(8)->create();

        $instruments->each(function ($instrument, $i) use ($laboran) {
            // Riwayat kalibrasi 1 tahun lalu
            $instrument->calibrations()->create([
                'tanggal_kalibrasi' => now()->subMonths(11),
                'tanggal_jatuh_tempo' => now()->subMonths(11)->addYear(),
                'vendor_kalibrasi' => fake()->randomElement(['PT Kalibrasi Nusantara', 'Balai Metrologi Malang', 'PT Sucofindo']),
                'status' => 'selesai',
                'catatan' => 'Kalibrasi rutin tahunan.',
            ]);

            // Beberapa alat sengaja dibuat mendekati/lewat jatuh tempo untuk demo dashboard
            if ($i < 3) {
                $daysFromNow = [-5, 12, 25][$i]; // -5 = sudah lewat, sisanya mendekati 30 hari
                $instrument->calibrations()->create([
                    'tanggal_kalibrasi' => now()->subMonths(13),
                    'tanggal_jatuh_tempo' => now()->addDays($daysFromNow),
                    'vendor_kalibrasi' => 'PT Kalibrasi Nusantara',
                    'status' => 'terjadwal',
                    'catatan' => 'Kalibrasi berikutnya — mohon dijadwalkan ulang.',
                ]);
            }
        });

        // ── SAMPLES (menyebar di semua status) ──────────────────
        $statusDistribution = [
            'diajukan' => 4,
            'diverifikasi' => 2,
            'menunggu_pembayaran' => 3,
            'dibayar' => 3,
            'diproses' => 3,
            'hasil_terbit' => 3,
            'selesai' => 5,
            'ditolak' => 1,
        ];

        $sampleNames = [
            'Air Limbah Industri', 'Air Sungai Brantas', 'Limbah Cair Pabrik Tekstil',
            'Air Sumur Warga', 'Limbah B3 Cair', 'Air Baku PDAM', 'Air Kolam Renang',
            'Limbah Cair Rumah Sakit', 'Air Irigasi Sawah', 'Limbah Cair Laundry',
            'Air Danau Buatan', 'Effluent IPAL', 'Air Hujan Asam', 'Limbah Cair Peternakan',
            'Air Tanah Dangkal', 'Sampel Tanah Tercemar', 'Air Payau Tambak', 'Limbah Cair Percetakan',
            'Air Sumber Mata Air', 'Limbah Cair Pertambangan', 'Sampel Kontrol Mutu',
        ];

        $counter = 0;
        $kodeSequence = 1;

        foreach ($statusDistribution as $status => $jumlah) {
            for ($i = 0; $i < $jumlah; $i++) {
                $user = $pemohonPool->random();
                $createdAt = now()->subDays(random_int(1, 60));

                $sample = Sample::create([
                    'kode_sampel' => sprintf('SMP-%s-%s', now()->format('Y'), str_pad((string) $kodeSequence++, 4, '0', STR_PAD_LEFT)),
                    'user_id' => $user->id,
                    'nama_sampel' => $sampleNames[$counter % count($sampleNames)],
                    'deskripsi' => 'Sampel dikirim untuk pengujian rutin kualitas air/limbah.',
                    'status' => $status,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $counter++;

                // Attach 2-4 parameter acak dengan snapshot harga
                $chosenParams = $parameters->random(random_int(2, 4));
                $syncData = [];
                foreach ($chosenParams as $param) {
                    $syncData[$param->id] = ['harga_saat_daftar' => $param->hargaUntuk($user)];
                }
                $sample->parameters()->sync($syncData);

                if ($status === 'ditolak') {
                    $sample->update(['catatan' => 'Volume sampel tidak mencukupi untuk seluruh parameter yang diminta.']);
                    continue;
                }

                if (in_array($status, ['diverifikasi', 'menunggu_pembayaran', 'dibayar', 'diproses', 'hasil_terbit', 'selesai'])) {
                    $sample->update(['verified_by' => $laboran->random()->id, 'verified_at' => $createdAt->copy()->addHours(3)]);
                }

                if (in_array($status, ['menunggu_pembayaran', 'dibayar', 'diproses', 'hasil_terbit', 'selesai'])) {
                    $totalBiaya = collect($syncData)->sum('harga_saat_daftar');
                    $totalHitung = Payment::hitungTotal($totalBiaya, $user->tipe);

                    $sample->payment()->create([
                        'jumlah' => $totalHitung['jumlah'],
                        'ppn' => $totalHitung['ppn'],
                        'total' => $totalHitung['total'],
                        'status' => $status === 'menunggu_pembayaran' ? 'menunggu_verifikasi' : 'lunas',
                        'metode' => fake()->randomElement(['transfer_manual', 'gateway']),
                        'verified_by' => $status === 'menunggu_pembayaran' ? null : $laboran->random()->id,
                        'verified_at' => $status === 'menunggu_pembayaran' ? null : $createdAt->copy()->addDay(),
                    ]);
                }

                if (in_array($status, ['diproses', 'hasil_terbit', 'selesai'])) {
                    foreach ($chosenParams as $param) {
                        $sample->parameters()->updateExistingPivot($param->id, [
                            'hasil' => in_array($status, ['hasil_terbit', 'selesai']) ? (string) random_int(1, 100) : null,
                            'satuan_hasil' => $param->satuan,
                        ]);
                    }
                }

                if (in_array($status, ['hasil_terbit', 'selesai'])) {
                    $sample->testResult()->create([
                        'status_approval' => $status === 'selesai' ? 'disetujui' : 'menunggu',
                        'approved_by' => $status === 'selesai' ? $kepalaLab->id : null,
                        'approved_at' => $status === 'selesai' ? $createdAt->copy()->addDays(2) : null,
                    ]);
                }
            }
        }

        // ── PRACTICUM SCHEDULES + BOOKINGS ─────────────────────
        $praktikumNames = [
            'Analisis Kadar Asam Basa', 'Titrasi Redoks', 'Spektrofotometri UV-Vis',
            'Analisis Gravimetri', 'Kromatografi Lapis Tipis',
        ];

        foreach ($praktikumNames as $i => $nama) {
            $schedule = PracticumSchedule::create([
                'nama_praktikum' => $nama,
                'dosen_id' => $dosen->random()->id,
                'instrument_id' => $instruments->random()->id,
                'tanggal' => now()->addDays($i * 3 - 5),
                'jam_mulai' => '08:00',
                'jam_selesai' => '11:00',
                'kapasitas' => 15,
                'catatan' => 'Wajib membawa jas lab dan alat pelindung diri.',
            ]);

            foreach ($mahasiswa->random(min(4, $mahasiswa->count())) as $mhs) {
                PracticumBooking::create([
                    'schedule_id' => $schedule->id,
                    'user_id' => $mhs->id,
                    'status' => fake()->randomElement(['diajukan', 'disetujui', 'disetujui', 'ditolak']),
                ]);
            }
        }

        $this->command->info('Dummy data berhasil dibuat: ' . $pemohonPool->count() . ' pemohon, ' .
            $parameters->count() . ' parameter, ' . $instruments->count() . ' alat, ' .
            Sample::count() . ' sampel, ' . PracticumSchedule::count() . ' jadwal praktikum.');
    }
}
