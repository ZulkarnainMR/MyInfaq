@extends('layouts.app')
@section('title', 'Log Masuk')

@section('content')
<style>
.login-background {
  background: linear-gradient(135deg, #1a5f4a 0%, #0f4c3a 100%);
  background-image: 
    url('/leaf-pattern.png'),
    linear-gradient(135deg, #1a5f4a 0%, #0f4c3a 100%);
  opacity: 0.08; /* 8% visibility */
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none;
}

.form-card {
  background: white;
  z-index: 10; /* Float above pattern */
  position: relative;
}
</style>
<div class="login-background"></div>
<div style="min-height:75vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem">
    <div style="width:100%;max-width:440px">
        <div style="text-align:center;margin-bottom:2rem">
            <div style="font-size:2.5rem;font-weight:800;background:linear-gradient(135deg,#0d9488,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:.25rem">MyInfaq</div>
            <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b">Log Masuk ke Akaun Anda</h1>
            <p style="color:#64748b;font-size:.9rem;margin-top:.3rem">Platform derma yang telus & dipercayai</p>
        </div>

        <div class="card form-card" style="padding:2rem">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat E-mel</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Kata Laluan</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
                    <label style="display:flex;align-items:center;gap:.4rem;font-size:.85rem;cursor:pointer">
                        <input type="checkbox" name="ingat_saya" style="accent-color:#0d9488"> Ingat Saya
                    </label>
                    <a href="{{ route('password.request') }}" style="font-size:.85rem;color:#0d9488;text-decoration:none;font-weight:600">Lupa Kata Laluan?</a>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:.8rem">
                    Log Masuk
                </button>
                <div style="text-align:center;margin-top:1.5rem">
                    <a href="{{ route('public.home') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:#64748b;font-size:.9rem;font-weight:600;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#64748b'">
                         Kembali ke Halaman Utama
                    </a>
                </div>
            </form>
        </div>

        <div style="text-align:center;margin-top:1.25rem;font-size:.88rem;color:#64748b">
            Belum ada akaun?
            <a href="{{ route('register.penderma') }}" style="color:#0d9488;font-weight:600">Daftar sebagai Penderma</a>
            &bull;
            <a href="{{ route('register.organisasi') }}" style="color:#0d9488;font-weight:600">Daftar Organisasi</a>
        </div>
    </div>
</div>
@endsection
