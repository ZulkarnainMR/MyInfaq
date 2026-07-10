@extends('layouts.app')
@section('title', 'Pembayaran Tidak Berjaya')

@section('content')
<div style="max-width:560px;margin:4rem auto;padding:0 1.5rem;text-align:center">

    <div style="font-size:5rem;margin-bottom:1.25rem;animation:shake .5s ease-in-out">❌</div>
    <h1 style="font-size:2rem;font-weight:800;color:#1e293b;margin-bottom:.5rem">Pembayaran Tidak Berjaya</h1>
    <p style="color:#64748b;margin-bottom:2rem">
        Maaf, pembayaran anda tidak dapat diproses. Sila cuba sekali lagi atau pilih kaedah pembayaran lain.
    </p>

    <div class="card" style="padding:2rem;text-align:left;margin-bottom:1.5rem">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;color:#dc2626">📋 Butiran Transaksi</h2>
        <table style="width:100%;border-collapse:collapse;font-size:.9rem">
            <tr><td style="padding:.5rem 0;color:#64748b;width:45%">No. Resit</td><td style="font-weight:700">{{ $derma->no_resit }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Kempen</td><td style="font-weight:600">{{ $derma->kempen->tajuk_kempen }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Amaun</td><td style="font-size:1.1rem;font-weight:800;color:#dc2626">RM {{ number_format($derma->amaun_derma, 2) }}</td></tr>
            <tr><td style="padding:.5rem 0;color:#64748b">Status</td>
                <td>
                    <span style="background:#fee2e2;color:#dc2626;padding:.2rem .75rem;border-radius:9999px;font-size:.8rem;font-weight:700">
                        {{ $derma->status_bayaran === 'Pending' ? '⏳ Dalam Proses' : '❌ Gagal' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    @if($derma->status_bayaran === 'Pending')
    <div style="background:#fefce8;border:1px solid #fde68a;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;font-size:.85rem;color:#92400e;text-align:left">
        ⚠️ <strong>Pembayaran masih dalam proses.</strong> Jika wang telah ditolak dari akaun anda, sila tunggu sehingga 5 minit dan semak semula riwayat derma anda.
    </div>
    @endif

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('public.derma.checkout', $derma->kempen) }}" class="btn btn-primary">
            Cuba Semula
        </a>
        <a href="{{ route('public.home') }}" class="btn btn-outline">
            Kembali ke Utama
        </a>
    </div>

    <p style="margin-top:1.5rem;font-size:.8rem;color:#94a3b8">
        Jika anda menghadapi masalah, sila hubungi kami dengan menyertakan No. Resit di atas.
    </p>
</div>
@endsection

@push('styles')
<style>
@keyframes shake {
    0%,100%{ transform:translateX(0) }
    20%{ transform:translateX(-8px) }
    40%{ transform:translateX(8px) }
    60%{ transform:translateX(-5px) }
    80%{ transform:translateX(5px) }
}
</style>
@endpush
