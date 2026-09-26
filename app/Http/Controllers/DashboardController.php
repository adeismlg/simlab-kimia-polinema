<?php

namespace App\Http\Controllers;

use App\Models\Calibration;
use App\Models\Payment;
use App\Models\PracticumBooking;
use App\Models\Sample;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['laboran', 'admin', 'kepala_lab'])) {
            $data = [
                'total_sampel_diajukan' => Sample::where('status', 'diajukan')->count(),
                'total_menunggu_pembayaran' => Sample::where('status', 'menunggu_pembayaran')->count(),
                'total_diproses' => Sample::where('status', 'diproses')->count(),
                'total_selesai_bulan_ini' => Sample::where('status', 'selesai')
                    ->whereMonth('updated_at', now()->month)->count(),
                'pembayaran_menunggu_verifikasi' => Payment::where('status', 'menunggu_verifikasi')->count(),
                'kalibrasi_akan_jatuh_tempo' => Calibration::where('tanggal_jatuh_tempo', '<=', now()->addDays(30))
                    ->where('status', '!=', 'selesai')->count(),
                'booking_praktikum_diajukan' => PracticumBooking::where('status', 'diajukan')->count(),
            ];

            return view('dashboard-staff', compact('data'));
        }

        // dashboard untuk mahasiswa/dosen/eksternal
        $samples = Sample::where('user_id', $user->id)->latest()->limit(5)->get();
        $bookings = PracticumBooking::with('schedule')->where('user_id', $user->id)->latest()->limit(5)->get();

        return view('dashboard', compact('samples', 'bookings'));
    }
}
