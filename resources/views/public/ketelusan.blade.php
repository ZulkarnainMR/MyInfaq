@extends('layouts.app')
@section('title', 'Ketelusan & Sejarah Derma')

@push('styles')
<style>
.ketelusan-hero{background:linear-gradient(135deg,#0f172a,#1e3a5f);padding:3.5rem 0;color:#fff;text-align:center}
.ketelusan-hero h1{font-size:2.2rem;font-weight:800;margin-bottom:.5rem}
.ketelusan-hero p{color:#94a3b8;font-size:1rem;max-width:560px;margin:0 auto}
.kempen-selesai{margin-top:2.5rem;display:flex;flex-direction:column;gap:2rem}
.ketelusan-card{border-radius:1rem;overflow:hidden;background:#fff;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,0,0,.05)}
.ketelusan-header{background:linear-gradient(135deg,#0f766e,#0369a1);padding:1.5rem;color:#fff;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem}
.ketelusan-body{padding:1.75rem}
.gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem;margin-top:1rem}
.gallery img{width:100%;height:130px;object-fit:cover;border-radius:.625rem;border:2px solid #e2e8f0;transition:transform .2s}
.gallery img:hover{transform:scale(1.04)}
.timeline-entry{border-left:3px solid #0d9488;padding:.85rem 1.25rem;margin-bottom:1rem;background:#f0fdfa;border-radius:0 .75rem .75rem 0}
</style>
@endpush

@section('content')
<section class="ketelusan-hero">
    <div class="container">
        <h1>🔍 Halaman Ketelusan</h1>
        <p>Semua dana yang dihimpun diagihkan dengan telus. Lihat bukti agihan, penerima manfaat, dan dokumentasi lengkap kempen yang telah selesai.</p>
    </div>
</section>
<section style="padding:3rem 0">
    <div class="container">
        @if($kempenSelesai->isNotEmpty())
            <div class="kempen-selesai">
                @foreach($kempenSelesai as $kempen)
                <div class="ketelusan-card">
                    <div class="ketelusan-header">
                        <div>
                            <div style="font-size:.75rem;color:rgba(255,255,255,.7);margin-bottom:.2rem">{{ $kempen->organisasi?->nama_organisasi }}</div>
                            <h2 style="font-size:1.2rem;font-weight:700">{{ $kempen->tajuk_kempen }}</h2>
                        </div>
                        <div style="text-align:right">
                            <div style="font-size:1.5rem;font-weight:800">RM {{ number_format($kempen->jumlah_kutipan_semasa,0) }}</div>
                            <div style="font-size:.78rem;color:rgba(255,255,255,.7)">Jumlah Terkumpul</div>
                            <span class="badge badge-success" style="margin-top:.4rem">{{ $kempen->status_kempen }}</span>
                        </div>
                    </div>
                    <div class="ketelusan-body">
                        @if($kempen->ketelusan->isNotEmpty())
                            <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:1rem">📋 Laporan Agihan Dana</h3>
                            @foreach($kempen->ketelusan as $laporan)
                            <div class="timeline-entry">
                                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:.5rem">
                                    <div>
                                        <span style="font-weight:700;font-size:.95rem">{{ $laporan->tajuk_laporan ?? 'Laporan Agihan' }}</span>
                                        <span style="font-size:.78rem;color:#64748b;margin-left:.5rem">{{ $laporan->tarikh_agihan->format('d M Y') }}</span>
                                    </div>
                                    <span style="font-size:.8rem;background:#d1fae5;color:#065f46;padding:.2rem .6rem;border-radius:9999px;font-weight:600">{{ $laporan->bilangan_penerima }} penerima</span>
                                </div>
                                <p style="font-size:.88rem;color:#334155;line-height:1.7;margin-bottom:.75rem">{{ $laporan->keterangan_penerima }}</p>
                                @if($laporan->gambar_agihan)
                                    <div class="gallery">
                                        @foreach($laporan->gambar_agihan as $img)
                                            <img src="{{ asset('storage/'.$img) }}" alt="Gambar agihan" loading="lazy">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <p style="color:#94a3b8;font-size:.9rem;font-style:italic">Laporan ketelusan akan dikemaskini tidak lama lagi.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination" style="margin-top:2.5rem">
                @if($kempenSelesai->onFirstPage()) <span>«</span> @else <a href="{{ $kempenSelesai->previousPageUrl() }}">«</a> @endif
                @foreach($kempenSelesai->getUrlRange(1,$kempenSelesai->lastPage()) as $page=>$url)
                    @if($page==$kempenSelesai->currentPage()) <span class="active">{{$page}}</span>
                    @else <a href="{{ $url }}">{{$page}}</a> @endif
                @endforeach
                @if($kempenSelesai->hasMorePages()) <a href="{{ $kempenSelesai->nextPageUrl() }}">»</a> @else <span>»</span> @endif
            </div>
        @else
            <div style="text-align:center;padding:4rem 0;color:#64748b">
                <div style="font-size:3rem;margin-bottom:1rem">📋</div>
                <p style="font-size:1.1rem;font-weight:600">Belum ada kempen selesai</p>
                <p style="margin-top:.5rem">Kempen yang berjaya akan dipaparkan di sini bersama laporan ketelusan.</p>
                <a href="{{ route('public.home') }}" class="btn btn-primary" style="margin-top:1.5rem">Lihat Kempen Aktif</a>
            </div>
        @endif
    </div>
</section>
@endsection
