<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationQueue extends Model
{
    protected $table = 'notification_queues';

    protected $fillable = [
        'log_id', 'channels', 'recipients', 'status', 
        'retry_count', 'error_log', 'sent_at'
    ];

    protected $casts = [
        'channels' => 'array',
        'recipients' => 'array',
        'error_log' => 'array',
        'sent_at' => 'datetime'
    ];

    public function log()
    {
        return $this->belongsTo(ServerLog::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                     ->where('retry_count', '<', 3);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed')
                     ->where('retry_count', '>=', 3);
    }
}