<?php

namespace App\Services;

use App\Models\ServerLog;
use App\Services\ClassificationService;
use App\Notifications\CriticalLogAlert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class LogCreationService
{
    protected $integrityService;
    protected $classificationService;

    public function __construct(LogIntegrityService $integrityService, ClassificationService $classificationService)
    {
        $this->integrityService = $integrityService;
        $this->classificationService = $classificationService;
    }

    /**
     * Create a new log entry with auto-classification and integrity hashing
     */
    public function create(array $data): ServerLog
    {
        // Auto-classify if not explicitly provided or is generic
        if (!isset($data['severity']) || !isset($data['classification']) || $data['classification'] === 'general') {
            $classified = $this->classificationService->classify(
                $data['action_type'] ?? 'unknown',
                $data['message'] ?? ''
            );
            $data['severity'] = $data['severity'] ?? $classified['severity'];
            $data['classification'] = $classified['classification'];
        }

        // Get the last log for hash chaining
        $lastLog = ServerLog::latest('id')->first();
        $previousHash = $lastLog ? $lastLog->hash : null;

        // Prepare log data
        $logData = [
            'log_entry_id' => $data['log_entry_id'] ?? Str::uuid(),
            'timestamp' => $data['timestamp'] ?? now(),
            'source_ip' => $data['source_ip'] ?? Request::ip(),
            'user_id' => $data['user_id'] ?? (Auth::check() ? Auth::id() : null),
            'action_type' => $data['action_type'],
            'severity' => $data['severity'] ?? 'info',
            'classification' => $data['classification'] ?? 'general',
            'message' => $data['message'],
            'context' => $data['context'] ?? [],
            'metadata' => $data['metadata'] ?? [],
            'request_id' => $data['request_id'] ?? Str::uuid(),
            'previous_hash' => $previousHash
        ];

        // Generate deterministic SHA-256 integrity hash
        $hashPayload = json_encode([
            'id' => (string) $logData['log_entry_id'],
            'message' => $logData['message'],
            'severity' => $logData['severity'],
            'timestamp' => ($logData['timestamp'] instanceof \DateTimeInterface)
                ? $logData['timestamp']->toIso8601String()
                : (string) $logData['timestamp'],
            'previous_hash' => $previousHash
        ]);
        $logData['hash'] = hash('sha256', $hashPayload);

        // Create the log
        $log = ServerLog::create($logData);

        // Trigger critical notification if severity is critical or emergency
        if (in_array($log->severity, ['critical', 'emergency'])) {
            $this->notifyCritical($log);
        }

        return $log;
    }

    /**
     * Create a critical log with immediate notification
     */
    public function createCritical(string $message, array $context = []): ServerLog
    {
        return $this->create([
            'action_type' => $context['action_type'] ?? 'critical_event',
            'message' => $message,
            'severity' => 'critical',
            'classification' => $context['classification'] ?? 'security',
            'context' => $context
        ]);
    }

    /**
     * Batch create logs
     */
    public function batchCreate(array $logs): array
    {
        $created = [];
        foreach ($logs as $log) {
            $created[] = $this->create($log);
        }
        return $created;
    }

    /**
     * Notify admins of critical log events via email
     */
    protected function notifyCritical(ServerLog $log): void
    {
        try {
            // Get admin users (or all users for now)
            $admins = User::where('role', 'admin')->get();

            // If no admin role users exist, notify all users
            if ($admins->isEmpty()) {
                $admins = User::all();
            }

            Notification::send($admins, new CriticalLogAlert($log));

            // Mark as notified
            $log->update(['is_notified' => true]);
        } catch (\Exception $e) {
            // Log notification failure but don't break the log creation
            \Log::error('Failed to send critical notification: ' . $e->getMessage());
        }
    }
}