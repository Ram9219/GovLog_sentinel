<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppCloudService
{
    protected $client;
    protected $accessToken;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://graph.facebook.com/v18.0/',
            'timeout' => 10.0,
        ]);

        $this->accessToken = config('whatsapp.access_token');
        $this->phoneNumberId = config('whatsapp.phone_number_id');
    }

    public function sendMessage($to, $message, $isCritical = false)
    {
        try {
            $to = $this->formatPhoneNumber($to);

            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $this->formatMessage($message, $isCritical)
                    ]
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'message_id' => $result['messages'][0]['id'] ?? null
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function sendTemplateMessage($to, $templateName, $components = [])
    {
        try {
            $response = $this->client->post("{$this->phoneNumberId}/messages", [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($to),
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => ['code' => 'en'],
                        'components' => $components
                    ]
                ]
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function formatPhoneNumber($number)
    {
        $number = preg_replace('/\D/', '', $number);

        if (strlen($number) === 10) {
            $number = '91' . $number;
        }

        return $number;
    }

    private function formatMessage($message, $isCritical)
    {
        $header = $isCritical ? "🔴 CRITICAL SECURITY ALERT 🔴\n" : "📋 GovLog Alert\n";
        return $header . $message . "\n\n⏰ " . now()->format('d M Y H:i:s');
    }
}
