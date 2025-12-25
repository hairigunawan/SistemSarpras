<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FonnteService
{
    protected $token;
    protected $apiUrl;
    protected $country;
    protected $timeout;
    protected $retries;
    protected $authPrefix;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->apiUrl = config('services.fonnte.api_url');
        $this->country = config('services.fonnte.country', '62');
        $this->timeout = config('services.fonnte.timeout', 30);
        $this->retries = config('services.fonnte.retries', 3);
        $this->authPrefix = config('services.fonnte.auth_prefix', '');
    }

    /**
     * Send a WhatsApp message via Fonnte.
     *
     * @param string $target
     * @param string $message
     * @param array $options
     * @return array
     */
    public function send(string $target, string $message, array $options = [])
    {
        $correlationId = (string) Str::uuid();
        $sanitizedTarget = $this->sanitizeNumber($target);
        
        // Observability: Log attempt (Masked)
        Log::info("FonnteService: Sending message [{$correlationId}]", [
            'target_hash' => hash('sha256', $sanitizedTarget),
            'msg_length' => strlen($message),
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->authPrefix . $this->token,
            ])
            ->timeout($this->timeout)
            ->retry($this->retries, 100, function ($exception, $request) {
                if ($exception instanceof \Illuminate\Http\Client\ConnectionException) {
                    return true;
                }
                if ($exception instanceof \Illuminate\Http\Client\RequestException) {
                    return $exception->response->serverError();
                }
                return false;
            })
            ->throw()
            ->post($this->apiUrl, array_merge([
                'target' => $sanitizedTarget,
                'message' => $message,
                'countryCode' => $this->country, 
            ], $options));

            Log::info("FonnteService: Success [{$correlationId}]", [
                'response' => $response->json(),
            ]);
            return $response->json();

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // 4xx: Client Error (e.g. Invalid Number) -> Return response (Job won't retry)
            if ($e->response->clientError()) {
                Log::warning("FonnteService: Client Error [{$correlationId}]", [
                    'status' => $e->response->status(),
                    'body' => $e->response->body()
                ]);
                return $e->response->json();
            }
            
            // 5xx: Server Error -> Throw (Job will retry)
            Log::error("FonnteService: Server Error [{$correlationId}]", [
                'status' => $e->response->status(),
                'body' => $e->response->body()
            ]);
            throw $e;

        } catch (\Exception $e) {
            Log::error("FonnteService: Exception [{$correlationId}]", [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Sanitize phone number to required format.
     * e.g. 0812... -> 62812...
     *      +62812... -> 62812...
     * 
     * @param string $number
     * @return string
     */
    public function sanitizeNumber(string $number): string
    {
        $number = trim($number);
        $number = preg_replace('/[^0-9]/', '', $number); // Remove non-numeric

        // Handle 00 prefix (international)
        if (str_starts_with($number, '00')) {
            $number = substr($number, 2);
        }

        // Check if starts with '0' (standard local format)
        if (str_starts_with($number, '0')) {
            return $this->country . substr($number, 1);
        }

        // Check if starts with 62 (assuming country code)
        if (str_starts_with($number, $this->country)) {
            return $number;
        }
        
        // Handle cases like '812...' where 0 is missing but intended as local
        if (str_starts_with($number, '8') && strlen($number) >= 10) {
             return $this->country . $number;
        }

        return $number;
    }

    // Static facade-like method for backward compatibility or simple usage
    public static function sendMessage($target, $message)
    {
        $instance = new self();
        return $instance->send($target, $message);
    }
}