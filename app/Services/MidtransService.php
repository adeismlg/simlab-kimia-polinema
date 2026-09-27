<?php

namespace App\Services;

use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Buat Snap token untuk satu tagihan Payment.
     * order_id HARUS unik per percobaan bayar, jadi kita sisipkan timestamp.
     */
    public function createSnapToken(Payment $payment): string
    {
        $payable = $payment->payable;
        $user = $payable->user;

        $params = [
            'transaction_details' => [
                'order_id' => 'SIMLAB-' . $payment->id . '-' . now()->timestamp,
                'gross_amount' => (int) round($payment->total),
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp,
            ],
            'item_details' => [[
                'id' => 'payment-' . $payment->id,
                'price' => (int) round($payment->total),
                'quantity' => 1,
                'name' => class_basename($payable) === 'Sample'
                    ? "Pengujian Sampel {$payable->kode_sampel}"
                    : "Praktikum {$payable->schedule->nama_praktikum}",
            ]],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Verifikasi signature notifikasi webhook Midtrans.
     * Wajib dipanggil sebelum mempercayai payload dari callback.
     */
    public function verifySignature(array $payload): bool
    {
        $signature = hash('sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.server_key')
        );

        return $signature === ($payload['signature_key'] ?? null);
    }
}
