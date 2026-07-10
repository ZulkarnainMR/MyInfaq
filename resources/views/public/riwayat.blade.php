@extends('layouts.app')
@section('title', 'Riwayat Derma Saya')

@section('content')
<div style="max-width:900px;margin:3rem auto;padding:0 1.5rem">
    <h1 class="section-title" style="margin-bottom:.3rem">📜 Riwayat Derma Saya</h1>
    <p class="section-sub" style="margin-bottom:2rem">Terima kasih atas sumbangan anda. Setiap derma membawa perubahan.</p>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem">
        <div class="card" style="padding:1.25rem;text-align:center">
            <div style="font-size:1.8rem;font-weight:800;color:#0d9488">RM {{ number_format($totalDerma,2) }}</div>
            <div style="font-size:.82rem;color:#64748b;margin-top:.2rem">Jumlah Keseluruhan</div>
        </div>
        <div class="card" style="padding:1.25rem;text-align:center">
            <div style="font-size:1.8rem;font-weight:800;color:#3b82f6">{{ $jumlahKempen }}</div>
            <div style="font-size:.82rem;color:#64748b;margin-top:.2rem">Kempen Disokong</div>
        </div>
        <div class="card" style="padding:1.25rem;text-align:center">
            <div style="font-size:1.8rem;font-weight:800;color:#10b981">{{ $derma->total() }}</div>
            <div style="font-size:.82rem;color:#64748b;margin-top:.2rem">Transaksi Berjaya</div>
        </div>
    </div>

    <!-- History Table -->
    @if($derma->isNotEmpty())
        <div class="card" style="overflow:hidden">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f1f5f9">
                        <th style="padding:.85rem 1rem;text-align:left;font-size:.82rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em">No. Resit</th>
                        <th style="padding:.85rem 1rem;text-align:left;font-size:.82rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em">Kempen</th>
                        <th style="padding:.85rem 1rem;text-align:left;font-size:.82rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em">Tarikh</th>
                        <th style="padding:.85rem 1rem;text-align:left;font-size:.82rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em">Kaedah</th>
                        <th style="padding:.85rem 1rem;text-align:right;font-size:.82rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em">Amaun</th>
                        <th style="padding:.85rem 1rem;text-align:center;font-size:.82rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($derma as $d)
                    <tr style="border-top:1px solid #f1f5f9;transition:background .15s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                        <td style="padding:.85rem 1rem;font-size:.83rem;color:#64748b;font-family:monospace">{{ $d->no_resit }}</td>
                        <td style="padding:.85rem 1rem">
                            <div style="font-weight:600;font-size:.9rem;color:#1e293b">{{ $d->kempen->tajuk_kempen }}</div>
                            <div style="font-size:.76rem;color:#94a3b8">{{ $d->kempen->organisasi?->nama_organisasi }}</div>
                        </td>
                        <td style="padding:.85rem 1rem;font-size:.85rem;color:#64748b">{{ $d->tarikh_derma->format('d M Y') }}</td>
                        <td style="padding:.85rem 1rem;font-size:.85rem;color:#64748b">{{ $d->kaedah_bayaran }}</td>
                        <td style="padding:.85rem 1rem;text-align:right;font-weight:700;color:#0d9488;font-size:.95rem">RM {{ number_format($d->amaun_derma,2) }}</td>
                        <td style="padding:.85rem 1rem;text-align:center;">
                            <a href="{{ route('public.derma.resit', $d) }}" class="btn btn-outline btn-sm" style="padding: .3rem .6rem; font-size: .75rem;" title="Muat Turun Resit">
                                ⬇️ Resit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            @if($derma->onFirstPage()) <span>«</span> @else <a href="{{ $derma->previousPageUrl() }}">«</a> @endif
            @foreach($derma->getUrlRange(1,$derma->lastPage()) as $page=>$url)
                @if($page==$derma->currentPage()) <span class="active">{{$page}}</span>
                @else <a href="{{ $url }}">{{$page}}</a> @endif
            @endforeach
            @if($derma->hasMorePages()) <a href="{{ $derma->nextPageUrl() }}">»</a> @else <span>»</span> @endif
        </div>
    @else
        <div class="card" style="padding:3rem;text-align:center;color:#64748b">
            <div style="font-size:3rem;margin-bottom:1rem">💸</div>
            <p style="font-size:1.05rem;font-weight:600">Anda belum membuat sebarang derma</p>
            <a href="{{ route('public.home') }}" class="btn btn-primary" style="margin-top:1.25rem">Terokai Kempen</a>
        </div>
    @endif
</div>
@endsection
