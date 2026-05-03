<?php

namespace App\Notifications\Channels;

use App\Services\TwilioService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TwilioSmsChannel
{
    protected TwilioService $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Send the given notification via Twilio SMS.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        // Get the phone number from the notifiable (User model)
        $to = $notifiable->routeNotificationFor('sms')
            ?? $notifiable->phone
            ?? config('twilio.alert_phone');

        if (empty($to)) {
            Log::warning('TwilioSmsChannel: No phone number for notifiable #' . ($notifiable->id ?? 'unknown'));
            return;
        }

        // Get the SMS message from the notification
        if (!method_exists($notification, 'toSms')) {
            Log::warning('TwilioSmsChannel: Notification does not implement toSms()');
            return;
        }

        $message = $notification->toSms($notifiable);

        // Determine if emergency
        $isEmergency = method_exists($notification, 'isEmergency')
            ? $notification->isEmergency()
            : false;

        $result = $this->twilioService->sendSms($to, $message, $isEmergency);

        if (!$result['success']) {
            Log::error('TwilioSmsChannel: SMS failed — ' . ($result['error'] ?? 'unknown error'));
        }
    }
}
