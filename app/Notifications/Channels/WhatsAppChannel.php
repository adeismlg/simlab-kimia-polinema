<?php

namespace App\Notifications\Channels;

use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Channel WhatsApp sederhana via Fonnte (https://fonnte.com).
 * Ganti implementasi send() ini kalau Anda pakai provider lain
 * (Twilio WhatsApp, Wablas, Qontak, dll) — strukturnya tetap sama.
 */
class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $phone = method_exists($notifiable, 'routeNotificationForWhatsApp')
            ? $notifiable->routeNotificationForWhatsApp($notification)
            : ($notifiable->no_hp ?? null);

        if (! $phone) {
            return; // user tidak punya no HP terdaftar, skip diam-diam
        }

        /** @var WhatsAppMessage $message */
        $message = $notification->toWhatsApp($notifiable);

        $token = config('services.fonnte.token');

        if (! $token) {
            Log::warning('FONNTE_TOKEN belum diset di .env — notifikasi WhatsApp dilewati.', ['phone' => $phone]);

            return;
        }

        try {
            Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->post('https://api.fonnte.com/send', [
                    'target' => $this->normalizePhone($phone),
                    'message' => $message->content,
                ]);
        } catch (\Throwable $e) {
            // Jangan sampai kegagalan WA mengganggu proses utama (verifikasi, dsb)
            Log::error('Gagal mengirim notifikasi WhatsApp: ' . $e->getMessage());
        }
    }

    /**
     * Fonnte & sebagian besar gateway WA Indonesia butuh format 62xxx (tanpa +, tanpa 0 di depan).
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
