<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CurlConfigMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ TRUST PROXY UNTUK RAILWAY
        $middleware->trustProxies(
            at: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
        );

        $middleware->alias([
            'role' => CheckRole::class,
            'admin' => AdminMiddleware::class,
            'curl-config' => CurlConfigMiddleware::class,
        ]);

        $middleware->web()->append(CurlConfigMiddleware::class);
        $middleware->web()->append(\Illuminate\Session\Middleware\AuthenticateSession::class);
        $middleware->web()->append(
            \App\Http\Middleware\CountPeminjamanHariIni::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
