<?php

namespace App\Http\Controllers;

use App\Models\PracticumBooking;
use App\Models\PracticumSchedule;
use Illuminate\Support\Facades\Auth;

class PracticumBookingController extends Controller
{
    /**
     * Booking milik user sendiri (atau semua, kalau dosen/admin).
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = $user->hasAnyRole(['dosen', 'admin', 'laboran'])
            ? PracticumBooking::with(['schedule', 'user'])->latest()->paginate(15)
            : PracticumBooking::with('schedule')->where('user_id', $user->id)->latest()->paginate(15);

        return view('practicum-bookings.index', compact('bookings'));
    }

    /**
     * Mahasiswa/dosen booking jadwal praktikum tertentu.
     */
    public function store(PracticumSchedule $schedule)
    {
        $user = Auth::user();

        abort_if($schedule->sisa_kuota <= 0, 422, 'Kuota praktikum sudah penuh.');

        PracticumBooking::firstOrCreate(
            ['schedule_id' => $schedule->id, 'user_id' => $user->id],
            ['status' => 'diajukan']
        );

        return back()->with('success', 'Booking praktikum berhasil diajukan, menunggu persetujuan dosen.');
    }

    /**
     * Dosen/laboran menyetujui atau menolak booking.
     */
    public function updateStatus(PracticumBooking $booking, string $status)
    {
        abort_unless(Auth::user()->hasAnyRole(['dosen', 'laboran', 'admin']), 403);
        abort_unless(in_array($status, ['disetujui', 'ditolak', 'selesai']), 422);

        $booking->update(['status' => $status]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}
