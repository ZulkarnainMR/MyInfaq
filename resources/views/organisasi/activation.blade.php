@extends('layouts.app')
@section('title', 'Pengaktifan Akaun Organisasi')

@section('content')
<div style="max-width: 600px; margin: 4rem auto; padding: 0 1.5rem; text-align: center;">
    
    <h1 style="font-size: 2rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Selamat Datang, {{ $organisasi->nama_organisasi }}!</h1>

    <div class="card" style="padding: 2rem; text-align: left; margin-bottom: 2rem; border-top: 4px solid #0d9488;">
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #0d9488;">Pengaktifan Akaun Diperlukan</h2>
        <p style="color: #475569; line-height: 1.6; margin-bottom: 1.5rem;">
            Untuk mula mencipta kempen sumbangan, anda perlu mengaktifkan akaun organisasi anda terlebih dahulu.
            Yuran pengaktifan dan semakan KYC sebanyak <strong>RM{{ number_format($amount, 2) }}</strong> dikenakan sekali seumur hidup.
        </p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #64748b;">Yuran Pendaftaran & KYC</span>
                <span style="font-weight: 700; color: #1e293b;">RM {{ number_format($amount, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px dashed #cbd5e1; padding-top: 0.5rem;">
                <span style="font-weight: 700; color: #0f172a;">Jumlah Perlu Dibayar</span>
                <span style="font-weight: 800; color: #0d9488; font-size: 1.25rem;">RM {{ number_format($amount, 2) }}</span>
            </div>
        </div>

        <form action="{{ route('organisasi.activation.proses') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 1rem;" onclick="this.disabled=true; this.innerHTML='⏳ Memproses...'; this.form.submit();">
                Bayar RM{{ number_format($amount, 2) }} Sekarang
            </button>
        </form>
    </div>

    <p style="font-size: 0.85rem; color: #64748b;">
        Dengan meneruskan pembayaran, anda bersetuju dengan Terma dan Syarat MyInfaq.
    </p>

    <style>
        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.75rem;
            padding: 0.45rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #dc2626;
            background: #fff1f2;
            border: 1.5px solid #fecaca;
            border-radius: 999px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            letter-spacing: 0.01em;
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #ffffff;
            border-color: #dc2626;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.35);
            transform: translateY(-1px);
            text-decoration: none;
        }
        .logout-btn svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }
        .logout-btn:hover svg {
            transform: translateX(2px);
        }
    </style>

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       class="logout-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
        </svg>
        Log Keluar Sementara
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
@endsection
