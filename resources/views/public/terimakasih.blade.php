@extends('layouts.app')
@section('title', 'Terima Kasih!')

@section('content')
<div style="max-width:580px;margin:4rem auto;padding:0 1.5rem;text-align:center">
    <div style="font-size:5rem;margin-bottom:1rem;animation:bounce 1s infinite alternate">💚</div>
    <h1 style="font-size:2rem;font-weight:800;color:#1e293b;margin-bottom:.5rem">Terima Kasih!</h1>
    <p style="color:#64748b;margin-bottom:2rem">Derma anda telah berjaya diproses. Semoga Allah membalas kebaikan anda.</p>

    <div class="card" style="padding:2rem;text-align:left;margin-bottom:1.5rem">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;color:#0d9488">📋 Resit Derma</h2>
        <table style="width:100%;border-collapse:collapse;font-size:.9rem">
            <tr><td style="padding:.5rem 0;color:#64748b;width:45%">No. Resit</td><td style="font-weight:700">{{ $derma->no_resit }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Kempen</td><td style="font-weight:600">{{ $derma->kempen->tajuk_kempen }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Organisasi</td><td>{{ $derma->kempen->organisasi?->nama_organisasi }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Penderma</td><td>{{ $derma->penderma?->nama_penderma ?? 'Anon' }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Kaedah</td><td>{{ $derma->kaedah_bayaran }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Tarikh</td><td>{{ $derma->tarikh_derma->format('d M Y, H:i') }}</td></tr>
            <tr style="border-top:2px solid #e2e8f0">
                <td style="padding:.75rem 0;color:#64748b;font-weight:700">Jumlah</td>
                <td style="font-size:1.3rem;font-weight:800;color:#0d9488">RM {{ number_format($derma->amaun_derma,2) }}</td>
            </tr>
        </table>
    </div>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('public.home') }}" class="btn btn-primary">Kempen Lain</a>
        @auth
            @if(auth()->user()->isPenderma())
                <a href="{{ route('public.riwayat') }}" class="btn btn-outline">Lihat Riwayat Saya</a>
            @endif
        @endauth
    </div>

    {{-- Sesi Testimoni --}}
    <div class="card" style="padding:2rem;text-align:left;margin-top:2.5rem;background:#f8fafc;border:1px solid #e2e8f0;">
        <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:0.5rem;color:#0f172a;text-align:center">🌟 Bagaimana pengalaman anda hari ini?</h2>
        <p style="color:#64748b;font-size:0.9rem;text-align:center;margin-bottom:1.5rem">Kongsikan maklum balas anda untuk membantu kami menambah baik sistem ini.</p>

        <form id="testimoni-form" onsubmit="hantarTestimoni(event)">
            @csrf
            <input type="hidden" name="nama" value="{{ $derma->penderma?->nama_penderma ?? 'Hamba Allah' }}">
            
            <div style="display:flex;justify-content:center;gap:0.5rem;margin-bottom:1rem" id="star-container">
                @for($i=1; $i<=5; $i++)
                    <span class="star" data-value="{{ $i }}" style="font-size:2rem;cursor:pointer;color:#cbd5e1;transition:color 0.2s;">★</span>
                @endfor
                <input type="hidden" name="bintang" id="bintang-input" value="0" required>
            </div>

            <div class="form-group">
                <textarea name="ulasan" id="ulasan-input" class="form-control" rows="3" placeholder="Tuliskan ulasan ringkas anda di sini..." required style="resize:vertical"></textarea>
            </div>

            <div id="testimoni-msg" style="text-align:center;font-size:0.9rem;font-weight:600;margin-bottom:1rem;display:none"></div>

            <button type="submit" id="btn-submit-testimoni" class="btn btn-primary" style="width:100%;justify-content:center">
                Hantar Ulasan
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
@keyframes bounce { from{transform:translateY(0)} to{transform:translateY(-12px)} }
.star:hover, .star.active { color: #eab308 !important; }
</style>
@endpush

@push('scripts')
<script>
    const stars = document.querySelectorAll('.star');
    const bintangInput = document.getElementById('bintang-input');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            let val = parseInt(this.getAttribute('data-value'));
            bintangInput.value = val;
            
            stars.forEach(s => {
                if(parseInt(s.getAttribute('data-value')) <= val) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });

    async function hantarTestimoni(e) {
        e.preventDefault();
        
        let bintang = bintangInput.value;
        if(bintang == 0) {
            alert('Sila berikan rating bintang.');
            return;
        }

        let btn = document.getElementById('btn-submit-testimoni');
        let msg = document.getElementById('testimoni-msg');
        let form = document.getElementById('testimoni-form');
        
        btn.disabled = true;
        btn.innerHTML = '⏳ Menghantar...';

        try {
            let response = await fetch('{{ route('public.testimoni.hantar') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            if(response.ok) {
                form.style.display = 'none';
                msg.innerHTML = '✅ Terima kasih! Ulasan anda amat bermakna bagi kami.';
                msg.style.color = '#16a34a';
                msg.style.display = 'block';
            } else {
                throw new Error('Gagal');
            }
        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = 'Hantar Ulasan';
            alert('Ralat teknikal. Sila cuba lagi.');
        }
    }
</script>
@endpush
@endsection
