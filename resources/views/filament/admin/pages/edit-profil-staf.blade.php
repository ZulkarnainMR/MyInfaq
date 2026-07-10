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
    background: linear-gradient(135deg, #1e3a5f, #1e40af);
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
.profil-banner {
    background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 60%, #2563eb 100%);
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
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    border: 3px solid rgba(255,255,255,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.profil-name { font-size: 1.2rem; font-weight: 800; color: #fff; }
.profil-email { font-size: 0.85rem; color: rgba(219,234,254,0.9); margin-top: 0.2rem; }
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
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}
.field-input:disabled {
    background: #f9fafb; color: #9ca3af; cursor: not-allowed;
}
.field-error { font-size: 0.78rem; color: #ef4444; margin-top: 0.2rem; }
.btn-profil {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: #fff; border: none;
    padding: 0.7rem 2rem; border-radius: 0.75rem;
    font-weight: 700; font-size: 0.9rem; cursor: pointer;
    box-shadow: 0 4px 16px rgba(37,99,235,0.3);
    transition: transform 0.2s, box-shadow 0.2s;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-profil:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37,99,235,0.4);
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
        {{ strtoupper(substr($this->nama_staf ?? auth()->user()->email, 0, 1)) }}
    </div>
    <div>
        <div class="profil-name">{{ $this->nama_staf ?? 'Staf' }}</div>
        <div class="profil-email">{{ auth()->user()->email }}</div>
        <div class="profil-badge">
            {{ auth()->user()->isAdmin() ? '🛡️ Admin' : '👤 Staf' }}
            @if($this->jawatan) — {{ $this->jawatan }} @endif
        </div>
    </div>
</div>

{{-- ══ SECTION 1: Maklumat Profil ══ --}}
<div class="profil-section">
    <div class="profil-section-header">
        <span>👤</span>
        <h3>Kemaskini Maklumat Peribadi</h3>
    </div>
    <div style="padding:1.5rem">
        <form wire:submit.prevent="saveProfil">

            <div class="field-row" style="margin-bottom:1rem">
                <div class="field-group">
                    <label class="field-label">E-mel</label>
                    <input type="email" wire:model="email" class="field-input" placeholder="E-mel akaun">
                    @error('email') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Peranan</label>
                    <input type="text" value="{{ ucfirst(auth()->user()->role) }}" class="field-input" disabled>
                </div>
            </div>

            <div class="field-row" style="margin-bottom:1.5rem">
                <div class="field-group">
                    <label class="field-label">Nama Penuh</label>
                    <input type="text" wire:model="nama_staf" class="field-input" placeholder="Nama penuh anda">
                    @error('nama_staf') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>
                <div class="field-group">
                    <label class="field-label">Jawatan</label>
                    <input type="text" wire:model="jawatan" class="field-input" placeholder="Cth: Pengurus Kempen">
                    @error('jawatan') <span class="field-error">⚠️ {{ $message }}</span> @enderror
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
    <div style="padding:1.5rem">
        <form wire:submit.prevent="savePassword">

            <div style="max-width:460px; display:flex; flex-direction:column; gap:1rem;">
                <div class="field-group">
                    <label class="field-label">Kata Laluan Semasa</label>
                    <input type="password" wire:model="kata_laluan_semasa" class="field-input"
                           placeholder="••••••••" autocomplete="current-password">
                    @error('kata_laluan_semasa') <span class="field-error">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Kata Laluan Baru</label>
                    <input type="password" wire:model="kata_laluan_baru" class="field-input"
                           placeholder="Min 8 aksara" autocomplete="new-password"
                           oninput="checkStrengthStaf(this.value)">
                    <div class="strength-bar"><div class="strength-fill" id="staf-strength-fill"></div></div>
                    <div style="font-size:0.75rem;color:#6b7280;margin-top:0.2rem" id="staf-strength-label">Masukkan kata laluan baru</div>
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
function checkStrengthStaf(val) {
    const fill  = document.getElementById('staf-strength-fill');
    const label = document.getElementById('staf-strength-label');
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
