<?php

namespace App\Filament\Staf\Widgets;

use App\Models\Kempen;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CampaignCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Taburan Kategori Kempen';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '250px';
    
    // Jadikan widget ini ambil separuh ruangan (supaya boleh letak sebelah graf lain)
    // Walau bagaimanapun, ini bergantung kepada konfigurasi lajur utama dashboard
    // protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $categories = Kempen::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kempen',
                    'data' => array_values($categories),
                    'backgroundColor' => [
                        '#0f766e', // Deep Teal
                        '#10b981', // Emerald
                        '#3b82f6', // Blue
                        '#f59e0b', // Amber
                        '#8b5cf6', // Violet
                        '#64748b', // Slate
                    ],
                ],
            ],
            'labels' => array_keys($categories),
        ];
    }

    protected function getType(): string
    {
        // 'doughnut' akan menghasilkan Donut Chart
        return 'doughnut';
    }
}
