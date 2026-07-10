@extends('layouts.app')
@section('title', 'Lupa Kata Laluan')

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
            <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b">Lupa Kata Laluan</h1>
            <p style="color:#64748b;font-size:.9rem;margin-top:.3rem">Lupa kata laluan anda? Tiada masalah. Masukkan e-mel anda dan kami akan menghantar pautan untuk menetapkan semula kata laluan.</p>
        </div>

        @if (session('status'))
            <div style="background-color: #d1fae5; color: #065f46; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #10b981; text-align: center;">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">{{ session('status') }}</p>
                
                @if(session('reset_url'))
                    <p style="font-size: 0.9rem; margin-bottom: 1rem;">
                        <i>(Simulasi: Anda akan dibawa ke halaman tetapan semula dalam masa <span id="countdown">15</span> saat...)</i>
                    </p>
                    <a href="{{ session('reset_url') }}" class="btn btn-primary" style="display: inline-block; text-decoration: none; padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #059669;">
                        Pergi Sekarang
                    </a>
                    <script>
                        let count = 15;
                        const timer = setInterval(() => {
                            count--;
                            document.getElementById('countdown').innerText = count;
                            if (count <= 0) {
                                clearInterval(timer);
                                window.location.href = "{{ session('reset_url') }}";
                            }
                        }, 1000);
                    </script>
                @endif
            </div>
        @endif

        <div class="card form-card" style="padding:2rem">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="email">Alamat E-mel</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')<p class="form-error" style="color:#ef4444;font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:.8rem">
                    Hantar Pautan Tetapan Semula
                </button>

                <div style="text-align:center;margin-top:1.5rem">
                    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:#64748b;font-size:.9rem;font-weight:600;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#64748b'">
                         Kembali ke Halaman Log Masuk
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
