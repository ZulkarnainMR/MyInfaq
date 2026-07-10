<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (strtolower(trim(auth()->user()->role)) !== 'admin') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akaun anda tidak mempunyai akses ke panel ini.']);
        }

        return $next($request);
    }
}
