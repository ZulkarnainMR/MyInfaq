<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureIsOrganisasi;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
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

class OrganisasiPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('organisasi')
            ->path('organisasi')
            ->colors([
                'primary' => Color::Blue,
                'gray'    => Color::Zinc,
            ])
            ->brandName('MyInfaq – Portal Organisasi')
            ->discoverResources(in: app_path('Filament/Organisasi/Resources'), for: 'App\\Filament\\Organisasi\\Resources')
            ->discoverPages(in: app_path('Filament/Organisasi/Pages'), for: 'App\\Filament\\Organisasi\\Pages')
            ->discoverWidgets(in: app_path('Filament/Organisasi/Widgets'), for: 'App\\Filament\\Organisasi\\Widgets')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Laporan::class,
            ])
            ->userMenuItems([
                'logout' => MenuItem::make()->label('Log Keluar'),
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
                EnsureIsOrganisasi::class,
                \App\Http\Middleware\EnsureOrganisasiIsPaid::class,
            ]);
    }
}
