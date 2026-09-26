<?php

namespace App\Console\Commands;

use App\Models\Calibration;
use Illuminate\Console\Command;

class CheckCalibrationDue extends Command
{
    protected $signature = 'simlab:check-calibration';

    protected $description = 'Cek kalibrasi alat yang mendekati/lewat jatuh tempo dan update statusnya';

    public function handle(): void
    {
        // tandai yang sudah lewat jatuh tempo
        $terlambat = Calibration::where('tanggal_jatuh_tempo', '<', now())
            ->where('status', '!=', 'terlambat')
            ->update(['status' => 'terlambat']);

        $this->info("Ditandai terlambat: {$terlambat} kalibrasi.");

        // di sini bisa ditambahkan notifikasi email/Slack ke laboran
        $akanJatuhTempo = Calibration::with('instrument')
            ->whereBetween('tanggal_jatuh_tempo', [now(), now()->addDays(30)])
            ->where('status', '!=', 'selesai')
            ->get();

        foreach ($akanJatuhTempo as $kalibrasi) {
            $this->line("Perlu perhatian: {$kalibrasi->instrument->nama_alat} - jatuh tempo {$kalibrasi->tanggal_jatuh_tempo->format('d-m-Y')}");
            // TODO: kirim notifikasi, misal: Notification::send($laboran, new CalibrationDueNotification($kalibrasi));
        }
    }
}
