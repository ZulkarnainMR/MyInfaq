@extends('layouts.app')
@section('title', 'Daftar Sebagai Penderma')

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
    <div style="width:100%;max-width:480px">
        <div style="text-align:center;margin-bottom:2rem">
            <h1 style="font-size:1.6rem;font-weight:800;color:#1e293b"> Daftar Sebagai Penderma</h1>
            <p style="color:#64748b;font-size:.9rem;margin-top:.3rem">Sertai komuniti penderma MyInfaq hari ini</p>
        </div>
        <div class="card form-card" style="padding:2rem">
            <form method="POST" action="{{ route('register.penderma.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nama_penderma">Nama Penuh</label>
                    <input type="text" id="nama_penderma" name="nama_penderma" class="form-control" value="{{ old('nama_penderma') }}" required>
                    @error('nama_penderma')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Alamat E-mel</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="no_telefon">No. Telefon (Pilihan)</label>
                    <input type="text" id="no_telefon" name="no_telefon" class="form-control" value="{{ old('no_telefon') }}" placeholder="01x-xxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Kata Laluan</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="8">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Sahkan Kata Laluan</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem;font-size:1rem">Daftar Sekarang</button>
            </form>
        </div>
        <div style="text-align:center;margin-top:1rem;font-size:.88rem;color:#64748b">
            Sudah ada akaun? <a href="{{ route('login') }}" style="color:#0d9488;font-weight:600">Log Masuk</a>
            
        </div>
    </div>
</div>
@endsection
