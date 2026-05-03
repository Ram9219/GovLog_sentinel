<?php

namespace App\Services;

use App\Models\ServerLog;
use App\Models\NotificationQueue;
use App\Notifications\CriticalLogAlert;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    protected $twilioService;
    protected $whatsappService;

    public function __construct(
        TwilioService $twilioService,
        WhatsAppCloudService $whatsappService
    ) {
        $this->twilioService = $twilioService;
        $this->whatsappService = $whatsappService;
    }

    public function dispatchNotifications(ServerLog $log, array $channels = [])
    {
        if (empty($channels)) {
            $channels = $this->determineChannels($log->severity);
        }

        $results = [];

        foreach ($channels as $channel) {
            $result = $this->sendViaChannel($log, $channel);
            $results[$channel] = $result;

            NotificationQueue::create([
                'log_id' => $log->id,
                'channels' => [$channel],
                'recipients' => $this->getRecipients($log->severity),
                'status' => $result['success'] ? 'sent' : 'failed',
                'error_log' => $result['error'] ?? null,
                'sent_at' => $result['success'] ? now() : null
            ]);
        }

        $log->update([
            'is_notified' => true,
            'notification_results' => $results
        ]);

        return $results;
    }

    protected function determineChannels($severity)
    {
        return match($severity) {
            'emergency' => ['sms', 'whatsapp', 'email', 'pusher'],
            'critical' => ['sms', 'whatsapp', 'pusher'],
            'error' => ['email', 'pusher'],
            default => ['pusher', 'database']
        };
    }

    protected function sendViaChannel(ServerLog $log, $channel)
    {
        $message = $this->prepareMessage($log);

        return match($channel) {
            'sms' => $this->twilioService->sendSms(
                $this->getPrimaryPhone(),
                $message,
                $log->severity === 'emergency'
            ),
            'whatsapp' => $this->whatsappService->sendMessage(
                $this->getPrimaryWhatsApp(),
                $message,
                $log->severity === 'critical'
            ),
            'email' => $this->sendEmail($log, $message),
            'pusher' => $this->sendPusherNotification($log),
            default => ['success' => false, 'error' => 'Unknown channel']
        };
    }

    protected function prepareMessage(ServerLog $log)
    {
        return sprintf(
            "[%s] %s\nAction: %s\nIP: %s\nUser: %s\nTime: %s",
            strtoupper($log->severity),
            $log->message,
            $log->action_type,
            $log->source_ip,
            $log->user->email ?? 'System',
            $log->timestamp->format('Y-m-d H:i:s')
        );
    }

    protected function getPrimaryPhone()
    {
        return config('twilio.alert_phone', '+1234567890');
    }

    protected function getPrimaryWhatsApp()
    {
        return config('whatsapp.alert_number', '+1234567890');
    }

    protected function sendEmail($log, $message)
    {
        try {
            Notification::route('mail', config('mail.alert_email'))
                ->notify(new CriticalLogAlert($log));
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function sendPusherNotification($log)
    {
        try {
            event(new \App\Events\CriticalLogEvent($log));
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function getRecipients($severity)
    {
        return [
            'sms' => [$this->getPrimaryPhone()],
            'whatsapp' => [$this->getPrimaryWhatsApp()],
            'email' => [config('mail.alert_email')]
        ];
    }
}
