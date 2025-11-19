<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token;
    protected $phoneNumberId;
    protected $apiUrl;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->apiUrl = config('services.whatsapp.api_url') . "{$this->phoneNumberId}/messages";
    }

    public function sendMessage(string $to, string $message)
    {
        if (!$this->token || !$this->phoneNumberId) {
            throw new \Exception("WhatsApp API belum dikonfigurasikan.");
        }

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $this->sanitizeNumber($to),
            "type" => "text",
            "text" => ["body" => $message]
        ];

        $response = Http::withToken($this->token)->post($this->apiUrl, $payload);

        if ($response->failed()) {
            Log::error("WhatsApp API Error", ['body' => $response->body()]);
        }

        return $response->json();
    }

    private function sanitizeNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (strpos($number, '62') === 0) return $number;
        return '62' . ltrim($number, '0');
    }
}
