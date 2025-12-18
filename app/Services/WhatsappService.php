<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token;
    protected $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token') ?? config('services.whatsapp.token');
    }

    public function sendMessage(string $to, string $message)
    {
        if (!$this->token) {
            $errorMessage = "Fonnte Token belum dikonfigurasi. Silakan set FONNTE_TOKEN di .env";
            Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }

        $sanitizedNumber = $this->sanitizeNumber($to);
        Log::info("Mengirim pesan Fonnte ke: " . $sanitizedNumber);

        $payload = [
            "target" => $sanitizedNumber,
            "message" => $message
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post($this->apiUrl, $payload);

        $responseData = $response->json();

        if ($response->failed()) {
            $errorBody = $response->body();
            $errorMessage = $responseData['reason'] ?? $responseData['message'] ?? $errorBody;

            Log::error("Gagal mengirim pesan Fonnte ke {$sanitizedNumber}. Status: {$response->status()}", [
                'request_payload' => $payload,
                'response_body' => $errorBody,
                'response_data' => $responseData
            ]);

            throw new \Exception("Gagal mengirim pesan WhatsApp: " . $errorMessage);
        }

        Log::info("Pesan Fonnte berhasil dikirim ke {$sanitizedNumber}.", [
            'response_data' => $responseData
        ]);

        return $responseData;
    }

    private function sanitizeNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (strpos($number, '62') === 0) return $number;
        return '62' . ltrim($number, '0');
    }
}
