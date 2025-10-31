<?php

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
        $middleware->alias([
            'role' => CheckRole::class,
            'admin' => AdminMiddleware::class,
            'curl-config' => CurlConfigMiddleware::class,
        ]);

        // Tambahkan middleware global untuk cURL configuration
        $middleware->web()->append(CurlConfigMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tangani exception di sini jika perlu
    })
    ->create();
