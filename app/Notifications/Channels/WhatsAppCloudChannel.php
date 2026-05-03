<?php

namespace App\Notifications\Channels;

use App\Services\WhatsAppCloudService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WhatsAppCloudChannel
{
    protected WhatsAppCloudService $whatsAppService;

    public function __construct(WhatsAppCloudService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send the given notification via WhatsApp Cloud API.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('whatsapp')
            ?? $notifiable->phone
            ?? config('whatsapp.alert_number');

        if (empty($to)) {
            Log::warning('WhatsAppCloudChannel: No phone number for notifiable #' . ($notifiable->id ?? 'unknown'));
            return;
        }

        if (!method_exists($notification, 'toWhatsApp')) {
            Log::warning('WhatsAppCloudChannel: Notification does not implement toWhatsApp()');
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $isCritical = method_exists($notification, 'isEmergency') && $notification->isEmergency();

        $result = $this->whatsAppService->sendMessage($to, $message, $isCritical);

        if (!$result['success']) {
            Log::error('WhatsAppCloudChannel: Message failed — ' . ($result['error'] ?? 'unknown error'));
        }
    }
}
