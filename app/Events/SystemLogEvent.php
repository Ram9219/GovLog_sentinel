<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ServerLog;

class SystemLogEvent
{
    use Dispatchable, SerializesModels;

    public $logData;
    public $serverLog;

    /**
     * Create a new event instance.
     */
    public function __construct(array $logData, ?ServerLog $serverLog = null)
    {
        $this->logData = $logData;
        $this->serverLog = $serverLog;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return ['logs'];
    }

    public function broadcastAs(): string
    {
        return 'system.log';
    }
}