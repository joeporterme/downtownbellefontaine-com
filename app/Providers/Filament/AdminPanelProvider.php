<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('images/logo.svg'))
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => [
                    50 => '#e6f2f3',
                    100 => '#cce5e8',
                    200 => '#99cbd0',
                    300 => '#66b1b9',
                    400 => '#3397a1',
                    500 => '#01757f',
                    600 => '#016069',
                    700 => '#014b52',
                    800 => '#003d45',
                    900 => '#002e34',
                    950 => '#001f23',
                ],
                'danger' => [
                    50 => '#fdf2f2',
                    100 => '#fce4e4',
                    200 => '#f9c9c9',
                    300 => '#f3a0a2',
                    400 => '#ea6b6e',
                    500 => '#88292f',
                    600 => '#772428',
                    700 => '#661e22',
                    800 => '#55191c',
                    900 => '#441416',
                    950 => '#330f10',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => new HtmlString('
                    <style>
                        /* TipTap/ProseMirror editor - make taller and resizable */
                        .fi-fo-rich-editor-main {
                            min-height: 400px !important;
                            resize: vertical !important;
                            overflow: auto !important;
                        }
                        .fi-fo-rich-editor-content {
                            min-height: 380px !important;
                        }
                        .fi-fo-rich-editor-content .ProseMirror {
                            min-height: 360px !important;
                        }
                    </style>
                ')
            );
    }
}
