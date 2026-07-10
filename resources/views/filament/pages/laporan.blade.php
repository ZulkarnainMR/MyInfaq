<x-filament-panels::page>
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
        <a href="{{ route('laporan.cetak', ['bulan' => $bulan, 'tahun' => $tahun, 'status' => $status, 'id_kempen' => $id_kempen]) }}" target="_blank" style="padding: 0.6rem 1.2rem; border-radius: 0.5rem; background-color: #047857; color: white; text-decoration: none; font-weight: 700; border: 2px solid #064e3b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); display: inline-flex; align-items: center; gap: 0.5rem;">
            🖨️ Cetak / Eksport PDF
        </a>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="padding: 1.5rem; text-align: center; border-radius: 0.5rem; background: var(--white, white); border-top: 4px solid var(--primary-600, #059669); box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);">
            <div style="color: gray; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Jumlah Kutipan Berjaya</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--primary-600, #059669);">RM {{ number_format($summary['jumlah_kutipan'], 2) }}</div>
        </div>
        <div style="padding: 1.5rem; text-align: center; border-radius: 0.5rem; background: var(--white, white); border-top: 4px solid #3b82f6; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);">
            <div style="color: gray; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Transaksi Berjaya</div>
            <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $summary['transaksi_berjaya'] }}</div>
        </div>
        <div style="padding: 1.5rem; text-align: center; border-radius: 0.5rem; background: var(--white, white); border-top: 4px solid gray; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);">
            <div style="color: gray; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">Jumlah Transaksi Keseluruhan</div>
            <div style="font-size: 2rem; font-weight: 800; color: #111827;">{{ $summary['jumlah_transaksi'] }}</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div style="padding: 1.5rem; margin-bottom: 2rem; background: var(--white, white); border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; color: gray;">Kempen</label>
                <select wire:model.live="id_kempen" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Kempen</option>
                    @foreach($senaraiKempen as $k)
                        <option value="{{ $k->id_kempen }}">
                            {{ $k->tajuk_kempen }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; color: gray;">Bulan</label>
                <select wire:model.live="bulan" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $b)
                        <option value="{{ sprintf('%02d', $b) }}">
                            {{ date('F', mktime(0, 0, 0, $b, 10)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; color: gray;">Tahun</label>
                <select wire:model.live="tahun" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y'), date('Y') - 5) as $t)
                        <option value="{{ $t }}">
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; color: gray;">Status Bayaran</label>
                <select wire:model.live="status" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Status</option>
                    <option value="Berjaya">Berjaya</option>
                    <option value="Pending">Pending</option>
                    <option value="Gagal">Gagal</option>
                </select>
            </div>
            <div>
                <button wire:click="$set('bulan', ''); $set('tahun', ''); $set('status', ''); $set('id_kempen', '');" style="padding: 0.5rem 1rem; border-radius: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; font-weight: 600; cursor: pointer; color: #111827;">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div style="background: var(--white, white); border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; color: #111827;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 1rem; font-weight: 600; color: #4b5563;">No. Resit</th>
                        <th style="padding: 1rem; font-weight: 600; color: #4b5563;">Tarikh</th>
                        <th style="padding: 1rem; font-weight: 600; color: #4b5563;">Penderma</th>
                        <th style="padding: 1rem; font-weight: 600; color: #4b5563;">Kempen</th>
                        <th style="padding: 1rem; font-weight: 600; color: #4b5563;">Status</th>
                        <th style="padding: 1rem; font-weight: 600; color: #4b5563; text-align: right;">Amaun (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 1rem; font-weight: 500;">
                                {{ $row->no_resit ?? '-' }}
                            </td>
                            <td style="padding: 1rem; color: #4b5563;">
                                {{ date('d/m/Y H:i', strtotime($row->tarikh_derma)) }}
                            </td>
                            <td style="padding: 1rem;">
                                {{ $row->nama_penderma }}
                            </td>
                            <td style="padding: 1rem; color: #4b5563;">
                                <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $row->tajuk_kempen }}">
                                    {{ $row->tajuk_kempen }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--primary-600, #059669);">{{ $row->nama_organisasi }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                @if($row->status_bayaran === 'Berjaya')
                                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Berjaya</span>
                                @elseif($row->status_bayaran === 'Pending')
                                    <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Pending</span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Gagal</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: right; font-weight: 700;">
                                {{ number_format($row->amaun_derma, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">
                                Tiada rekod derma dijumpai berdasarkan carian anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
