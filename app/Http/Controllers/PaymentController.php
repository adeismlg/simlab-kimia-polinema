<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        $user = Auth::user();
        $owner = $payment->payable->user_id ?? null;

        abort_unless($owner === $user->id || $user->hasAnyRole(['laboran', 'admin']), 403);

        return view('payments.show', compact('payment'));
    }

    /**
     * User upload bukti transfer manual.
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'bukti_bayar' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $path = $request->file('bukti_bayar')->store('payments/bukti', 'public');

        $payment->update([
            'bukti_bayar' => $path,
            'status' => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi laboran.');
    }

    /**
     * Laboran/admin verifikasi pembayaran -> update status sample/booking terkait.
     */
    public function verify(Payment $payment)
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin']), 403);

        $payment->update([
            'status' => 'lunas',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // update status entitas terkait (sample atau practicum booking)
        $payable = $payment->payable;
        if ($payable && property_exists($payable, 'status')) {
            $payable->update(['status' => $payable instanceof \App\Models\Sample ? 'dibayar' : 'disetujui']);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Payment $payment)
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin']), 403);

        $payment->update(['status' => 'ditolak']);

        return back()->with('error', 'Pembayaran ditolak. Silakan minta pengguna mengunggah ulang bukti bayar.');
    }
}
