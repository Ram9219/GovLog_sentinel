<?php

return [
    'sid' => env('TWILIO_SID'),
    'auth_token' => env('TWILIO_AUTH_TOKEN'),
    'phone_number' => env('TWILIO_PHONE_NUMBER'),
    'trial_mode' => env('TWILIO_TRIAL_MODE', true),
    'verified_number' => env('TWILIO_VERIFIED_NUMBER'),
    'alert_phone' => env('TWILIO_ALERT_PHONE'),
];
