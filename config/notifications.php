<?php

return [
    'channels' => [
        'critical' => ['whatsapp', 'sms', 'email', 'pusher'],
        'error' => ['email', 'pusher'],
        'warning' => ['pusher', 'database'],
        'info' => ['database']
    ],
    
    'throttling' => [
        'sms' => 5, // Max SMS per minute
        'whatsapp' => 10, // Max WhatsApp per minute
        'email' => 20
    ],
    
    'quiet_hours' => [
        'enabled' => true,
        'start' => '22:00',
        'end' => '08:00',
        'bypass_for' => ['emergency', 'critical']
    ]
];
