<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class TwilioSmsChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        // Placeholder for Twilio SMS delivery.
    }
}
