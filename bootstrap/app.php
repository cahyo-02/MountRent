<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 1. Daftarkan alias untuk middleware admin kamu di sini
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // Catatan: Pastikan nama class-nya benar 'AdminMiddleware'. 
            // Kalau nama file-nya beda, sesuaikan ya.
        ]);

        // 2. Pengecualian CSRF Token untuk Webhook Midtrans
        $middleware->validateCsrfTokens(except: [
            '/midtrans-webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
