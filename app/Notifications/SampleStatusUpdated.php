<?php

namespace App\Notifications;

use App\Models\Sample;
use App\Notifications\Channels\WhatsAppChannel;
use App\Notifications\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SampleStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private static array $labels = [
        'diajukan' => 'diajukan dan menunggu verifikasi',
        'diverifikasi' => 'diverifikasi oleh laboran',
        'menunggu_pembayaran' => 'menunggu pembayaran',
        'dibayar' => 'lunas dibayar, sampel akan segera diproses',
        'diproses' => 'sedang diproses di laboratorium',
        'hasil_terbit' => 'hasil uji sudah terbit, menunggu approval kepala lab',
        'selesai' => 'selesai — sertifikat hasil uji dapat diunduh',
        'ditolak' => 'ditolak',
    ];

    public function __construct(private Sample $sample)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = self::$labels[$this->sample->status] ?? $this->sample->status;

        return (new MailMessage())
            ->subject("Update Status Sampel {$this->sample->kode_sampel}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Status sampel uji Anda dengan kode **{$this->sample->kode_sampel}** ({$this->sample->nama_sampel}) telah berubah menjadi:")
            ->line("**" . ucwords(str_replace('_', ' ', $this->sample->status)) . "** — {$label}.")
            ->action('Lihat Detail Sampel', route('samples.show', $this->sample))
            ->line('Terima kasih telah menggunakan layanan Laboratorium Kimia Polinema.');
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        $label = self::$labels[$this->sample->status] ?? $this->sample->status;

        return WhatsAppMessage::create(
            "Halo {$notifiable->name}, status sampel *{$this->sample->kode_sampel}* ({$this->sample->nama_sampel}) kini: *" .
            ucwords(str_replace('_', ' ', $this->sample->status)) . "* — {$label}.\n\n" .
            "Cek detail: " . route('samples.show', $this->sample)
        );
    }
}
