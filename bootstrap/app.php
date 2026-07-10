<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai semua proxy (ngrok, reverse proxy) — supaya URL https:// dijana dengan betul
        $middleware->trustProxies(at: '*');

        // Langkau halaman interstitial ngrok yang boleh merosakkan sesi/CSRF
        $middleware->append(\App\Http\Middleware\NgrokSkipWarning::class);

        // Kecualikan callback ToyyibPay daripada CSRF — server ToyyibPay POST tanpa token
        $middleware->validateCsrfTokens(except: [
            '/derma/callback',
            '/organisasi/activation/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
