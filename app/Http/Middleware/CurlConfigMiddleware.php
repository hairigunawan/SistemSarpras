<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CurlConfigMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set cURL options for better compatibility
        if (function_exists('curl_init')) {
            curl_setopt_array(curl_init(), [
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);
        }

        return $next($request);
    }
}
