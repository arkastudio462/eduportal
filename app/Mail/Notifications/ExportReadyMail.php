<?php

namespace App\Mail\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExportReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $type;

    public string $path;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Export '.ucfirst($this->type).' Siap Diunduh',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notifications.export-ready',
            with: [
                'type' => $this->type,
                'url' => url('storage/'.$this->path),
            ],
        );
    }
}
