<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ini menambah header 'ngrok-skip-browser-warning' pada setiap respons.
 * Ia mencegah halaman interstitial Ngrok daripada muncul dan merosakkan sesi/CSRF.
 */
class NgrokSkipWarning
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('ngrok-skip-browser-warning', '1');
        return $response;
    }
}
