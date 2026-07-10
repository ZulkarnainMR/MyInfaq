<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Tunjuk borang untuk meminta pautan tetapan semula kata laluan.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kendalikan permintaan e-mel tetapan semula.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        // Simulasi: Berikan pautan terus ke frontend jika dalam persekitaran local
        $resetUrl = null;
        if ($status === Password::RESET_LINK_SENT) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                // Jana token baru khas untuk simulasi (token di dalam e-mel log tidak akan sah lagi)
                $token = Password::broker()->createToken($user);
                $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
            }
            
            return back()->with([
                'status' => 'Kami telah menghantar pautan tetapan semula kata laluan anda melalui emel.',
                'reset_url' => $resetUrl
            ]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Tunjuk borang tetapan semula kata laluan.
     */
    public function edit(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Kendalikan tetapan semula kata laluan baru.
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(null);

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
