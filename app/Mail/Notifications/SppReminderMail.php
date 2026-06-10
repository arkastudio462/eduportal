<?php

namespace App\Mail\Notifications;

use App\Models\PembayaranSpp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SppReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public PembayaranSpp $pembayaran;

    public function __construct(PembayaranSpp $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat Pembayaran SPP',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notifications.spp-reminder',
            with: ['pembayaran' => $this->pembayaran],
        );
    }
}
