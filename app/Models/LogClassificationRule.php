<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogClassificationRule extends Model
{
    protected $table = 'classification_rules';

    protected $fillable = [
        'name', 'classification', 'severity', 'patterns', 
        'conditions', 'priority', 'is_active', 'created_by'
    ];

    protected $casts = [
        'patterns' => 'array',
        'conditions' => 'array',
        'is_active' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>', 5);
    }
}