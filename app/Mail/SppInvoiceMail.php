<?php

namespace App\Mail;

use App\Models\PembayaranSpp;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SppInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public PembayaranSpp $pembayaran;

    public function __construct(PembayaranSpp $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function envelope(): Envelope
    {
        $bulan = Carbon::create()->month((int) $this->pembayaran->bulan)->locale('id')->monthName;

        return new Envelope(
            subject: 'Invoice Pembayaran SPP - '.$bulan.' '.$this->pembayaran->tahun,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.spp-invoice',
            with: [
                'pembayaran' => $this->pembayaran,
                'bulan' => Carbon::create()->month((int) $this->pembayaran->bulan)->locale('id')->monthName,
            ],
        );
    }

    public function attachments(): array
    {
        $bulan = Carbon::create()->month((int) $this->pembayaran->bulan)->locale('id')->monthName;
        $this->pembayaran->load('siswa.user', 'siswa.kelas');

        $pdf = Pdf::loadView('pdf.invoice-spp', ['pembayaran' => $this->pembayaran]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Invoice_SPP_'.$bulan.'_'.$this->pembayaran->tahun.'.pdf'
            ),
        ];
    }
}
