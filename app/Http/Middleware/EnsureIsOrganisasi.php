<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsOrganisasi
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('filament.organisasi.auth.login');
        }

        if (auth()->user()->role !== 'Organisasi') {
            auth()->logout();
            return redirect()->route('filament.organisasi.auth.login')
                ->withErrors(['email' => 'Akaun anda tidak mempunyai akses ke portal organisasi.']);
        }

        return $next($request);
    }
}
