@extends('layouts.app')
@section('title', 'Tetapkan Semula Kata Laluan')

@section('content')
<style>
.login-background {
  background: linear-gradient(135deg, #1a5f4a 0%, #0f4c3a 100%);
  background-image: 
    url('/leaf-pattern.png'),
    linear-gradient(135deg, #1a5f4a 0%, #0f4c3a 100%);
  opacity: 0.08;
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none;
}
.form-card {
  background: white;
  z-index: 10;
  position: relative;
}
</style>
<div class="login-background"></div>
<div style="min-height:75vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem">
    <div style="width:100%;max-width:440px">
        <div style="text-align:center;margin-bottom:2rem">
            <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b">Tetapkan Semula Kata Laluan</h1>
            <p style="color:#64748b;font-size:.9rem;margin-top:.3rem">Sila masukkan kata laluan baru anda di bawah.</p>
        </div>

        <div class="card form-card" style="padding:2rem">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="email">Alamat E-mel</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $email) }}" required autocomplete="email">
                    @error('email')<p class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="password">Kata Laluan Baru</label>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                    @error('password')<p class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="password_confirmation">Sahkan Kata Laluan Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:.8rem">
                    Simpan Kata Laluan Baru
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
