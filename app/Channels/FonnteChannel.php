<?php

namespace App\Channels;

use App\Services\FonnteService;
use Illuminate\Notifications\Notification;

class FonnteChannel
{
    protected FonnteService $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toFonnte($notifiable);

        $target = $message->target ?? $notiable->routeNotificationFor('fonnte');

        if (! $target) {
            return;
        }

        $this->fonnte->send($target, $message->content ?? $message);
    }
}
