<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * @param Request $request
     */
    public function toResponse($request): RedirectResponse
    {
        // Change the logout redirect to the custom login page
        return redirect()->route('login');
    }
}
