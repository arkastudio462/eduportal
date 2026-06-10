<?php

namespace App\Mail\Notifications;

use App\Models\TugasSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TugasSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public TugasSubmission $submission;

    public string $judulTugas;

    public string $namaSiswa;

    public function __construct(TugasSubmission $submission, string $judulTugas, string $namaSiswa)
    {
        $this->submission = $submission;
        $this->judulTugas = $judulTugas;
        $this->namaSiswa = $namaSiswa;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tugas Dikumpulkan: '.$this->judulTugas.' - '.$this->namaSiswa,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notifications.tugas-submission',
            with: [
                'judulTugas' => $this->judulTugas,
                'namaSiswa' => $this->namaSiswa,
            ],
        );
    }
}
