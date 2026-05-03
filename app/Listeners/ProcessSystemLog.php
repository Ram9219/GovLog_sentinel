<?php

namespace App\Listeners;

use App\Events\SystemLogEvent;
use App\Services\LogCreationService;
use App\Services\ClassificationService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProcessSystemLog implements ShouldQueue
{
    protected $logCreationService;
    protected $classificationService;
    protected $notificationService;

    public function __construct(
        LogCreationService $logCreationService,
        ClassificationService $classificationService,
        NotificationService $notificationService
    ) {
        $this->logCreationService = $logCreationService;
        $this->classificationService = $classificationService;
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(SystemLogEvent $event): void
    {
        try {
            // Step 1: Classify the log
            $classification = $this->classificationService->classify(
                $event->logData['action_type'] ?? 'unknown',
                $event->logData['message'] ?? '',
                $event->logData['context'] ?? []
            );

            // Step 2: Merge classification with log data
            $logData = array_merge($event->logData, $classification);

            // Step 3: Create and store the log
            $serverLog = $this->logCreationService->create($logData);

            // Step 4: Send notifications for critical/emergency logs
            if (in_array($serverLog->severity, ['critical', 'emergency'])) {
                $this->notificationService->dispatchNotifications($serverLog);
            }

            Log::info('Log processed successfully', ['log_id' => $serverLog->id]);
        } catch (\Exception $e) {
            Log::error('Failed to process log: ' . $e->getMessage(), [
                'log_data' => $event->logData
            ]);
        }
    }
}