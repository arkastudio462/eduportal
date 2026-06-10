<?php

namespace App\Notifications;

use App\Channels\FonnteMessage;
use App\Mail\Notifications\TugasSubmissionMail;
use App\Models\TugasSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class TugasSubmissionNotification extends Notification
{
    use Queueable;

    public $judulTugas;

    public $namaSiswa;

    public $tugasId;

    public $submission;

    public function __construct($judulTugas, $namaSiswa, $tugasId, ?TugasSubmission $submission = null)
    {
        $this->judulTugas = $judulTugas;
        $this->namaSiswa = $namaSiswa;
        $this->tugasId = $tugasId;
        $this->submission = $submission;
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
        return (new TugasSubmissionMail($this->submission, $this->judulTugas, $this->namaSiswa))
            ->to($notifiable->email);
    }

    public function toFonnte($notifiable): FonnteMessage
    {
        $message = '📝 *Tugas Dikumpulkan*'."\n"
            .'Siswa: '.$this->namaSiswa."\n"
            .'Tugas: '.$this->judulTugas;

        return FonnteMessage::create($message)
            ->to($notifiable->routeNotificationFor('fonnte'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'tugas_submission',
            'judul' => $this->judulTugas,
            'siswa' => $this->namaSiswa,
            'tugas_id' => $this->tugasId,
            'url' => route('portal-guru.tugas.submissions', $this->tugasId),
        ];
    }
}
