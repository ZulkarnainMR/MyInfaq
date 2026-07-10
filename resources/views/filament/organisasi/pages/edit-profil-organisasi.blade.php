<x-filament-panels::page>
<style>
.profil-section {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.profil-section-header {
    background: linear-gradient(135deg, #064e3b, #047857);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.profil-section-header h3 {
    color: #fff;
    font-weight: 700;
    font-size: 0.95rem;
    margin: 0;
}
.profil-section-body {
    padding: 1.5rem;
}
.profil-banner {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%);
    border-radius: 1rem;
    padding: 1.75rem 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.profil-banner::after {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 150px; height: 150px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.profil-avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
    overflow: hidden;
    background: linear-gradient(135deg, #10b981, #34d399);
}
.profil-avatar img {
    width: 100%; height: 100%; object-fit: cover;
}
.profil-name { font-size: 1.2rem; font-weight: 800; color: #fff; }
.profil-email { font-size: 0.85rem; color: rgba(209,250,229,0.8); margin-top: 0.2rem; }
.profil-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    padding: 0.25rem 0.75rem; border-radius: 9999px;
    font-size: 0.75rem; font-weight: 700;
    margin-top: 0.5rem;
}
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.field-row.full { grid-template-columns: 1fr; }
.field-group { display: flex; flex-direction: column; gap: 0.4rem; }
.field-label {
    font-size: 0.78rem; font-weight: 700;
    color: #374151; text-transform: uppercase; letter-spacing: 0.04em;
}
.field-input {
    width: 100%; padding: 0.65rem 1rem;
    border: 1.5px solid #d1d5db; border-radius: 0.625rem;
    font-size: 0.9rem; color: #111827;
    background: #fff; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
}
.field-input:focus {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
}
.field-input:disabled {
    background: #f9fafb; color: #9ca3af; cursor: not-allowed;
}
textarea.field-input { resize: vertical; min-height: 80px; }
.field-error { font-size: 0.78rem; color: #ef4444; margin-top: 0.2rem; }
.field-hint { font-size: 0.75rem; color: #9ca3af; margin-top: 0.2rem; }

/* ── Logo Upload Area ── */
.logo-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 0.875rem;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    position: relative;
}
.logo-upload-area:hover {
    border-color: #059669;
    background: #f0fdf4;
}
.logo-upload-area input[type="file"] {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    opacity: 0; cursor: pointer;
}
.logo-preview-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.875rem;
    margin-bottom: 0.75rem;
}
.logo-preview-img {
    width: 64px; height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d1fae5;
}
.logo-preview-letter {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #34d399);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.btn-remove-logo {
    background: #fee2e2;
    color: #991b1b;
    border: none;
    padding: 0.4rem 0.9rem;
    border-radius: 0.5rem;
    font-size: 0.78rem; font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    margin-left: auto;
}
.btn-remove-logo:hover { background: #fca5a5; }

.btn-profil {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff; border: none;
    padding: 0.7rem 2rem; border-radius: 0.75rem;
    font-weight: 700; font-size: 0.9rem; cursor: pointer;
    box-shadow: 0 4px 16px rgba(5,150,105,0.3);
    transition: transform 0.2s, box-shadow 0.2s;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-profil:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(5,150,105,0.4);
}
.strength-bar {
    height: 4px; background: #f3f4f6;
    border-radius: 999px; margin-top: 0.4rem; overflow: hidden;
}
.strength-fill {
    height: 100%; border-radius: 999px;
    width: 0%; transition: width 0.4s, background 0.4s;
}
.divider {
    border: none; border-top: 1px solid #f3f4f6; margin: 1.25rem 0;
}
@media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
}
</style>

{{-- ── Banner ── --}}
<div class="profil-banner">
    <div class="profil-avatar">
        @if($this->logo_sedia_ada)
            <img src="{{ asset('storage/' . $this->logo_sedia_ada) }}" alt="Logo Organisasi">
        @else
            {{ strtoupper(substr($this->nama_organisasi ?? auth()->user()->email, 0, 1)) }}
        @endif
    </div>
    <div>
        <div class="profil-name">{{ $this->nama_organisasi ?? 'Organisasi' }}</div>
        <div class="profil-email">{{ auth()->user()->email }}</div>
        <div class="profil-badge">🏢 Organisasi</div>
    </div>
</div>

{{-- ══ SECTION 1: Maklumat Profil ══ --}}
<div class="profil-section">
    <div class="profil-section-header">
        <span>🏢</span>
        <h3>Kemaskini Maklumat Organisasi</h3>
    </div>
    <div class="profil-section-body">
        <form wire:submit.prevent="saveProfil" enctype="multipart/form-data">

            <div class="field-row" style="margin-bottom:1rem">
                <div class="field-group">
                    <label class="field-label">E-mel</label>
                    <input type="email" wire:model="email" class="field-input" placeholder="E-mel akaun">
                    @error('email') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Peranan</label>
                    <input type="text" value="Organisasi" class="field-input" disabled>
                </div>
            </div>

            <div class="field-row" style="margin-bottom:1rem">
                <div class="field-group">
                    <label class="field-label">Nama Organisasi</label>
                    <input type="text" wire:model="nama_organisasi" class="field-input" placeholder="Nama organisasi anda">
                    @error('nama_organisasi') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">No. Telefon</label>
                    <input type="tel" wire:model="no_telefon" class="field-input" placeholder="Cth: 0312345678">
                    @error('no_telefon') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="field-row full" style="margin-bottom:1rem">
                <div class="field-group">
                    <label class="field-label">Alamat</label>
                    <textarea wire:model="alamat" class="field-input" placeholder="Alamat penuh organisasi">{{ $this->alamat }}</textarea>
                    @error('alamat') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ── Logo Upload ── --}}
            <div class="field-row full" style="margin-bottom:1.5rem">
                <div class="field-group">
                    <label class="field-label">📸 Gambar Profil / Logo</label>

                    {{-- Pratonton logo semasa atau logo baru --}}
                    <div class="logo-preview-box">
                        @if($this->logo)
                            <img src="{{ $this->logo->temporaryUrl() }}" alt="Pratonton" class="logo-preview-img">
                            <div>
                                <div style="font-weight:600;font-size:0.88rem;color:#059669">✅ Imej dipilih</div>
                                <div style="font-size:0.78rem;color:#6b7280">{{ $this->logo->getClientOriginalName() }}</div>
                            </div>
                        @elseif($this->logo_sedia_ada)
                            <img src="{{ asset('storage/' . $this->logo_sedia_ada) }}" alt="Logo Semasa" class="logo-preview-img">
                            <div>
                                <div style="font-weight:600;font-size:0.88rem;color:#374151">Logo Semasa</div>
                                <div style="font-size:0.78rem;color:#6b7280">Muat naik gambar baru untuk menggantikan</div>
                            </div>
                            <button type="button" class="btn-remove-logo" wire:click="removeLogo"
                                    wire:confirm="Adakah anda pasti mahu membuang logo ini?">
                                🗑️ Buang
                            </button>
                        @else
                            <div class="logo-preview-letter">
                                {{ strtoupper(substr($this->nama_organisasi ?? 'O', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:0.88rem;color:#374151">Tiada Logo</div>
                                <div style="font-size:0.78rem;color:#6b7280">Pilih imej untuk dimuat naik</div>
                            </div>
                        @endif
                    </div>

                    {{-- Input upload --}}
                    <div class="logo-upload-area">
                        <input type="file" wire:model="logo" accept="image/jpeg,image/png,image/jpg,image/gif">
                        <div style="pointer-events:none">
                            <div style="font-size:1.5rem;margin-bottom:0.4rem">📤</div>
                            <div style="font-weight:600;font-size:0.88rem;color:#374151">Klik atau seret untuk muat naik</div>
                            <div class="field-hint">JPG, JPEG, PNG, GIF — Maksimum 2MB</div>
                        </div>
                    </div>

                    {{-- Progress indicator --}}
                    <div wire:loading wire:target="logo" style="display:flex;align-items:center;gap:0.5rem;margin-top:0.5rem;font-size:0.82rem;color:#059669">
                        <svg style="animation:spin 1s linear infinite;width:14px;height:14px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Sedang memuat...
                    </div>
                    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>

                    @error('logo') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end">
                <button type="submit" class="btn-profil">
                    <span wire:loading.remove wire:target="saveProfil">💾 Simpan Perubahan</span>
                    <span wire:loading wire:target="saveProfil">⏳ Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ SECTION 2: Tukar Kata Laluan ══ --}}
<div class="profil-section">
    <div class="profil-section-header">
        <span>🔑</span>
        <h3>Tukar Kata Laluan</h3>
    </div>
    <div class="profil-section-body">
        <form wire:submit.prevent="savePassword">

            <div style="max-width:460px; display:flex; flex-direction:column; gap:1rem;">
                <div class="field-group">
                    <label class="field-label">Kata Laluan Semasa</label>
                    <input type="password" wire:model="kata_laluan_semasa" class="field-input" placeholder="••••••••" autocomplete="current-password">
                    @error('kata_laluan_semasa') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Kata Laluan Baru</label>
                    <input type="password" wire:model="kata_laluan_baru" class="field-input"
                           placeholder="Min 8 aksara" autocomplete="new-password"
                           oninput="checkStrengthOrg(this.value)">
                    <div class="strength-bar"><div class="strength-fill" id="org-strength-fill"></div></div>
                    <div style="font-size:0.75rem;color:#6b7280;margin-top:0.2rem" id="org-strength-label">Masukkan kata laluan baru</div>
                    @error('kata_laluan_baru') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Sahkan Kata Laluan Baru</label>
                    <input type="password" wire:model="kata_laluan_baru_confirmation" class="field-input"
                           placeholder="Ulangi kata laluan baru" autocomplete="new-password">
                </div>
            </div>

            <hr class="divider">

            <div style="display:flex; justify-content:flex-end">
                <button type="submit" class="btn-profil">
                    <span wire:loading.remove wire:target="savePassword">🔑 Tukar Kata Laluan</span>
                    <span wire:loading wire:target="savePassword">⏳ Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function checkStrengthOrg(val) {
    const fill  = document.getElementById('org-strength-fill');
    const label = document.getElementById('org-strength-label');
    if (!fill) return;
    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;
    const levels = [
        { w: '0%',   bg: '#e5e7eb', txt: 'Masukkan kata laluan baru' },
        { w: '25%',  bg: '#ef4444', txt: '⚠️ Terlalu lemah' },
        { w: '50%',  bg: '#f59e0b', txt: '😐 Sederhana' },
        { w: '75%',  bg: '#3b82f6', txt: '👍 Kuat' },
        { w: '100%', bg: '#10b981', txt: '🔒 Sangat kuat' },
    ];
    fill.style.width      = levels[score].w;
    fill.style.background = levels[score].bg;
    label.textContent     = levels[score].txt;
}
</script>
</x-filament-panels::page>
