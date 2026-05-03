<?php

namespace App\Notifications;

use App\Models\ServerLog;
use App\Notifications\Channels\TwilioSmsChannel;
use App\Notifications\Channels\WhatsAppCloudChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CriticalLogAlert extends Notification
{
    use Queueable;

    protected ServerLog $log;

    public function __construct(ServerLog $log)
    {
        $this->log = $log;
    }

    /**
     * Determine which channels to use based on severity
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];

        // Add SMS for critical/emergency if Twilio is configured
        if (
            in_array($this->log->severity, ['critical', 'emergency']) &&
            !empty(config('twilio.sid')) &&
            !str_contains(config('twilio.sid'), 'your_')
        ) {
            $channels[] = TwilioSmsChannel::class;
        }

        return $channels;
    }

    /**
     * Email notification
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🚨 CRITICAL ALERT - GovLog Sentinel')
            ->greeting('⚠️ Critical Event Detected!')
            ->line('A critical/emergency log event has been recorded in GovLog Sentinel.')
            ->line('**Severity:** ' . strtoupper($this->log->severity))
            ->line('**Action:** ' . $this->log->action_type)
            ->line('**Message:** ' . $this->log->message)
            ->line('**Source IP:** ' . $this->log->source_ip)
            ->line('**Classification:** ' . $this->log->classification)
            ->line('**Timestamp:** ' . $this->log->created_at->format('Y-m-d H:i:s'))
            ->action('View Log Details', url('/admin/logs/' . $this->log->id))
            ->line('Please investigate this event immediately.')
            ->salutation('— GovLog Sentinel Monitoring System');
    }

    /**
     * SMS notification via Twilio
     */
    public function toSms(object $notifiable): string
    {
        return sprintf(
            "[%s] %s\nAction: %s\nIP: %s\nTime: %s",
            strtoupper($this->log->severity),
            substr($this->log->message, 0, 120),
            $this->log->action_type,
            $this->log->source_ip,
            $this->log->created_at->format('Y-m-d H:i:s')
        );
    }

    /**
     * WhatsApp notification
     */
    public function toWhatsApp(object $notifiable): string
    {
        return sprintf(
            "🚨 *%s ALERT*\n\n*Action:* %s\n*Message:* %s\n*IP:* %s\n*Classification:* %s\n*Time:* %s",
            strtoupper($this->log->severity),
            $this->log->action_type,
            $this->log->message,
            $this->log->source_ip,
            $this->log->classification,
            $this->log->created_at->format('Y-m-d H:i:s')
        );
    }

    /**
     * Check if this is an emergency-level alert
     */
    public function isEmergency(): bool
    {
        return $this->log->severity === 'emergency';
    }

    /**
     * Database notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'log_id' => $this->log->id,
            'severity' => $this->log->severity,
            'action_type' => $this->log->action_type,
            'message' => $this->log->message,
            'source_ip' => $this->log->source_ip,
            'classification' => $this->log->classification,
            'created_at' => $this->log->created_at->toIso8601String(),
        ];
    }
}
