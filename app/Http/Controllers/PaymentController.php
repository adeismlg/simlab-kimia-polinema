<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Notifications\SampleStatusUpdated;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        $user = Auth::user();
        $owner = $payment->payable->user_id ?? null;

        abort_unless($owner === $user->id || $user->hasAnyRole(['laboran', 'admin']), 403);

        $midtransClientKey = config('midtrans.client_key');
        $midtransIsProduction = config('midtrans.is_production');

        return view('payments.show', compact('payment', 'midtransClientKey', 'midtransIsProduction'));
    }

    /**
     * User upload bukti transfer manual (alternatif dari bayar via Midtrans).
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'bukti_bayar' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $path = $request->file('bukti_bayar')->store('payments/bukti', 'public');

        $payment->update([
            'bukti_bayar' => $path,
            'metode' => 'transfer_manual',
            'status' => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi laboran.');
    }

    /**
     * Generate Snap token untuk dibuka di popup Midtrans (dipanggil via fetch dari halaman payments.show).
     */
    public function snapToken(Payment $payment, MidtransService $midtrans)
    {
        $user = Auth::user();
        abort_unless(($payment->payable->user_id ?? null) === $user->id, 403);

        try {
            $token = $midtrans->createSnapToken($payment);

            return response()->json(['snap_token' => $token]);
        } catch (\Throwable $e) {
            Log::error('Gagal membuat Snap token Midtrans: ' . $e->getMessage());

            return response()->json(['message' => 'Gagal menghubungi payment gateway. Coba lagi nanti.'], 500);
        }
    }

    /**
     * Webhook notification dari Midtrans (server-to-server, tanpa auth session).
     * Daftarkan URL ini di dashboard Midtrans: Settings > Configuration > Notification URL.
     */
    public function midtransCallback(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        if (! $midtrans->verifySignature($payload)) {
            Log::warning('Signature Midtrans tidak valid.', $payload);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // order_id formatnya: SIMLAB-{payment_id}-{timestamp}
        $paymentId = (int) explode('-', $payload['order_id'])[1] ?? null;
        $payment = Payment::find($paymentId);

        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (in_array($transactionStatus, ['capture', 'settlement']) && $fraudStatus !== 'deny') {
            $payment->update(['metode' => 'gateway', 'status' => 'lunas', 'verified_at' => now()]);

            $payable = $payment->payable;
            if ($payable) {
                $payable->update(['status' => class_basename($payable) === 'Sample' ? 'dibayar' : 'disetujui']);

                if ($payable instanceof \App\Models\Sample) {
                    $payable->user->notify(new SampleStatusUpdated($payable));
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $payment->update(['status' => 'ditolak']);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Laboran/admin verifikasi pembayaran manual -> update status sample/booking terkait.
     */
    public function verify(Payment $payment)
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin']), 403);

        $payment->update([
            'status' => 'lunas',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $payable = $payment->payable;
        if ($payable) {
            $payable->update(['status' => class_basename($payable) === 'Sample' ? 'dibayar' : 'disetujui']);

            if ($payable instanceof \App\Models\Sample) {
                $payable->user->notify(new SampleStatusUpdated($payable));
            }
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
