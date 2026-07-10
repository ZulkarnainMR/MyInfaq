<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->font('Poppins')
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#0f766e'), // Deep Teal
                'gray'    => \Filament\Support\Colors\Color::Slate,
                'info'    => \Filament\Support\Colors\Color::Blue,
                'success' => \Filament\Support\Colors\Color::Emerald,
                'warning' => \Filament\Support\Colors\Color::Orange,
                'danger'  => \Filament\Support\Colors\Color::Rose,
            ])
            ->brandName('MyInfaq Admin')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Laporan::class,
            ])
            ->userMenuItems([
                'logout' => MenuItem::make()->label('Log Keluar'),
            ])
            ->widgets([
              
                \App\Filament\Admin\Widgets\AdminStatsOverview::class,
                \App\Filament\Admin\Widgets\CampaignCategoryChart::class,
                \App\Filament\Admin\Widgets\MonthlyDonationChart::class,
                \App\Filament\Admin\Widgets\LatestDonationsWidget::class ,
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
                \App\Http\Middleware\EnsureIsAdmin::class,
            ]);
    }
}
