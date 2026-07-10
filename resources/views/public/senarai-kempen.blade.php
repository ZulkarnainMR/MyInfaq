@extends('layouts.app')
@section('title', 'Semua Kempen')

@push('styles')
<style>
.senarai-header {
    background: linear-gradient(135deg, #0a2416 0%, #063d1e 100%);
    padding: 4rem 0 3rem;
    color: #fff;
    text-align: center;
}
.senarai-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.senarai-header p {
    color: rgba(209, 250, 229, 0.8);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto 2rem;
}

/* Category Filters */
.category-filters {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
    margin-bottom: 2.5rem;
}
.cat-btn {
    padding: 0.6rem 1.25rem;
    border-radius: 9999px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--gray-600);
}
.cat-btn:hover {
    border-color: var(--em-500);
    color: var(--em-600);
    box-shadow: 0 4px 12px rgba(5,150,105,0.1);
    transform: translateY(-2px);
}
.cat-btn.active {
    background: linear-gradient(135deg, var(--em-600), var(--em-500));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 16px rgba(5,150,105,0.25);
}

/* Search Bar Large */
.search-container {
    max-width: 600px;
    margin: 0 auto 2rem;
    display: flex;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border-radius: 9999px;
    overflow: hidden;
}
.search-input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    font-size: 1rem;
    font-family: inherit;
    outline: none;
}
.search-btn {
    padding: 1rem 2rem;
    background: var(--em-600);
    color: #fff;
    border: none;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.3s;
}
.search-btn:hover {
    background: var(--em-700);
}

/* Grid & Cards */
.kempen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}
.kempen-card {
    background: #fff;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    border: 1px solid var(--border);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
}
.kempen-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}
.kempen-img-wrap {
    position: relative;
    height: 200px;
    background: #f1f5f9;
}
.kempen-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.kempen-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: #cbd5e1;
}
.kempen-cat-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(4px);
    padding: 0.3rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--em-600);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.kempen-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.kempen-org {
    font-size: 0.75rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.kempen-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 0.75rem;
    line-height: 1.4;
}
.kempen-desc {
    font-size: 0.9rem;
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}
.kempen-stats-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}
.stat-box { display: flex; flex-direction: column; gap: 0.2rem; }
.stat-val { font-size: 1.1rem; font-weight: 800; color: var(--em-600); }
.stat-lbl { font-size: 0.75rem; color: var(--gray-500); font-weight: 600; }

@media (max-width: 768px) {
    .search-container { flex-direction: column; border-radius: 1rem; }
    .search-btn { width: 100%; border-radius: 0 0 1rem 1rem; }
}
</style>
@endpush

@section('content')
<section class="senarai-header">
    <div class="container">
        <h1>Senarai Kempen</h1>
       
        
        <form action="{{ route('public.kempen.index') }}" method="GET" class="search-container">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <input type="text" name="cari" class="search-input" placeholder="Cari tajuk kempen atau organisasi..." value="{{ request('cari') }}">
            <button type="submit" class="search-btn">🔍 Cari Kempen</button>
        </form>
    </div>
</section>

<section style="padding: 4rem 0; background: var(--bg-color); min-height: 60vh;">
    <div class="container">
        
        {{-- Filter Kategori --}}
        <div class="category-filters">
            @php
                $kategoriSemasa = request('kategori', 'Semua');
                $kategoriList = ['Semua', 'Pendidikan', 'Kesihatan', 'Bencana Alam', 'Anak Yatim', 'Fakir Miskin', 'Lain-lain'];
            @endphp
            
            @foreach($kategoriList as $kat)
                <a href="{{ route('public.kempen.index', ['kategori' => $kat, 'cari' => request('cari')]) }}" 
                   class="cat-btn {{ $kategoriSemasa === $kat ? 'active' : '' }}">
                    {{ $kat }}
                </a>
            @endforeach
        </div>

        {{-- Results Info --}}
        <div style="margin-bottom: 2rem; color: var(--gray-600); font-size: 0.95rem;">
            Menjumpai <strong>{{ $kempen->total() }}</strong> kempen 
            @if(request('cari'))
                untuk carian "<span style="color:var(--em-600);font-weight:700">{{ request('cari') }}</span>"
            @endif
            @if($kategoriSemasa !== 'Semua')
                dalam kategori "<span style="color:var(--em-600);font-weight:700">{{ $kategoriSemasa }}</span>"
            @endif
        </div>

        {{-- Kempen Grid --}}
        @if($kempen->isNotEmpty())
            <div class="kempen-grid">
                @foreach($kempen as $k)
                <article class="kempen-card">
                    <a href="{{ route('public.kempen', $k) }}" style="text-decoration:none; color:inherit; display:flex; flex-direction:column; height:100%;">
                        <div class="kempen-img-wrap">
                            @if($k->gambar_kempen)
                                <img src="{{ asset('storage/' . $k->gambar_kempen) }}" alt="{{ $k->tajuk_kempen }}">
                            @else
                                <div class="kempen-img-placeholder">🕌</div>
                            @endif
                            <div class="kempen-cat-badge">{{ $k->kategori ?? 'Kebajikan' }}</div>
                        </div>
                        <div class="kempen-body">
                            <div class="kempen-org">{{ $k->organisasi?->nama_organisasi ?? 'Organisasi' }}</div>
                            <h3 class="kempen-title">{{ $k->tajuk_kempen }}</h3>
                            <p class="kempen-desc">{{ $k->keterangan_kempen }}</p>
                            
                            <div class="kempen-progress-bar" style="margin-bottom:0.6rem">
                                <div class="kempen-progress-fill" style="width:{{ $k->peratus_kutipan }}%"></div>
                            </div>
                            
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div class="stat-box">
                                    <span class="stat-val">RM {{ number_format($k->jumlah_kutipan_semasa, 0) }}</span>
                                    <span class="stat-lbl">terkumpul dari RM {{ number_format($k->sasaran_dana, 0) }}</span>
                                </div>
                                <div style="font-weight:800; color:var(--em-600); font-size:1.1rem;">
                                    {{ $k->peratus_kutipan }}%
                                </div>
                            </div>

                            <div class="kempen-stats-row">
                                <div style="font-size:0.8rem; color:var(--gray-500); display:flex; gap:1rem;">
                                    <span>👥 {{ $k->derma_count ?? 0 }} penderma</span>
                                    @if($k->tarikh_tamat)
                                        <span>📅 {{ $k->tarikh_tamat->format('d M Y') }}</span>
                                    @endif
                                </div>
                                <span class="btn btn-primary btn-sm" style="padding:0.4rem 1.25rem;">Derma</span>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination" style="margin-top: 3rem;">
                {{ $kempen->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div style="text-align:center; padding: 4rem 2rem; background: #fff; border-radius: 1rem; border: 1px dashed var(--border);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🍃</div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">Tiada Kempen Ditemui</h3>
                <p style="color: var(--gray-500);">Maaf, tiada kempen yang sepadan dengan carian atau kategori anda buat masa ini.</p>
                <a href="{{ route('public.kempen.index') }}" class="btn btn-outline" style="margin-top: 1.5rem;">Tunjuk Semua Kempen</a>
            </div>
        @endif
    </div>
</section>
@endsection
