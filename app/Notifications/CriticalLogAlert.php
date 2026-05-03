<?php

namespace App\Notifications;

use App\Models\ServerLog;
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

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

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
