@extends('layouts.app')
@section('title', 'Derma – ' . $kempen->tajuk_kempen)

@push('styles')
<style>
.checkout-wrap {
    max-width: 680px;
    margin: 3rem auto;
    padding: 0 1.5rem;
}
.checkout-summary {
    background: linear-gradient(135deg, #0f766e, #059669);
    color: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.75rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.checkout-summary .kempen-thumb {
    width: 64px; height: 64px;
    border-radius: .625rem;
    background: rgba(255,255,255,.2);
    object-fit: cover;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    overflow: hidden;
}
.amt-btn {
    padding: .45rem .85rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 9999px;
    background: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    font-size: .85rem;
}
.amt-btn:hover, .amt-btn.active {
    border-color: #0d9488;
    background: #f0fdfa;
    color: #0d9488;
}
.toyyibpay-badge {
    display: flex;
    align-items: center;
    gap: .75rem;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 1.5px solid #6ee7b7;
    border-radius: .875rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}
.toyyibpay-logo {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #0d9488, #059669);
    border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.3rem; font-weight: 800;
    flex-shrink: 0;
}
.toyyibpay-info { flex: 1; }
.toyyibpay-info strong { display: block; font-size: .9rem; color: #065f46; font-weight: 700; }
.toyyibpay-info span { font-size: .8rem; color: #047857; }
.secure-icons { display: flex; gap: .5rem; margin-top: .35rem; }
.secure-tag {
    font-size: .7rem; font-weight: 700;
    background: #d1fae5; color: #065f46;
    padding: .15rem .5rem; border-radius: 9999px;
}
</style>
@endpush

@section('content')
<div class="checkout-wrap">
    <h1 style="font-size:1.6rem;font-weight:800;margin-bottom:1.5rem">💚 Proses Derma</h1>

    {{-- Campaign Summary --}}
    <div class="checkout-summary">
        <div class="kempen-thumb">
            @if($kempen->gambar_kempen)
                <img src="{{ asset('storage/'.$kempen->gambar_kempen) }}" alt="" style="width:100%;height:100%;object-fit:cover">
            @else
                🕌
            @endif
        </div>
        <div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.7);margin-bottom:.2rem">Anda menyumbang kepada</div>
            <div style="font-weight:700;font-size:1rem">{{ $kempen->tajuk_kempen }}</div>
            <div style="font-size:.8rem;color:rgba(255,255,255,.7)">oleh {{ $kempen->organisasi?->nama_organisasi }}</div>
        </div>
    </div>

    @if ($errors->any())
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:.75rem;padding:1rem;margin-bottom:1.25rem;color:#991b1b;font-size:.9rem">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    {{-- Form --}}
    <div class="card" style="padding:2rem">
        <form action="{{ route('public.derma.proses', $kempen) }}" method="POST" id="donation-form" onsubmit="document.getElementById('submit-btn').disabled=true; document.getElementById('submit-btn').innerHTML='⏳ Menghubungi gateway...';">
            @csrf

            {{-- Amaun --}}
            <div class="form-group">
                <label class="form-label">Amaun Derma (RM)</label>
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:.75rem">
                    @foreach([10, 20, 50, 100, 200, 500] as $amt)
                        <button type="button" onclick="selectAmt({{ $amt }}, this)" class="amt-btn">RM {{ $amt }}</button>
                    @endforeach
                </div>
                <input type="number" name="amaun_derma" id="amaun_derma" class="form-control"
                    placeholder="Masukkan amaun (contoh: 30)" min="1" max="999999" step="0.01"
                    value="{{ old('amaun_derma') }}" required>
                @error('amaun_derma')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Nota --}}
            <div class="form-group">
                <label class="form-label">Nota (Pilihan)</label>
                <textarea name="nota" class="form-control" rows="2"
                    placeholder="Doa, ucapan, atau pesanan...">{{ old('nota') }}</textarea>
            </div>

            {{-- Platform Tip (RM2) --}}
            <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 1rem;">
                <input type="checkbox" name="platform_tip" id="platform_tip" value="2.00" style="width: 1.25rem; height: 1.25rem; margin-top: 0.25rem; accent-color: #3b82f6; cursor: pointer;">
                <label for="platform_tip" style="cursor: pointer;">
                    <div style="font-weight: 700; color: #1e3a8a; font-size: 0.95rem; margin-bottom: 0.25rem;">
                        Sokong Platform MyInfaq (+RM 2.00)
                    </div>
                    <div style="font-size: 0.85rem; color: #1e40af; line-height: 1.5;">
                        Tambah RM2 sebagai sumbangan ikhlas kepada platform. Sumbangan anda membantu kami mengekalkan sistem ini tanpa mengenakan caj yang tinggi kepada organisasi kebajikan.
                    </div>
                </label>
            </div>

            {{-- ToyyibPay Badge --}}
            <div class="toyyibpay-badge">
                <div class="toyyibpay-logo">T</div>
                <div class="toyyibpay-info">
                    <strong>Bayar dengan selamat melalui ToyyibPay</strong>
                    <span>Menyokong FPX (Internet Banking), Kad Kredit & E-Wallet</span>
                    <div class="secure-icons">
                        <span class="secure-tag">🔒 SSL Selamat</span>
                        <span class="secure-tag">🏦 FPX</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submit-btn"
                style="width:100%;justify-content:center;font-size:1rem;padding:.85rem">
                Teruskan ke Halaman Pembayaran (<span id="total-amount-display">RM 0.00</span>)
            </button>
        </form>
    </div>

    <div style="text-align:center;margin-top:1.5rem">
        <a href="{{ route('public.kempen', $kempen) }}" style="display:inline-flex; align-items:center; gap:.5rem; color:#0d9488; font-weight:600; font-size:.95rem; text-decoration:none; padding:.65rem 1.5rem; border-radius:999px; background:#f0fdfa; border:1px solid #ccfbf1; transition:all .2s;" onmouseover="this.style.background='#ccfbf1'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#f0fdfa'; this.style.transform='translateY(0)'">
            <span style="font-size:1.2rem;line-height:1"></span>Batal dan Kembali ke Kempen
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTotal() {
    let amt = parseFloat(document.getElementById('amaun_derma').value) || 0;
    let tip = document.getElementById('platform_tip').checked ? 2.00 : 0.00;
    let total = amt + tip;
    document.getElementById('total-amount-display').innerText = 'RM ' + total.toFixed(2);
}

function selectAmt(val, el) {
    document.getElementById('amaun_derma').value = val;
    document.querySelectorAll('.amt-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    updateTotal();
}

document.getElementById('amaun_derma').addEventListener('input', updateTotal);
document.getElementById('platform_tip').addEventListener('change', updateTotal);

// Initial calculation
updateTotal();
</script>
@endpush
