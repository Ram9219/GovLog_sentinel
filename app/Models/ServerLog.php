<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServerLog extends Model
{
    protected $table = 'server_logs';
    
    protected $fillable = [
        'log_entry_id', 
        'timestamp', 
        'source_ip', 
        'user_id', 
        'action_type',
        'severity', 
        'classification', 
        'message', 
        'context', 
        'metadata',
        'hash',
        'previous_hash', 
        'request_id', 
        'is_notified'
    ];
    
    protected $casts = [
        'timestamp' => 'datetime',
        'context' => 'array',
        'metadata' => 'array',
        'is_notified' => 'boolean'
    ];
    
    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Scope for critical logs
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }
    
    // Scope for date range
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('timestamp', [$start, $end]);
    }
}