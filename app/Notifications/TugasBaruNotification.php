<?php

namespace App\Notifications;

use App\Channels\FonnteMessage;
use App\Mail\Notifications\TugasBaruMail;
use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class TugasBaruNotification extends Notification
{
    use Queueable;

    public $judul;

    public $tugasId;

    public $mapel;

    public function __construct($judul, $tugasId, $mapel)
    {
        $this->judul = $judul;
        $this->tugasId = $tugasId;
        $this->mapel = $mapel;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->email) {
            $channels[] = 'mail';
        }

        if ($notifiable->routeNotificationFor('fonnte')) {
            $channels[] = 'fonnte';
        }

        return $channels;
    }

    public function toMail($notifiable): Mailable
    {
        return (new TugasBaruMail(Tugas::find($this->tugasId)))->to($notifiable->email);
    }

    public function toFonnte($notifiable): FonnteMessage
    {
        $message = '🔔 *Tugas Baru!*'."\n"
            .'Judul: '.$this->judul."\n"
            .'Mapel: '.$this->mapel."\n"
            .'Cek di portal siswa untuk detail.';

        return FonnteMessage::create($message)
            ->to($notifiable->routeNotificationFor('fonnte'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'tugas',
            'judul' => $this->judul,
            'mapel' => $this->mapel,
            'tugas_id' => $this->tugasId,
            'url' => route('portal-siswa.tugas'),
        ];
    }
}
