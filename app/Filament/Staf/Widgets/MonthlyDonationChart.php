<?php

namespace App\Filament\Staf\Widgets;

use App\Models\Derma;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyDonationChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Kutipan Derma (6 Bulan Terkini)';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '310px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        // Loop untuk 6 bulan ke belakang
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            
            $total = Derma::where('status_bayaran', 'Berjaya')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amaun_derma');
                
            $data[] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kutipan (RM)',
                    'data' => $data,
                    'borderColor' => '#0f766e',
                    'backgroundColor' => 'rgba(15, 118, 110, 0.2)', // Deep teal transparent
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
