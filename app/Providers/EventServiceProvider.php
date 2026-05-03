protected $listen = [
    \App\Events\SystemLogEvent::class => [
        \App\Listeners\ProcessSystemLog::class,
        \App\Listeners\BroadcastLogEvent::class,
    ],
    \App\Events\CriticalLogEvent::class => [
        \App\Listeners\SendCriticalNotification::class,
    ],
];