<?php

return [
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'phone_number' => env('TWILIO_PHONE_NUMBER'),
    ],
    'whatsapp' => [
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
    ],
];
