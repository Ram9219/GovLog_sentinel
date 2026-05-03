<?php

namespace App\Console\Commands;

use App\Models\NotificationQueue;
use App\Models\ServerLog;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendPendingNotifications extends Command
{
    protected $signature = 'notifications:send-pending {--retry-failed : Also retry previously failed notifications}';

    protected $description = 'Process and send all pending notifications from the notification queue.';

    public function handle(NotificationService $notificationService): int
    {
        $query = NotificationQueue::where('status', 'pending');

        if ($this->option('retry-failed')) {
            $query->orWhere(function ($q) {
                $q->where('status', 'failed')
                  ->where('retry_count', '<', 3);
            });
        }

        $pending = $query->with('log')->get();

        if ($pending->isEmpty()) {
            $this->info('✅ No pending notifications to process.');
            return self::SUCCESS;
        }

        $this->info("📬 Processing {$pending->count()} pending notification(s)...");

        $sent = 0;
        $failed = 0;

        foreach ($pending as $notification) {
            $log = $notification->log;

            if (!$log) {
                $notification->update(['status' => 'failed', 'error_log' => ['error' => 'Log entry not found']]);
                $failed++;
                continue;
            }

            $this->line("  → Sending notification for log #{$log->id} ({$log->severity})");

            try {
                $channels = is_array($notification->channels) ? $notification->channels : json_decode($notification->channels, true);
                $results = $notificationService->dispatchNotifications($log, $channels);

                $allSuccess = collect($results)->every(fn($r) => $r['success'] ?? false);

                $notification->update([
                    'status' => $allSuccess ? 'sent' : 'failed',
                    'sent_at' => $allSuccess ? now() : null,
                    'retry_count' => $notification->retry_count + 1,
                    'error_log' => $allSuccess ? null : $results,
                ]);

                $allSuccess ? $sent++ : $failed++;
            } catch (\Exception $e) {
                $notification->update([
                    'status' => 'failed',
                    'retry_count' => $notification->retry_count + 1,
                    'error_log' => ['exception' => $e->getMessage()],
                ]);
                $failed++;
                $this->error("    ✗ Failed: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("📊 Results: {$sent} sent, {$failed} failed");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
