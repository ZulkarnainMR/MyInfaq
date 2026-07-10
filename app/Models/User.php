<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use Notifiable;

    protected $table = 'tbl_users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'email',
        'password',
        'role',
        'tarikh_daftar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_daftar' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────
    public function penderma()
    {
        return $this->hasOne(Penderma::class, 'id_user', 'id_user');
    }

    public function organisasi()
    {
        return $this->hasOne(Organisasi::class, 'id_user', 'id_user');
    }

    public function staf()
    {
        return $this->hasOne(Staf::class, 'id_user', 'id_user');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    public function isAdmin(): bool    { return strtolower(trim($this->role)) === 'admin'; }
    public function isStaf(): bool     { return strtolower(trim($this->role)) === 'staf'; }
    public function isPenderma(): bool { return strtolower(trim($this->role)) === 'penderma'; }
    public function isOrganisasi(): bool { return strtolower(trim($this->role)) === 'organisasi'; }

    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'admin'      => strtolower(trim($this->role)) === 'admin',
            'staf'       => strtolower(trim($this->role)) === 'staf',
            'organisasi' => strtolower(trim($this->role)) === 'organisasi',
            default      => false,
        };
    }

    public function getDisplayNameAttribute(): string
    {
        return match(strtolower(trim($this->role))) {
            'penderma'   => $this->penderma?->nama_penderma ?? $this->email,
            'organisasi' => $this->organisasi?->nama_organisasi ?? $this->email,
            'staf'       => $this->staf?->nama_staf ?? $this->email,
            default      => 'Admin',
        };
    }

    /**
     * Filament calls getFilamentName() to display the user in the top nav.
     * We map it to display_name for all roles.
     */
    public function getFilamentName(): string
    {
        return $this->display_name;
    }

    /**
     * Some Filament internals also check ->name directly.
     */
    public function getNameAttribute(): string
    {
        return $this->display_name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->isOrganisasi() && $this->organisasi && $this->organisasi->logo) {
            return Storage::url($this->organisasi->logo);
        }

        return null;
    }
}
