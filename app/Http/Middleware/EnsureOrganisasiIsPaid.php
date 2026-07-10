<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganisasiIsPaid
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is logged in, is an Organisasi, and payment_status is not 'Paid'
        if ($user && strtolower($user->role) === 'organisasi' && $user->organisasi) {
            if ($user->organisasi->payment_status !== 'Paid') {
                return redirect()->route('organisasi.activation');
            }
        }

        return $next($request);
    }
}
