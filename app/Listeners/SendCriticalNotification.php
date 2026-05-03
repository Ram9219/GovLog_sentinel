<?php

namespace App\Listeners;

use App\Events\CriticalLogEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCriticalNotification implements ShouldQueue
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(CriticalLogEvent $event): void
    {
        // Send via all available channels for critical alerts
        $this->notificationService->dispatchNotifications(
            $event->log,
            ['whatsapp', 'sms', 'email', 'pusher']
        );
    }
}