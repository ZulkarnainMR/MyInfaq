<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\MenuItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StafPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('staf')
            ->path('staf')
            ->font('Poppins')
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0f766e'), // Deep Teal
                'gray'    => \Filament\Support\Colors\Color::Slate,
                'info'    => \Filament\Support\Colors\Color::Blue,
                'success' => \Filament\Support\Colors\Color::Emerald,
                'warning' => \Filament\Support\Colors\Color::Amber, // Lebih lembut dari Orange
                'danger'  => \Filament\Support\Colors\Color::Rose,
            ])
            ->brandName('MyInfaq Staff')
            ->discoverResources(in: app_path('Filament/Staf/Resources'), for: 'App\\Filament\\Staf\\Resources')
            ->discoverPages(in: app_path('Filament/Staf/Pages'), for: 'App\\Filament\\Staf\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Laporan::class,
            ])
            ->userMenuItems([
                'logout' => MenuItem::make()->label('Log Keluar'),
            ])
            ->discoverWidgets(in: app_path('Filament/Staf/Widgets'), for: 'App\\Filament\\Staf\\Widgets')
            ->widgets([
               
                \App\Filament\Staf\Widgets\StafStatsOverview::class,
                \App\Filament\Staf\Widgets\CampaignCategoryChart::class,
                \App\Filament\Staf\Widgets\MonthlyDonationChart::class,
                \App\Filament\Staf\Widgets\LatestDonationsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureIsStaf::class,
            ]);
    }
}
