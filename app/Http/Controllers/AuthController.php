<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\Penderma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('ingat_saya'))) {
            $request->session()->regenerate();
            $user = auth()->user();

            return match (strtolower(trim($user->role))) {
                'admin'      => redirect('/admin'),
                'staf'       => redirect('/staf'),
                'organisasi' => redirect('/organisasi'),
                'penderma'   => redirect()->route('public.riwayat'),
                default      => redirect()->route('public.home'),
            };
        }

        return back()->withErrors(['email' => 'E-mel atau kata laluan tidak sah.'])->onlyInput('email');
    }

    public function showRegisterPenderma()
    {
        return view('auth.register-penderma');
    }

    /**
     * Daftarkan penderma baharu.
     *
     * DB::transaction memastikan jika User berjaya dicipta tetapi Penderma gagal,
     * rekod User akan di-rollback supaya tiada akaun tergantung (orphan user).
     */
    public function registerPenderma(Request $request)
    {
        $request->validate([
            'email'         => ['required', 'email', 'unique:tbl_users,email'],
            'password'      => ['required', 'confirmed', 'min:8'],
            'nama_penderma' => ['required', 'string', 'max:255'],
            'no_telefon'    => ['nullable', 'string', 'max:20'],
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 'Penderma',
                ]);

                Penderma::create([
                    'id_user'       => $user->id_user,
                    'nama_penderma' => $request->nama_penderma,
                    'no_telefon'    => $request->no_telefon,
                ]);

                return $user;
            });

            Auth::login($user);
            return redirect()->route('public.home')->with('success', 'Selamat datang ke MyInfaq!');

        } catch (\Throwable $e) {
            Log::error('AuthController@registerPenderma: Gagal mendaftar penderma', [
                'email'   => $request->email,
                'message' => $e->getMessage(),
            ]);
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['register' => 'Gagal mendaftar akaun. Sila cuba sebentar lagi.']);
        }
    }

    public function showRegisterOrganisasi()
    {
        return view('auth.register-organisasi');
    }

    /**
     * Daftarkan organisasi baharu.
     *
     * DB::transaction memastikan jika User berjaya dicipta tetapi Organisasi gagal,
     * rekod User akan di-rollback supaya tiada akaun tergantung (orphan user).
     */
    public function registerOrganisasi(Request $request)
    {
        $request->validate([
            'email'           => ['required', 'email', 'unique:tbl_users,email'],
            'password'        => ['required', 'confirmed', 'min:8'],
            'nama_organisasi' => ['required', 'string', 'max:255'],
            'no_pendaftaran'  => ['required', 'string', 'unique:tbl_organisasi,no_pendaftaran'],
            'no_telefon'      => ['required', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 'Organisasi',
                ]);

                Organisasi::create([
                    'id_user'         => $user->id_user,
                    'nama_organisasi' => $request->nama_organisasi,
                    'no_pendaftaran'  => $request->no_pendaftaran,
                    'no_telefon'      => $request->no_telefon,
                    'alamat'          => $request->alamat,
                ]);

                return $user;
            });

            Auth::login($user);
            return redirect('/organisasi')->with('success', 'Akaun organisasi berjaya didaftarkan!');

        } catch (\Throwable $e) {
            Log::error('AuthController@registerOrganisasi: Gagal mendaftar organisasi', [
                'email'   => $request->email,
                'message' => $e->getMessage(),
            ]);
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['register' => 'Gagal mendaftar akaun organisasi. Sila cuba sebentar lagi.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.home');
    }
}
