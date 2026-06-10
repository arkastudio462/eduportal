<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PesanBaruNotification extends Notification
{
    use Queueable;

    public Message $message;

    public Conversation $conversation;

    public function __construct(Message $message, Conversation $conversation)
    {
        $this->message = $message;
        $this->conversation = $conversation;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $sender = $this->message->sender;

        return [
            'type' => 'pesan_baru',
            'conversation_slug' => $this->conversation->slug,
            'subjek' => $this->conversation->subjek,
            'pengirim' => $sender->name,
            'pengirim_id' => $sender->id,
            'body' => $this->message->body,
            'url' => $notifiable->role === 'guru'
                ? route('portal-guru.pesan.show', $this->conversation)
                : route('portal-siswa.pesan.show', $this->conversation),
        ];
    }
}
