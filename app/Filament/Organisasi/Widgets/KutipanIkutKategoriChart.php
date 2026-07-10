<?php

namespace App\Filament\Organisasi\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KutipanIkutKategoriChart extends ChartWidget
{
    protected static ?string $heading = 'Kutipan Mengikut Kategori Kempen';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $organisasi = Auth::user()->organisasi;
        
        if (!$organisasi) {
            return [];
        }

        $organisasiId = $organisasi->id_organisasi;

        // Dapatkan jumlah kutipan (derma berjaya) dikumpulkan mengikut kategori kempen
        $kategoriKutipan = DB::table('tbl_derma')
            ->join('tbl_kempen', 'tbl_derma.id_kempen', '=', 'tbl_kempen.id_kempen')
            ->where('tbl_kempen.id_organisasi', $organisasiId)
            ->where('tbl_derma.status_bayaran', 'Berjaya')
            ->select('tbl_kempen.kategori', DB::raw('SUM(tbl_derma.amaun_derma) as total'))
            ->groupBy('tbl_kempen.kategori')
            ->get();

        $labels = [];
        $data = [];
        // Menyediakan beberapa warna lalai yang cantik untuk doughnut chart
        $backgroundColors = ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#E7E9ED'];
        $chartColors = [];

        foreach ($kategoriKutipan as $index => $row) {
            $kategori = trim($row->kategori);
            $labels[] = empty($kategori) ? 'Umum / Lain-lain' : $kategori;
            $data[] = (float) $row->total;
            // Kitar semula warna jika kategori lebih daripada pilihan warna sedia ada
            $chartColors[] = $backgroundColors[$index % count($backgroundColors)];
        }

        // Jika tiada data, kita masukkan data dummy kosong supaya carta tidak ralat
        if (empty($labels)) {
            $labels = ['Tiada Data'];
            $data = [0];
            $chartColors = ['#E7E9ED'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kutipan Mengikut Kategori (RM)',
                    'data' => $data,
                    'backgroundColor' => $chartColors,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected static ?string $maxHeight = '250px';

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
            ],
            'maintainAspectRatio' => false,
            'cutout' => '65%',
            'layout' => [
                'padding' => 15,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
