<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function sendMessage($target, $message)
    {
        $token = config('services.fonnte.token');

        return Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target'  => $target,
            'message' => $message,
        ])->json();
    }
}
