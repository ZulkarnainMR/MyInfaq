<?php

namespace App\Filament\Admin\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfilStaf extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $title           = 'Profil Saya';
    protected static ?string $slug            = 'profil-saya';
    protected static ?int    $navigationSort  = 99;

    protected static string $view = 'filament.admin.pages.edit-profil-staf';

    // ── Form state ─────────────────────────────────────────────────────────────
    public ?string $email      = null;
    public ?string $nama_staf  = null;
    public ?string $jawatan    = null;

    // Password fields
    public ?string $kata_laluan_semasa            = null;
    public ?string $kata_laluan_baru              = null;
    public ?string $kata_laluan_baru_confirmation = null;

    public function mount(): void
    {
        $user = auth()->user();

        $this->email     = $user->email;
        $this->nama_staf = $user->staf?->nama_staf;
        $this->jawatan   = $user->staf?->jawatan;
    }

    // ── Save profile ────────────────────────────────────────────────────────────
    public function saveProfil(): void
    {
        $this->validate([
            'email'     => ['required', 'email', 'unique:tbl_users,email,' . auth()->id() . ',id_user'],
            'nama_staf' => ['required', 'string', 'max:255'],
            'jawatan'   => ['nullable', 'string', 'max:100'],
        ], [
            'email.unique'      => 'E-mel ini sudah digunakan oleh akaun lain.',
            'nama_staf.required' => 'Nama diperlukan.',
        ]);

        $user = auth()->user();
        $user->email = $this->email;
        $user->save();

        if ($user->isAdmin() || $user->isStaf()) {
            if ($user->staf) {
                $user->staf->update([
                    'nama_staf' => $this->nama_staf,
                    'jawatan'   => $this->jawatan,
                ]);
            } else {
                $user->staf()->create([
                    'nama_staf' => $this->nama_staf,
                    'jawatan'   => $this->jawatan,
                ]);
            }
        }

        Notification::make()
            ->title('Profil berjaya dikemaskini!')
            ->success()
            ->send();
    }

    // ── Save password ───────────────────────────────────────────────────────────
    public function savePassword(): void
    {
        $this->validate([
            'kata_laluan_semasa'            => ['required'],
            'kata_laluan_baru'              => ['required', 'confirmed', Password::min(8)],
            'kata_laluan_baru_confirmation' => ['required'],
        ], [
            'kata_laluan_baru.min'       => 'Kata laluan baru mesti sekurang-kurangnya 8 aksara.',
            'kata_laluan_baru.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->kata_laluan_semasa, $user->password)) {
            $this->addError('kata_laluan_semasa', 'Kata laluan semasa tidak sah.');
            Notification::make()
                ->title('Kata laluan semasa tidak sah.')
                ->danger()
                ->send();
            return;
        }

        $user->password = Hash::make($this->kata_laluan_baru);
        $user->save();

        $this->kata_laluan_semasa            = null;
        $this->kata_laluan_baru              = null;
        $this->kata_laluan_baru_confirmation = null;

        Notification::make()
            ->title('Kata laluan berjaya ditukar!')
            ->success()
            ->send();
    }
}
