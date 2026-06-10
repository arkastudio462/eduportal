<?php

namespace App\Notifications;

use App\Channels\FonnteMessage;
use App\Mail\Notifications\PengumumanMail;
use App\Models\Pengumuman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class PengumumanNotification extends Notification
{
    use Queueable;

    public $judul;

    public $konten;

    public $pengumumanId;

    public function __construct($judul, $konten, $pengumumanId)
    {
        $this->judul = $judul;
        $this->konten = $konten;
        $this->pengumumanId = $pengumumanId;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];
        if ($this->pengumumanId) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): Mailable
    {
        return (new PengumumanMail(Pengumuman::find($this->pengumumanId)))->to($notifiable->email);
    }

    public function toFonnte($notifiable): FonnteMessage
    {
        $message = '📢 *Pengumuman*'."\n"
            .$this->judul."\n"
            ."\n".strip_tags(substr($this->konten, 0, 200))."\n"
            ."\nCek portal untuk detail.";

        return FonnteMessage::create($message)
            ->to($notifiable->routeNotificationFor('fonnte'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'pengumuman',
            'judul' => $this->judul,
            'konten' => $this->konten,
            'pengumuman_id' => $this->pengumumanId,
            'url' => route('admin.pengumuman'),
        ];
    }
}
