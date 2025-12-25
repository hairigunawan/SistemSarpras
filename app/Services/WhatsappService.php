<?php

namespace App\Services;

use App\Exceptions\WhatsappException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $token;
    protected string $apiUrl;
    protected string $countryCode;
    protected int $timeout;
    protected int $retries;
    protected string $authPrefix;
    
    public function __construct()
    {
        $this->token = config('services.fonnte.token') ?? config('services.whatsapp.token', '');
        $this->apiUrl = config('services.fonnte.api_url', 'https://api.fonnte.com/send');
        $this->countryCode = config('services.fonnte.country', '62');
        $this->timeout = (int) config('services.fonnte.timeout', 10);
        $this->retries = (int) config('services.fonnte.retries', 3);
        $this->authPrefix = trim((string) config('services.fonnte.auth_prefix', '')); // e.g. "Bearer"
    }

    /**
     * @param string $number
     * @return string
     * @throws WhatsappException
     */
    private function sanitizeNumber(string $number): string
    {
        $raw = trim($number);

        $digits = preg_replace('/[^0-9]/', '', $raw);
        if ($digits === '') {
            throw new WhatsappException('Nomor telepon tidak valid');
        }

        // Remove leading international "00" if present
        if (strpos($digits, '00') === 0) {
            $digits = preg_replace('/^00+/', '', $digits);
        }

        if ($digits[0] === '0') {
            $digits = $this->countryCode . substr($digits, 1);
        }

        if (strpos($digits, $this->countryCode) !== 0) {
            $digits = $this->countryCode . $digits;
        }

        if (strlen($digits) < 6) {
            throw new WhatsappException('Nomor telepon terlalu pendek setelah sanitasi');
        }

        return '+' . $digits;
    }

    /**
     *
     * @param string $to
     * @param string $message
     * @return array
     * @throws WhatsappException
     */
    public function sendMessage(string $to, string $message): array
    {
        if (empty($this->token)) {
            throw new WhatsappException('FONNTE token belum diset pada konfigurasi');
        }

        $target = $this->sanitizeNumber($to);

        $payload = [
            'target' => $target,
            'message' => $message,
        ];

        $authHeader = $this->authPrefix !== '' ? $this->authPrefix . ' ' . $this->token : $this->token;

        try {
            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Accept' => 'application/json',
            ])
                ->timeout($this->timeout)
                ->retry($this->retries, 200)
                ->post($this->apiUrl, $payload);
        } catch (\Throwable $e) {
            // Network level error
            Log::error('Fonnte HTTP error', [
                'target' => $this->maskNumber($target),
                'error' => $e->getMessage(),
            ]);
            throw new WhatsappException('Gagal melakukan request ke layanan WhatsApp', 0, $e);
        }

        $statusCode = $response->status();
        $data = $response->json();

        $ok = ($statusCode >= 200 && $statusCode < 300) && (isset($data['status']) ? $data['status'] === true : true);

        if (!$ok) {
            Log::error('Fonnte send failed', [
                'target' => $this->maskNumber($target),
                'payload' => [
                    'target' => $this->maskNumber($payload['target']),
                    'message_length' => mb_strlen($message),
                ],
                'http_status' => $statusCode,
                'response' => $this->safeResponseForLog($data),
            ]);

            $reason = $data['reason'] ?? ($data['message'] ?? 'Gagal mengirim pesan WhatsApp');
            throw new WhatsappException($reason);
        }

        Log::info('Fonnte send success', [
            'target' => $this->maskNumber($target),
            'http_status' => $statusCode,
            'response_id' => $data['id'] ?? null,
        ]);

        return $data;
    }

    protected function maskNumber(string $number): string
    {
        if (strlen($number) <= 6) {
            return '***';
        }
        return substr($number, 0, 6) . str_repeat('*', max(0, strlen($number) - 10)) . substr($number, -4);
    }

    protected function safeResponseForLog(array $data): array
    {
        if (isset($data['message'])) {
            $data['message'] = '[redacted]';
        }
        return $data;
    }
}
