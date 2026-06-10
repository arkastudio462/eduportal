<?php

namespace App\Notifications;

use App\Mail\Notifications\ExportReadyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification
{
    use Queueable;

    public string $type;

    public string $path;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
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
        return (new ExportReadyMail($this->type, $this->path))->to($notifiable->email);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'export',
            'judul' => 'Export '.ucfirst($this->type).' Siap',
            'konten' => 'File export data '.$this->type.' telah siap diunduh.',
            'url' => url('storage/'.$this->path),
        ];
    }
}
