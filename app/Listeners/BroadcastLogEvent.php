<?php

namespace App\Listeners;

use App\Events\SystemLogEvent;
use Illuminate\Support\Facades\Log;

class BroadcastLogEvent
{
    /**
     * Handle the event.
     */
    public function handle(SystemLogEvent $event): void
    {
        // For real-time broadcasting via Pusher
        if ($event->serverLog && in_array($event->serverLog->severity, ['error', 'critical', 'emergency'])) {
            broadcast(new \App\Events\CriticalLogEvent($event->serverLog));
        }
    }
}