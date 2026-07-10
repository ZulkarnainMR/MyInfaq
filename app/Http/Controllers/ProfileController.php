<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Paparkan halaman profil mengikut role pengguna.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Kemaskini maklumat profil.
     *
     * DB::transaction memastikan kemaskini email dan profil berlaku serentak.
     * Jika salah satu gagal, kedua-dua perubahan akan di-rollback.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // ── Validation email ──────────────────────────────────────────────────
        $request->validate([
            'email' => ['required', 'email', 'unique:tbl_users,email,' . $user->id_user . ',id_user'],
        ]);

        try {
            DB::transaction(function () use ($request, $user) {

                // ── Kemaskini email user ───────────────────────────────────────────────
                $user->email = $request->email;
                $user->save();

                // ── Kemaskini profil mengikut role ────────────────────────────────────
                if ($user->isPenderma()) {
                    $request->validate([
                        'nama_penderma' => ['required', 'string', 'max:255'],
                        'no_telefon'    => ['nullable', 'string', 'max:20'],
                    ]);

                    $user->penderma->update([
                        'nama_penderma' => $request->nama_penderma,
                        'no_telefon'    => $request->no_telefon,
                    ]);

                } elseif ($user->isOrganisasi()) {
                    $request->validate([
                        'nama_organisasi' => ['required', 'string', 'max:255'],
                        'no_telefon'      => ['required', 'string', 'max:20'],
                        'alamat'          => ['nullable', 'string'],
                        'logo'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                    ]);

                    $data = [
                        'nama_organisasi' => $request->nama_organisasi,
                        'no_telefon'      => $request->no_telefon,
                        'alamat'          => $request->alamat,
                    ];

                    if ($request->hasFile('logo')) {
                        // Padam logo lama jika ada
                        if ($user->organisasi->logo) {
                            Storage::disk('public')->delete($user->organisasi->logo);
                        }
                        $path = $request->file('logo')->store('logos', 'public');
                        $data['logo'] = $path;
                    }

                    $user->organisasi->update($data);

                } elseif ($user->isStaf()) {
                    $request->validate([
                        'nama_staf' => ['required', 'string', 'max:255'],
                        'jawatan'   => ['nullable', 'string', 'max:100'],
                    ]);

                    $user->staf->update([
                        'nama_staf' => $request->nama_staf,
                        'jawatan'   => $request->jawatan,
                    ]);
                }
            });

            return back()->with('success', 'Profil berjaya dikemaskini!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Biar Laravel uruskan validation error seperti biasa
            throw $e;

        } catch (\Throwable $e) {
            Log::error('ProfileController@update: Gagal kemaskini profil', [
                'id_user' => $user->id_user,
                'message' => $e->getMessage(),
            ]);
            return back()
                ->withInput()
                ->withErrors(['profile' => 'Gagal mengemaskini profil. Sila cuba sebentar lagi.']);
        }
    }

    /**
     * Kemaskini kata laluan.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'kata_laluan_semasa' => ['required'],
            'kata_laluan_baru'   => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->kata_laluan_semasa, $user->password)) {
            return back()
                ->withErrors(['kata_laluan_semasa' => 'Kata laluan semasa tidak sah.'])
                ->with('tab', 'password');
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $user->password = Hash::make($request->kata_laluan_baru);
                $user->save();
            });

            return back()->with('success', 'Kata laluan berjaya ditukar!')->with('tab', 'password');

        } catch (\Throwable $e) {
            Log::error('ProfileController@updatePassword: Gagal tukar kata laluan', [
                'id_user' => $user->id_user,
                'message' => $e->getMessage(),
            ]);
            return back()
                ->withErrors(['password' => 'Gagal menukar kata laluan. Sila cuba sebentar lagi.'])
                ->with('tab', 'password');
        }
    }
}
