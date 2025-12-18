<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class WhatsappDiagnostics
{
  /**
   * Lakukan diagnostic lengkap untuk WhatsApp Service
   */
  public static function diagnose()
  {
    $diagnostics = [
      'timestamp' => now(),
      'environment' => [
        'WHATSAPP_ACCESS_TOKEN' => self::maskToken(env('WHATSAPP_ACCESS_TOKEN')),
        'WHATSAPP_BUSINESS_PHONE_ID' => env('WHATSAPP_BUSINESS_PHONE_ID'),
        'WHATSAPP_API_URL' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v19.0/'),
      ],
      'config' => [
        'token' => self::maskToken(config('services.whatsapp.token')),
        'phone_number_id' => config('services.whatsapp.phone_number_id'),
        'api_url' => config('services.whatsapp.api_url'),
      ],
      'validation' => [
        'has_token' => !empty(config('services.whatsapp.token')),
        'has_phone_id' => !empty(config('services.whatsapp.phone_number_id')),
        'has_api_url' => !empty(config('services.whatsapp.api_url')),
      ],
      'api_endpoint' => config('services.whatsapp.api_url') . config('services.whatsapp.phone_number_id') . '/messages',
    ];

    Log::info("WhatsApp Diagnostics:", $diagnostics);

    return $diagnostics;
  }

  /**
   * Mask token untuk keamanan saat logging
   */
  private static function maskToken($token)
  {
    if (!$token) {
      return '❌ TIDAK DISET';
    }

    if (strlen($token) > 20) {
      return substr($token, 0, 10) . '...' . substr($token, -10);
    }

    return '***';
  }
}
