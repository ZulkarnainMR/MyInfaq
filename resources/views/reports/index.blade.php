@extends('layouts.app')

@section('title', 'Laporan Kutipan Derma')

@section('content')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="section-title">Laporan Kutipan</h1>
            <p class="section-sub">Pantau dan jana laporan sumbangan berdasarkan kempen dan status.</p>
        </div>
        <div>
            <a href="{{ route('laporan.cetak', request()->all()) }}" target="_blank" class="btn btn-outline">
                🖨️ Cetak / Eksport PDF
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="padding: 1.5rem; text-align: center; border-top: 4px solid var(--em-600);">
            <div style="color: var(--gray-500); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Jumlah Kutipan Berjaya</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--em-600);">RM {{ number_format($summary['jumlah_kutipan'], 2) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center; border-top: 4px solid #3b82f6;">
            <div style="color: var(--gray-500); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Transaksi Berjaya</div>
            <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $summary['transaksi_berjaya'] }}</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center; border-top: 4px solid var(--gray-500);">
            <div style="color: var(--gray-500); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Jumlah Transaksi Keseluruhan</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--gray-900);">{{ $summary['jumlah_transaksi'] }}</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
        <form method="GET" action="{{ route('laporan.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Kempen</label>
                <select name="id_kempen" class="form-control">
                    <option value="">Semua Kempen</option>
                    @foreach($senaraiKempen as $k)
                        <option value="{{ $k->id_kempen }}" {{ request('id_kempen') == $k->id_kempen ? 'selected' : '' }}>
                            {{ $k->tajuk_kempen }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $b)
                        <option value="{{ sprintf('%02d', $b) }}" {{ request('bulan') == sprintf('%02d', $b) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $b, 10)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y'), date('Y') - 5) as $t)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="form-label">Status Bayaran</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Berjaya" {{ request('status') == 'Berjaya' ? 'selected' : '' }}>Berjaya</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Gagal" {{ request('status') == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="height: 44px;">🔍 Tapis Data</button>
                <a href="{{ route('laporan.index') }}" class="btn btn-outline" style="height: 44px; margin-left: 0.5rem;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="card" style="overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: var(--gray-100); border-bottom: 2px solid var(--border);">
                    <tr>
                        <th style="padding: 1rem; font-weight: 600; color: var(--gray-600);">No. Resit</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--gray-600);">Tarikh</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--gray-600);">Penderma</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--gray-600);">Kempen</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--gray-600);">Status</th>
                        <th style="padding: 1rem; font-weight: 600; color: var(--gray-600); text-align: right;">Amaun (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem; color: var(--gray-900); font-weight: 500;">
                                {{ $row->no_resit ?? '-' }}
                            </td>
                            <td style="padding: 1rem; color: var(--gray-600);">
                                {{ date('d/m/Y H:i', strtotime($row->tarikh_derma)) }}
                            </td>
                            <td style="padding: 1rem; color: var(--gray-900);">
                                {{ $row->nama_penderma }}
                            </td>
                            <td style="padding: 1rem; color: var(--gray-600);">
                                <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $row->tajuk_kempen }}">
                                    {{ $row->tajuk_kempen }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--em-600);">{{ $row->nama_organisasi }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                @if($row->status_bayaran === 'Berjaya')
                                    <span class="badge badge-success">Berjaya</span>
                                @elseif($row->status_bayaran === 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Gagal</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: right; font-weight: 700; color: var(--gray-900);">
                                {{ number_format($row->amaun_derma, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: var(--gray-500);">
                                Tiada rekod derma dijumpai berdasarkan carian anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
