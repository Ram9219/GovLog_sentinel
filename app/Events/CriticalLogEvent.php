<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ServerLog;

class CriticalLogEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log;

    /**
     * Create a new event instance.
     */
    public function __construct(ServerLog $log)
    {
        $this->log = $log;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('critical-logs'),
            new Channel('admin-alerts')
        ];
    }

    public function broadcastAs(): string
    {
        return 'critical.log.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->log->id,
            'message' => $this->log->message,
            'severity' => $this->log->severity,
            'classification' => $this->log->classification,
            'timestamp' => $this->log->timestamp->toIso8601String(),
            'user' => $this->log->user?->email,
            'source_ip' => $this->log->source_ip
        ];
    }
}