<?php

namespace App\Mail\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TugasBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tugas $tugas;

    public function __construct(Tugas $tugas)
    {
        $this->tugas = $tugas;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tugas Baru: '.$this->tugas->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notifications.tugas-baru',
            with: ['tugas' => $this->tugas],
        );
    }
}
