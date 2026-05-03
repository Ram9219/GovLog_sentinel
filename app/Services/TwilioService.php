<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $this->client = new Client(
            config('twilio.sid'),
            config('twilio.auth_token')
        );
        $this->fromNumber = config('twilio.phone_number');
    }

    public function sendSms($to, $message, $isEmergency = false)
    {
        // Basic validation: ensure credentials exist before attempting network call
        $sid = config('twilio.sid');
        $token = config('twilio.auth_token');

        if (empty($sid) || str_contains($sid, 'your_') || empty($token) || str_contains($token, 'your_')) {
            Log::warning('Twilio credentials are missing or placeholders. SMS not sent.');
            return [
                'success' => false,
                'error' => 'Twilio SID/AuthToken not configured (check .env)'
            ];
        }

        // If trial mode is enabled and a verified number is configured, use it. Do NOT overwrite
        // the provided $to with null when verified_number is missing.
        if (config('twilio.trial_mode') && !empty(config('twilio.verified_number'))) {
            $to = config('twilio.verified_number');
        }

        // Validate recipient
        if (empty($to) || !is_string($to)) {
            Log::warning('No recipient phone number provided for Twilio SMS.');
            return [
                'success' => false,
                'error' => 'No recipient phone number provided'
            ];
        }

        try {
            $message = $this->client->messages->create(
                $to,
                [
                    'from' => $this->fromNumber,
                    'body' => $this->formatMessage($message, $isEmergency)
                ]
            );

            return [
                'success' => true,
                'sid' => $message->sid,
                'status' => $message->status
            ];
        } catch (\Exception $e) {
            Log::error('Twilio SMS failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function formatMessage($message, $isEmergency)
    {
        $prefix = $isEmergency ? '🚨 CRITICAL ALERT 🚨' : '[GovLog Sentinel]';
        return $prefix . "\n" . $message . "\n\nLog Time: " . now()->format('Y-m-d H:i:s');
    }
}
