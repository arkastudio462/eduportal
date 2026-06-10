<?php

namespace App\Notifications;

use App\Mail\Notifications\SppReminderMail;
use App\Models\PembayaranSpp;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class SppReminderNotification extends Notification
{
    use Queueable;

    public PembayaranSpp $pembayaran;

    public function __construct(PembayaranSpp $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): Mailable
    {
        return (new SppReminderMail($this->pembayaran))->to($notifiable->email);
    }

    public function toDatabase($notifiable): array
    {
        $bulan = Carbon::create()->month((int) $this->pembayaran->bulan)->locale('id')->monthName;

        return [
            'type' => 'spp_reminder',
            'judul' => 'Pengingat Pembayaran SPP',
            'konten' => 'SPP bulan '.$bulan.' '.$this->pembayaran->tahun.' sebesar Rp '.number_format($this->pembayaran->jumlah, 0, ',', '.').' belum dibayar. Segera lakukan pembayaran.',
            'url' => route('portal-siswa.spp'),
        ];
    }
}
