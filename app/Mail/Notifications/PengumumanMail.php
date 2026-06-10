<?php

namespace App\Mail\Notifications;

use App\Models\Pengumuman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengumumanMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pengumuman $pengumuman;

    public function __construct(Pengumuman $pengumuman)
    {
        $this->pengumuman = $pengumuman;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengumuman: '.$this->pengumuman->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notifications.pengumuman',
            with: ['pengumuman' => $this->pengumuman],
        );
    }
}
