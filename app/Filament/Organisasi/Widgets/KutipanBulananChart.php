<?php

namespace App\Filament\Organisasi\Widgets;

use App\Models\Derma;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KutipanBulananChart extends ChartWidget
{
    protected static ?string $heading = 'Carta Kutipan Bulanan (Tahun Semasa)';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $organisasi = Auth::user()->organisasi;
        
        if (!$organisasi) {
            return [];
        }
        
        $organisasiId = $organisasi->id_organisasi;
        $tahunSemasa = Carbon::now()->year;
        
        $derma = Derma::whereHas('kempen', function ($query) use ($organisasiId) {
                $query->where('id_organisasi', $organisasiId);
            })
            ->where('status_bayaran', 'Berjaya')
            ->whereYear('tarikh_derma', $tahunSemasa)
            ->get();

        $kutipanBulanan = array_fill(1, 12, 0);

        foreach ($derma as $d) {
            $bulan = Carbon::parse($d->tarikh_derma)->month;
            $kutipanBulanan[$bulan] += $d->amaun_derma;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kutipan (RM)',
                    'data' => array_values($kutipanBulanan),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogo', 'Sep', 'Okt', 'Nov', 'Dis'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
