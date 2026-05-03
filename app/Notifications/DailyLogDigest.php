<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DailyLogDigest extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }
}
