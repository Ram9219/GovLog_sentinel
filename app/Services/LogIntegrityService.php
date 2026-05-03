<?php

namespace App\Services;

use App\Models\ServerLog;

class LogIntegrityService
{
    /**
     * Verify a single log's hash integrity
     */
    public function verify(ServerLog $log): bool
    {
        $hashPayload = json_encode([
            'id' => (string) $log->log_entry_id,
            'message' => $log->message,
            'severity' => $log->severity,
            'timestamp' => $log->timestamp->toIso8601String(),
            'previous_hash' => $log->previous_hash
        ]);

        return hash('sha256', $hashPayload) === $log->hash;
    }

    /**
     * Verify the entire log chain integrity
     */
    public static function verifyChain(): array
    {
        $logs = ServerLog::orderBy('id')->get();
        $results = [
            'total' => $logs->count(),
            'valid' => 0,
            'invalid' => 0,
            'details' => []
        ];

        $previousLog = null;
        foreach ($logs as $log) {
            $isValid = true;
            $reason = null;

            // Check if previous hash matches the actual previous log's hash
            if ($previousLog) {
                if ($previousLog->hash !== $log->previous_hash) {
                    $isValid = false;
                    $reason = 'Previous hash mismatch';
                }
            }

            // Also verify the log's own hash if it looks like SHA-256 (64 hex chars)
            if ($isValid && strlen($log->hash) === 64) {
                $service = new self();
                if (!$service->verify($log)) {
                    $isValid = false;
                    $reason = 'Self-hash verification failed';
                }
            }

            $results['details'][] = [
                'log_id' => $log->id,
                'valid' => $isValid,
                'reason' => $reason
            ];

            if ($isValid) {
                $results['valid']++;
            } else {
                $results['invalid']++;
            }

            $previousLog = $log;
        }

        return $results;
    }
}
