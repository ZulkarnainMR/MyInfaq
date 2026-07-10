@extends('layouts.app')
@section('title', $kempen->tajuk_kempen)

@push('styles')
<style>
.kempen-hero { background: linear-gradient(135deg,#0f172a,#134e4a); padding:3rem 0; color:#fff; }
.kempen-hero-inner { display:grid; grid-template-columns:1fr 380px; gap:2.5rem; align-items:start; }
.kempen-hero-img { border-radius:1rem; overflow:hidden; height:450px; background:linear-gradient(135deg,#0f766e,#059669); display:flex;align-items:center;justify-content:center;font-size:4rem;color:rgba(255,255,255,.3); }
.kempen-hero-img img { width:100%;height:100%;object-fit:cover; }
.kempen-tag { font-size:.75rem;font-weight:700;color:#5eead4;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem; }
.kempen-hero h1 { font-size:1.9rem;font-weight:800;line-height:1.3;margin-bottom:1rem; }
.donate-box { background:rgba(255,255,255,.07);border:1.5px solid rgba(255,255,255,.15);border-radius:1rem;padding:1.75rem;backdrop-filter:blur(8px); }
.donate-amounts { display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;margin-bottom:1rem; }
.amt-btn { background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.2);color:#fff;padding:.55rem;border-radius:.6rem;font-weight:700;font-size:.9rem;cursor:pointer;transition:all .2s;text-align:center; }
.amt-btn:hover,.amt-btn.active { background:#0d9488;border-color:#0d9488; }
.section { padding:3rem 0; }
.tabs { display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid #e2e8f0;padding-bottom:0; }
.tab { padding:.6rem 1.1rem;font-weight:600;font-size:.88rem;cursor:pointer;border-bottom:2.5px solid transparent;margin-bottom:-2px;color:#64748b; }
.tab.active { color:#0d9488;border-color:#0d9488; }
.donor-list { display:flex;flex-direction:column;gap:.75rem; }
.donor-item { display:flex;align-items:center;justify-content:space-between;padding:.85rem 1rem;background:#f8fafc;border-radius:.75rem;border:1px solid #e2e8f0; }
@media(max-width:768px){ .kempen-hero-inner{grid-template-columns:1fr} }
</style>
@endpush

@section('content')
<section class="kempen-hero">
    <div class="container">
        <div class="kempen-hero-inner">
            <div>
                @if($kempen->gambar_kempen)
                    <div class="kempen-hero-img" style="margin-bottom:1.5rem">
                        <img src="{{ asset('storage/' . $kempen->gambar_kempen) }}" alt="{{ $kempen->tajuk_kempen }}">
                    </div>
                @else
                    <div class="kempen-hero-img" style="margin-bottom:1.5rem">
                        <span>📷</span>
                    </div>
                @endif
                <div class="kempen-tag">{{ $kempen->organisasi?->nama_organisasi }}</div>
                <h1>{{ $kempen->tajuk_kempen }}</h1>
                <div class="progress-bar" style="margin-bottom:.75rem"><div class="progress-fill" style="width:{{ $kempen->peratus_kutipan }}%"></div></div>
                <div style="display:flex;justify-content:space-between;margin-bottom:1.25rem">
                    <div><span style="font-size:1.6rem;font-weight:800;color:#34d399">RM {{ number_format($kempen->jumlah_kutipan_semasa,0) }}</span><br><span style="font-size:.8rem;color:#94a3b8">daripada RM {{ number_format($kempen->sasaran_dana,0) }}</span></div>
                    <div style="text-align:right"><span style="font-size:1.6rem;font-weight:800;color:#fff">{{ $kempen->peratus_kutipan }}%</span><br><span style="font-size:.8rem;color:#94a3b8">Dicapai</span></div>
                </div>
                <div style="display:flex;gap:1.5rem;font-size:.85rem;color:#94a3b8">
                    <span>👥 {{ $kempen->derma->count() }} penderma</span>
                    @if($kempen->tarikh_tamat)<span>📅 Tamat {{ $kempen->tarikh_tamat->format('d M Y') }}</span>@endif
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <div class="donate-box">
                    @auth
                        <a id="donate-link" href="{{ route('public.derma.checkout', $kempen) }}" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:.8rem">💚 Derma Sekarang</a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('public.kempen',$kempen) }}" class="btn btn-primary" style="width:100%;justify-content:center">Log Masuk untuk Derma</a>
                        <a href="{{ route('register.penderma') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.6rem;color:#fff;border-color:rgba(255,255,255,.4)">Daftar Akaun Percuma</a>
                    @endauth
                    
                    <div style="display:flex; align-items:center; margin: 1.5rem 0 1rem 0;">
                        <div style="flex:1; height:1px; background:rgba(255,255,255,.15);"></div>
                        <span style="padding:0 1rem; font-size:.8rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Atau Kongsi</span>
                        <div style="flex:1; height:1px; background:rgba(255,255,255,.15);"></div>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.6rem">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Sertai saya menderma untuk '.$kempen->tajuk_kempen.' di MyInfaq! ' . route('public.kempen', $kempen)) }}" target="_blank" class="btn btn-outline" style="justify-content:center;font-size:.85rem;color:#fff;border-color:rgba(255,255,255,.2);background:rgba(37, 211, 102, 0.15)">
                            💬 WhatsApp
                        </a>
                        <button onclick="copyLink(this, '{{ route('public.kempen', $kempen) }}')" class="btn btn-outline" style="justify-content:center;font-size:.85rem;color:#fff;border-color:rgba(255,255,255,.2);background:rgba(255,255,255,.05)">
                            🔗 Copy Link
                        </button>
                    </div>
                </div>

                <!-- Organizer Info -->
                <div class="donate-box" style="padding:1.25rem;">
                    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1rem;color:#e2e8f0;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:.5rem;">Dianjurkan Oleh</h3>
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div style="width:50px;height:50px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            @if($kempen->organisasi?->logo)
                                <img src="{{ asset('storage/' . $kempen->organisasi->logo) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:1.5rem">🏢</span>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:1rem;color:#fff">{{ $kempen->organisasi?->nama_organisasi ?? 'Penganjur' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Donors -->
                <div class="donate-box" style="padding:1.25rem;">
                    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1rem;color:#e2e8f0;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:.5rem;">Penderma Terkini</h3>
                    @if($kempen->derma->isNotEmpty())
                        <div style="display:flex;flex-direction:column;gap:.8rem;">
                            @foreach($kempen->derma->take(3) as $d)
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem">
                                    {{ strtoupper(substr($d->penderma?->nama_penderma ?? 'H',0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.9rem;color:#fff">{{ $d->penderma?->nama_penderma ?? 'Hamba Allah' }}</div>
                                    <div style="display:flex;gap:.5rem;align-items:center;font-size:.75rem;color:#94a3b8">
                                        <span style="color:#34d399;font-weight:600">RM {{ number_format($d->amaun_derma, 0) }}</span> &bull; {{ $d->tarikh_derma->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($kempen->derma->count() > 3)
                        <div style="text-align:center;margin-top:1rem;padding-top:.5rem;border-top:1px solid rgba(255,255,255,.1);">
                            <a href="javascript:void(0)" onclick="showTab('penderma'); document.getElementById('tab-penderma').scrollIntoView({behavior: 'smooth'})" style="font-size:.85rem;color:#5eead4;text-decoration:none;">Lihat Semua {{ $kempen->derma->count() }} Penderma</a>
                        </div>
                        @endif
                    @else
                        <div style="text-align:center;color:#94a3b8;font-size:.85rem;padding:.5rem 0;">
                            Belum ada penderma. Jadilah yang pertama!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="tabs">
            <div class="tab active" onclick="showTab('perihal')">Perihal</div>
            <div class="tab" onclick="showTab('perkembangan')">Perkembangan</div>
            <div class="tab" onclick="showTab('penderma')">Senarai Penderma</div>
        </div>
        <div id="tab-perihal">
            <div class="card" style="padding:2rem;line-height:1.8;color:#334155">
                {!! nl2br(e($kempen->keterangan_kempen)) !!}
            </div>
        </div>
        <div id="tab-perkembangan" style="display:none">
            @if($kempen->updates->isNotEmpty())
                <div class="updates-list" style="display:flex;flex-direction:column;gap:1.5rem">
                    @foreach($kempen->updates()->latest()->get() as $update)
                    <div class="card" style="padding:1.5rem;border:1px solid #e2e8f0;border-radius:1rem;background:#fff">
                        <div style="font-size:.85rem;color:#64748b;margin-bottom:.5rem;display:flex;align-items:center;gap:.4rem">
                            📅 {{ $update->created_at->format('d M Y, h:i A') }}
                        </div>
                        <h3 style="font-size:1.2rem;font-weight:700;color:#1e293b;margin-bottom:1rem">{{ $update->tajuk_update }}</h3>
                        
                        @if($update->gambar_update)
                            <div style="margin-bottom:1rem;border-radius:.75rem;overflow:hidden">
                                <img src="{{ asset('storage/' . $update->gambar_update) }}" alt="Update Image" style="max-width:100%;height:auto">
                            </div>
                        @endif
                        
                        <div style="line-height:1.7;color:#475569">
                            {!! nl2br(e($update->keterangan_update)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="card" style="padding:2.5rem;text-align:center;color:#64748b;background:#f8fafc;border:1px dashed #cbd5e1">
                    <div style="font-size:2.5rem;margin-bottom:1rem">📢</div>
                    <div style="font-weight:600;font-size:1.1rem;color:#475569">Tiada perkembangan buat masa ini</div>
                    <div style="font-size:.9rem;margin-top:.5rem">Sila semak semula nanti untuk maklumat terkini.</div>
                </div>
            @endif
        </div>
        <div id="tab-penderma" style="display:none">
            @if($kempen->derma->isNotEmpty())
                <div class="donor-list">
                    @foreach($kempen->derma as $d)
                    <div class="donor-item">
                        <div style="display:flex;align-items:center;gap:.75rem">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#0d9488,#10b981);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem">
                                {{ strtoupper(substr($d->penderma?->nama_penderma ?? 'A',0,1)) }}
                            </div>
                            <div><div style="font-weight:600;font-size:.9rem">{{ $d->penderma?->nama_penderma ?? 'Penderma Anon' }}</div><div style="font-size:.78rem;color:#64748b">{{ $d->tarikh_derma->diffForHumans() }}</div></div>
                        </div>
                        <div style="font-weight:700;color:#0d9488">RM {{ number_format($d->amaun_derma,2) }}</div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="card" style="padding:2rem;text-align:center;color:#64748b">Belum ada penderma. Jadilah yang pertama! 🌟</div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function showTab(tab){
    document.querySelectorAll('[id^=tab-]').forEach(el=>el.style.display='none');
    document.querySelectorAll('.tab').forEach(el=>el.classList.remove('active'));
    document.getElementById('tab-'+tab).style.display='block';
    event.target.classList.add('active');
}
function copyLink(btn, url) {
    navigator.clipboard.writeText(url).then(() => {
        let originalText = btn.innerHTML;
        btn.innerHTML = '✅ Copied!';
        setTimeout(() => { btn.innerHTML = originalText; }, 2000);
    });
}
</script>
@endpush
