<?php

namespace App\Filament\Staf\Widgets;

use App\Models\Derma;
use App\Models\Kempen;
use App\Models\Penderma;
use App\Models\Organisasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StafStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = '2';

    protected function getStats(): array
    {
        $totalKutipan = Derma::where('status_bayaran', 'Berjaya')->sum('amaun_derma');
        $kempenAktif  = Kempen::where('status_kempen', 'Aktif')->count();
        $kempenPending = Kempen::where('status_kempen', 'Pending')->count();
        $kempenSelesai = Kempen::whereIn('status_kempen', ['Selesai', 'Dibayar'])->count();
        $jumlahPenderma = Penderma::count();
        $jumlahOrganisasi = Organisasi::count();

        // Dapatkan data trend untuk 7 hari lepas bagi kutipan derma
        $dermaTrend = Derma::where('status_bayaran', 'Berjaya')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, sum(amaun_derma) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();

        // Jika tiada data, letak array kosong berbanding ralat
        if (empty($dermaTrend)) {
            $dermaTrend = [0, 0, 0, 0, 0, 0, 0];
        }

        return [
            Stat::make('Jumlah Kutipan', 'RM ' . number_format($totalKutipan, 2))
                ->description('Kutipan berjaya')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($dermaTrend)
                ->color('success'),

            Stat::make('Kempen Aktif', $kempenAktif)
                ->description('Sedang berjalan')
                ->descriptionIcon('heroicon-m-megaphone')
                ->chart([1, 4, 3, 5, 8, $kempenAktif])
                ->color('info'),

            Stat::make('Kempen Pending', $kempenPending)
                ->description('Menunggu kelulusan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Kempen Selesai', $kempenSelesai)
                ->description('Telah tamat / dibayar')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([1, 2, 4, 5, 7, $kempenSelesai])
                ->color('success'),

            Stat::make('Jumlah Pengguna', $jumlahPenderma + $jumlahOrganisasi)
                ->description('Penderma & Organisasi')
                ->descriptionIcon('heroicon-m-users')
                ->chart([2, 5, 10, 15, 20, $jumlahPenderma + $jumlahOrganisasi])
                ->color('primary'),
        ];
    }
}
