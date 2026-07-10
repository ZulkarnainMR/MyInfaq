@extends('layouts.app')
@section('title', 'Profil Saya')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   PROFILE PAGE
══════════════════════════════════════════════════════════════ */
.profile-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 50%, #ecfdf5 100%);
    padding: 3rem 0 5rem;
}

/* ── Hero Banner ─────────────────────────────────────────────── */
.profile-banner {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 100%);
    border-radius: 1.75rem;
    padding: 2.5rem 2.5rem 5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -3.5rem;
}
.profile-banner::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 250px; height: 250px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.profile-banner::after {
    content: '';
    position: absolute;
    bottom: -40px; left: -40px;
    width: 180px; height: 180px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.banner-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: rgba(209,250,229,0.7);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.4rem;
}
.banner-title {
    font-size: 1.7rem;
    font-weight: 800;
    color: #fff;
}
.banner-subtitle {
    font-size: 0.9rem;
    color: rgba(209,250,229,0.8);
    margin-top: 0.3rem;
}

/* ── Avatar Circle ───────────────────────────────────────────── */
.profile-avatar-wrap {
    display: flex;
    align-items: flex-end;
    gap: 1.5rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 2;
}
.profile-avatar {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #34d399);
    border: 4px solid rgba(255,255,255,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    user-select: none;
}
.role-chip {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    padding: 0.35rem 1rem; border-radius: 9999px;
    font-size: 0.78rem; font-weight: 700;
}

/* ── Card wrapper ────────────────────────────────────────────── */
.profile-card {
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 8px 40px rgba(5,150,105,0.10);
    border: 1px solid rgba(229,231,235,0.8);
    overflow: hidden;
}

/* ── Tabs ────────────────────────────────────────────────────── */
.profile-tabs {
    display: flex;
    border-bottom: 1px solid #f3f4f6;
    padding: 0 2rem;
    background: #fafafa;
}
.profile-tab {
    padding: 1.1rem 1.5rem;
    font-size: 0.88rem; font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.25s;
    display: flex; align-items: center; gap: 0.5rem;
    white-space: nowrap;
    background: none; border-top: none; border-left: none; border-right: none;
}
.profile-tab:hover { color: #059669; }
.profile-tab.active {
    color: #059669;
    border-bottom-color: #059669;
}

/* ── Panel ───────────────────────────────────────────────────── */
.profile-panel { display: none; padding: 2.5rem; }
.profile-panel.active { display: block; }

/* ── Form layout ─────────────────────────────────────────────── */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.form-row.full { grid-template-columns: 1fr; }

.field-label {
    display: block;
    font-size: 0.82rem; font-weight: 700;
    color: #374151;
    margin-bottom: 0.45rem;
    text-transform: uppercase; letter-spacing: 0.04em;
}
.field-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 0.875rem;
    font-family: inherit; font-size: 0.95rem;
    color: #111827;
    background: #fff;
    transition: border-color 0.25s, box-shadow 0.25s;
    outline: none;
}
.field-input:focus {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
}
.field-input:disabled {
    background: #f9fafb;
    color: #9ca3af;
    cursor: not-allowed;
}
.field-hint {
    font-size: 0.78rem;
    color: #9ca3af;
    margin-top: 0.35rem;
}
.field-error {
    font-size: 0.78rem;
    color: #ef4444;
    margin-top: 0.35rem;
    display: flex; align-items: center; gap: 0.3rem;
}

/* ── Section divider ─────────────────────────────────────────── */
.form-section-title {
    font-size: 0.8rem; font-weight: 700;
    color: #059669;
    text-transform: uppercase; letter-spacing: 0.08em;
    margin: 2rem 0 1.25rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.form-section-title::after {
    content: '';
    flex: 1; height: 1px;
    background: linear-gradient(to right, #d1fae5, transparent);
}

/* ── Save button ─────────────────────────────────────────────── */
.btn-save {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff; border: none;
    padding: 0.85rem 2.5rem;
    border-radius: 1rem;
    font-weight: 700; font-size: 0.95rem;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(5,150,105,0.35);
    transition: transform 0.25s, box-shadow 0.25s;
    display: inline-flex; align-items: center; gap: 0.5rem;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(5,150,105,0.45);
}
.btn-save:active { transform: translateY(0); }

/* ── Password strength ───────────────────────────────────────── */
.password-strength-bar {
    height: 4px; border-radius: 999px;
    background: #f3f4f6; margin-top: 0.5rem; overflow: hidden;
}
.password-strength-fill {
    height: 100%; border-radius: 999px;
    width: 0%; transition: width 0.4s, background 0.4s;
}

/* ── Alert ───────────────────────────────────────────────────── */
.profile-alert {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: 0.875rem;
    margin-bottom: 2rem;
    font-size: 0.9rem; font-weight: 500;
    animation: slideIn 0.4s ease;
}
.profile-alert.success {
    background: #d1fae5; color: #065f46;
    border: 1px solid #a7f3d0;
}
.profile-alert.error {
    background: #fee2e2; color: #991b1b;
    border: 1px solid #fca5a5;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Info box (readonly) ─────────────────────────────────────── */
.info-box {
    background: #f0fdf4;
    border: 1px solid #a7f3d0;
    border-radius: 0.875rem;
    padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 0.75rem;
    font-size: 0.88rem; color: #065f46;
    margin-bottom: 1.5rem;
}

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 768px) {
    .profile-banner { padding: 2rem 1.5rem 4.5rem; border-radius: 1.25rem; }
    .form-row { grid-template-columns: 1fr; }
    .profile-panel { padding: 1.75rem 1.25rem; }
    .profile-tabs { padding: 0 1rem; overflow-x: auto; }
    .profile-tab { padding: 1rem 1rem; font-size: 0.82rem; }
    .banner-title { font-size: 1.3rem; }
}
</style>
@endpush

@section('content')
<div class="profile-page">
    <div class="container">

        {{-- ── Banner ── --}}
        <div class="profile-banner">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar" style="overflow: hidden; background: #fff;">
                    @if(auth()->user()->isOrganisasi() && auth()->user()->organisasi?->logo)
                        <img src="{{ asset('storage/' . auth()->user()->organisasi->logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="background: linear-gradient(135deg, #10b981, #34d399); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #fff;">
                            @if(auth()->user()->isPenderma())
                                {{ strtoupper(substr(auth()->user()->penderma?->nama_penderma ?? 'P', 0, 1)) }}
                            @elseif(auth()->user()->isOrganisasi())
                                {{ strtoupper(substr(auth()->user()->organisasi?->nama_organisasi ?? 'O', 0, 1)) }}
                            @elseif(auth()->user()->isStaf())
                                {{ strtoupper(substr(auth()->user()->staf?->nama_staf ?? 'S', 0, 1)) }}
                            @else
                                A
                            @endif
                        </div>
                    @endif
                </div>
                <div>
                    <div class="banner-label">Akaun MyInfaq</div>
                    <div class="banner-title">
                        @if(auth()->user()->isPenderma()) {{ auth()->user()->penderma?->nama_penderma ?? 'Penderma' }}
                        @elseif(auth()->user()->isOrganisasi()) {{ auth()->user()->organisasi?->nama_organisasi ?? 'Organisasi' }}
                        @elseif(auth()->user()->isStaf()) {{ auth()->user()->staf?->nama_staf ?? 'Staf' }}
                        @else Admin
                        @endif
                    </div>
                    <div class="banner-subtitle">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div>
                <span class="role-chip">
                    @if(auth()->user()->isPenderma()) 💚 Penderma
                    @elseif(auth()->user()->isOrganisasi()) 🏢 Organisasi
                    @elseif(auth()->user()->isStaf()) 👤 Staf
                    @else 🛡️ Admin
                    @endif
                </span>
            </div>
        </div>

        {{-- ── Profile Card ── --}}
        <div class="profile-card">

            {{-- Tabs --}}
            <div class="profile-tabs">
                <button class="profile-tab {{ session('tab') !== 'password' ? 'active' : '' }}"
                        onclick="switchTab('profil')" id="tab-profil">
                    ✏️ Maklumat Profil
                </button>
                <button class="profile-tab {{ session('tab') === 'password' ? 'active' : '' }}"
                        onclick="switchTab('password')" id="tab-password">
                    🔑 Tukar Kata Laluan
                </button>
            </div>

            {{-- ══ PANEL 1: Maklumat Profil ══ --}}
            <div class="profile-panel {{ session('tab') !== 'password' ? 'active' : '' }}" id="panel-profil">

                {{-- Alert --}}
                @if(session('success') && session('tab') !== 'password')
                    <div class="profile-alert success">✅ {{ session('success') }}</div>
                @endif
                @if($errors->any() && session('tab') !== 'password')
                    <div class="profile-alert error">⚠️ {{ $errors->first() }}</div>
                @endif

                <div class="info-box">
                    ℹ️ Perubahan yang anda simpan akan dikemaskini serta-merta pada akaun anda.
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ── Email (semua role) ── --}}
                    <div class="form-section-title">📧 Maklumat Log Masuk</div>
                    <div class="form-row">
                        <div>
                            <label class="field-label" for="email">E-mel</label>
                            <input type="email" id="email" name="email" class="field-input"
                                   value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="field-error">⚠️ {{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label">Peranan</label>
                            <input type="text" class="field-input" value="{{ ucfirst(auth()->user()->role) }}" disabled>
                            <div class="field-hint">Peranan tidak boleh ditukar.</div>
                        </div>
                    </div>

                    {{-- ══ PENDERMA ══ --}}
                    @if(auth()->user()->isPenderma())
                        <div class="form-section-title">👤 Maklumat Peribadi</div>
                        <div class="form-row">
                            <div>
                                <label class="field-label" for="nama_penderma">Nama Penuh</label>
                                <input type="text" id="nama_penderma" name="nama_penderma" class="field-input"
                                       value="{{ old('nama_penderma', auth()->user()->penderma?->nama_penderma) }}"
                                       placeholder="Masukkan nama penuh" required>
                                @error('nama_penderma')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label" for="no_telefon">No. Telefon</label>
                                <input type="tel" id="no_telefon" name="no_telefon" class="field-input"
                                       value="{{ old('no_telefon', auth()->user()->penderma?->no_telefon) }}"
                                       placeholder="Contoh: 0123456789">
                                @error('no_telefon')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    {{-- ══ ORGANISASI ══ --}}
                    @elseif(auth()->user()->isOrganisasi())
                        <div class="form-section-title">🏢 Maklumat Organisasi</div>
                        <div class="form-row full">
                            <div>
                                <label class="field-label" for="logo">Gambar Profil / Logo (Pilihan)</label>
                                <input type="file" id="logo" name="logo" class="field-input" accept="image/jpeg,image/png,image/jpg,image/gif" style="padding: 0.5rem 1rem;">
                                <div class="field-hint">Format yang dibenarkan: JPG, JPEG, PNG, GIF. Saiz maksimum: 2MB.</div>
                                @error('logo')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div>
                                <label class="field-label" for="nama_organisasi">Nama Organisasi</label>
                                <input type="text" id="nama_organisasi" name="nama_organisasi" class="field-input"
                                       value="{{ old('nama_organisasi', auth()->user()->organisasi?->nama_organisasi) }}"
                                       placeholder="Nama organisasi anda" required>
                                @error('nama_organisasi')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">No. Pendaftaran</label>
                                <input type="text" class="field-input"
                                       value="{{ auth()->user()->organisasi?->no_pendaftaran }}" disabled>
                                <div class="field-hint">No. pendaftaran tidak boleh ditukar.</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div>
                                <label class="field-label" for="no_telefon">No. Telefon</label>
                                <input type="tel" id="no_telefon" name="no_telefon" class="field-input"
                                       value="{{ old('no_telefon', auth()->user()->organisasi?->no_telefon) }}"
                                       placeholder="Contoh: 0312345678" required>
                                @error('no_telefon')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Status Aktivasi</label>
                                @php $status = auth()->user()->organisasi?->payment_status; @endphp
                                <input type="text" class="field-input"
                                       value="{{ $status === 'Aktif' ? '✅ Aktif' : ($status === 'Pending' ? '⏳ Menunggu' : '❌ Tidak Aktif') }}"
                                       disabled>
                            </div>
                        </div>
                        <div class="form-row full" style="margin-top:1.25rem">
                            <div>
                                <label class="field-label" for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" class="field-input" rows="3"
                                          placeholder="Alamat penuh organisasi anda">{{ old('alamat', auth()->user()->organisasi?->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    {{-- ══ STAF ══ --}}
                    @elseif(auth()->user()->isStaf())
                        <div class="form-section-title">👤 Maklumat Staf</div>
                        <div class="form-row">
                            <div>
                                <label class="field-label" for="nama_staf">Nama Penuh</label>
                                <input type="text" id="nama_staf" name="nama_staf" class="field-input"
                                       value="{{ old('nama_staf', auth()->user()->staf?->nama_staf) }}"
                                       placeholder="Nama penuh anda" required>
                                @error('nama_staf')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label" for="jawatan">Jawatan</label>
                                <input type="text" id="jawatan" name="jawatan" class="field-input"
                                       value="{{ old('jawatan', auth()->user()->staf?->jawatan) }}"
                                       placeholder="Contoh: Pengurus Kempen">
                                @error('jawatan')
                                    <div class="field-error">⚠️ {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Save --}}
                    <div style="margin-top:2rem; display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn-save">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══ PANEL 2: Tukar Kata Laluan ══ --}}
            <div class="profile-panel {{ session('tab') === 'password' ? 'active' : '' }}" id="panel-password">

                @if(session('success') && session('tab') === 'password')
                    <div class="profile-alert success">✅ {{ session('success') }}</div>
                @endif
                @if($errors->has('kata_laluan_semasa'))
                    <div class="profile-alert error">⚠️ {{ $errors->first('kata_laluan_semasa') }}</div>
                @endif

                <div class="info-box">
                    🔒 Pilih kata laluan yang kuat — sekurang-kurangnya 8 aksara, campuran huruf dan nombor.
                </div>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf

                    <div class="form-section-title">🔑 Tukar Kata Laluan</div>

                    <div style="max-width: 480px; display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <label class="field-label" for="kata_laluan_semasa">Kata Laluan Semasa</label>
                            <input type="password" id="kata_laluan_semasa" name="kata_laluan_semasa"
                                   class="field-input" placeholder="••••••••" required autocomplete="current-password">
                        </div>
                        <div>
                            <label class="field-label" for="kata_laluan_baru">Kata Laluan Baru</label>
                            <input type="password" id="kata_laluan_baru" name="kata_laluan_baru"
                                   class="field-input" placeholder="Min 8 aksara"
                                   required autocomplete="new-password"
                                   oninput="checkStrength(this.value)">
                            <div class="password-strength-bar">
                                <div class="password-strength-fill" id="strength-fill"></div>
                            </div>
                            <div class="field-hint" id="strength-label">Masukkan kata laluan baru</div>
                            @error('kata_laluan_baru')
                                <div class="field-error">⚠️ {{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label" for="kata_laluan_baru_confirmation">Sahkan Kata Laluan Baru</label>
                            <input type="password" id="kata_laluan_baru_confirmation"
                                   name="kata_laluan_baru_confirmation"
                                   class="field-input" placeholder="Ulangi kata laluan baru"
                                   required autocomplete="new-password">
                        </div>
                    </div>

                    <div style="margin-top:2rem; display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn-save">
                            🔑 Tukar Kata Laluan
                        </button>
                    </div>
                </form>
            </div>

        </div>{{-- end profile-card --}}
    </div>{{-- end container --}}
</div>{{-- end profile-page --}}
@endsection

@push('scripts')
<script>
// ── Tab switching ──────────────────────────────────────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.profile-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('panel-' + tab).classList.add('active');
}

// ── Password strength ─────────────────────────────────────────────────────
function checkStrength(val) {
    const fill  = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    let score = 0;
    if (val.length >= 8)                    score++;
    if (/[A-Z]/.test(val))                  score++;
    if (/[0-9]/.test(val))                  score++;
    if (/[^A-Za-z0-9]/.test(val))           score++;

    const levels = [
        { w: '0%',   bg: '#e5e7eb', txt: 'Masukkan kata laluan baru' },
        { w: '25%',  bg: '#ef4444', txt: '⚠️ Terlalu lemah' },
        { w: '50%',  bg: '#f59e0b', txt: '😐 Sederhana' },
        { w: '75%',  bg: '#3b82f6', txt: '👍 Kuat' },
        { w: '100%', bg: '#10b981', txt: '🔒 Sangat kuat' },
    ];
    const l = levels[score];
    fill.style.width      = l.w;
    fill.style.background = l.bg;
    label.textContent     = l.txt;
}
</script>
@endpush
