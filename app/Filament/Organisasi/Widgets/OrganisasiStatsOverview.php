<?php

namespace App\Filament\Organisasi\Widgets;

use App\Models\Kempen;
use App\Models\Derma;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrganisasiStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $organisasi = Auth::user()->organisasi;
        
        // Ensure user is an organization
        if (!$organisasi) {
            return [];
        }
        
        $organisasiId = $organisasi->id_organisasi;

        // 1. Jumlah Kutipan Keseluruhan
        $jumlahKutipan = Kempen::where('id_organisasi', $organisasiId)
            ->sum('jumlah_kutipan_semasa');

        // 2. Kutipan Bulan Semasa
        $kutipanBulanIni = Derma::whereHas('kempen', function ($query) use ($organisasiId) {
                $query->where('id_organisasi', $organisasiId);
            })
            ->where('status_bayaran', 'Berjaya')
            ->whereMonth('tarikh_derma', Carbon::now()->month)
            ->whereYear('tarikh_derma', Carbon::now()->year)
            ->sum('amaun_derma');

        // 3. Kempen Aktif
        $kempenAktif = Kempen::where('id_organisasi', $organisasiId)
            ->where('status_kempen', 'Aktif')
            ->count();

        // 4. Jumlah Penderma
        $jumlahPenderma = Derma::whereHas('kempen', function ($query) use ($organisasiId) {
                $query->where('id_organisasi', $organisasiId);
            })
            ->where('status_bayaran', 'Berjaya')
            ->distinct('id_penderma')
            ->count('id_penderma');

        return [
            Stat::make('Jumlah Kutipan Keseluruhan', 'RM ' . number_format($jumlahKutipan, 2))
                ->description('Merentas semua kempen')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Kutipan Bulan Ini', 'RM ' . number_format($kutipanBulanIni, 2))
                ->description(Carbon::now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make('Kempen Aktif', $kempenAktif)
                ->description('Kempen sedang berjalan')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('warning'),

            Stat::make('Jumlah Penderma', $jumlahPenderma)
                ->description('Penderma unik pada semua kempen')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
