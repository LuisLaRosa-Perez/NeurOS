<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PsicologoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('psicologo')
            ->path('psicologo')
            ->colors([
                'primary' => Color::Cyan, // Color principal celeste
            ])
            ->discoverResources(
                in: app_path('Filament/Psicologo/Resources'),
                for: 'App\\Filament\\Psicologo\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Psicologo/Pages'),
                for: 'App\\Filament\\Psicologo\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Psicologo/Widgets'),
                for: 'App\\Filament\\Psicologo\\Widgets'
            )
            ->widgets([])

            // --- Configuración de autenticación y seguridad ---
            ->authGuard('web')
            ->login()
            ->authMiddleware([
                AuthenticateSession::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            // --- Verificación de rol del usuario ---
            ->bootUsing(function () {
                if (!auth()->check() || !auth()->user()->hasRole('psicologo')) {
                    abort(403, 'Acceso denegado. Este panel es exclusivo para psicólogos.');
                }
            });
    }
}
