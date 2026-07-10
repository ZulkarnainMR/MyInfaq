<?php

namespace App\Filament\Organisasi\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;

class EditProfilOrganisasi extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon  = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $title           = 'Profil Saya';
    protected static ?string $slug            = 'profil';
    protected static ?int    $navigationSort  = 99;

    protected static string $view = 'filament.organisasi.pages.edit-profil-organisasi';

    // ── Form state ─────────────────────────────────────────────────────────────
    public ?string $email           = null;
    public ?string $nama_organisasi = null;
    public ?string $no_telefon      = null;
    public ?string $alamat          = null;
    public ?string $logo_sedia_ada  = null;  // path logo yang tersimpan
    public $logo                    = null;  // fail baharu yang diupload

    // Password fields
    public ?string $kata_laluan_semasa            = null;
    public ?string $kata_laluan_baru              = null;
    public ?string $kata_laluan_baru_confirmation = null;

    public function mount(): void
    {
        $user = auth()->user();
        $org  = $user->organisasi;

        $this->email           = $user->email;
        $this->nama_organisasi = $org?->nama_organisasi;
        $this->no_telefon      = $org?->no_telefon;
        $this->alamat          = $org?->alamat;
        $this->logo_sedia_ada  = $org?->logo;
    }

    // ── Save profile ────────────────────────────────────────────────────────────
    public function saveProfil(): void
    {
        $this->validate([
            'email'           => ['required', 'email', 'unique:tbl_users,email,' . auth()->id() . ',id_user'],
            'nama_organisasi' => ['required', 'string', 'max:255'],
            'no_telefon'      => ['required', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
            'logo'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'email.unique'             => 'E-mel ini sudah digunakan oleh akaun lain.',
            'nama_organisasi.required' => 'Nama organisasi diperlukan.',
            'no_telefon.required'      => 'No. telefon diperlukan.',
            'logo.image'               => 'Fail yang dimuat naik mesti berformat imej.',
            'logo.max'                 => 'Saiz imej tidak boleh melebihi 2MB.',
        ]);

        $user = auth()->user();
        $user->email = $this->email;
        $user->save();

        $data = [
            'nama_organisasi' => $this->nama_organisasi,
            'no_telefon'      => $this->no_telefon,
            'alamat'          => $this->alamat,
        ];

        // ── Handle logo upload ──────────────────────────────────────────────
        if ($this->logo) {
            // Padam logo lama jika ada
            if ($this->logo_sedia_ada) {
                Storage::disk('public')->delete($this->logo_sedia_ada);
            }
            // Simpan logo baharu
            $path = $this->logo->store('logos', 'public');
            $data['logo'] = $path;
            $this->logo_sedia_ada = $path;
        }

        $user->organisasi->update($data);

        // Reset logo input
        $this->logo = null;

        Notification::make()
            ->title('Profil berjaya dikemaskini!')
            ->success()
            ->send();
    }

    // ── Remove logo ─────────────────────────────────────────────────────────────
    public function removeLogo(): void
    {
        $user = auth()->user();

        if ($this->logo_sedia_ada) {
            Storage::disk('public')->delete($this->logo_sedia_ada);
        }

        $user->organisasi->update(['logo' => null]);
        $this->logo_sedia_ada = null;
        $this->logo = null;

        Notification::make()
            ->title('Logo berjaya dibuang.')
            ->success()
            ->send();
    }

    // ── Save password ───────────────────────────────────────────────────────────
    public function savePassword(): void
    {
        $this->validate([
            'kata_laluan_semasa'           => ['required'],
            'kata_laluan_baru'             => ['required', 'confirmed', Password::min(8)],
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
