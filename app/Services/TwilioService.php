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
        try {
            if (config('twilio.trial_mode')) {
                $to = config('twilio.verified_number');
            }

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
