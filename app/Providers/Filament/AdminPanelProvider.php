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
            // Nav group order: Content right under Dashboard, Settings at the bottom.
            ->navigationGroups([
                'Content',
                'Businesses',
                'Communications',
                'User Management',
                'Tools',
                'Settings',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
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
                PanelsRenderHook::TOPBAR_END,
                fn () => new HtmlString('
                    <a href="' . url('/') . '" target="_blank" rel="noopener"
                       class="fi-btn fi-btn-size-md inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-primary-600 hover:text-primary-700 hover:bg-gray-50 dark:text-primary-400 dark:hover:bg-white/5"
                       title="Open the public website in a new tab">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span class="hidden sm:inline">Visit Site</span>
                    </a>
                ')
            )
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
                        /* Google Places autocomplete dropdown — keep above Filament modals/repeaters */
                        .pac-container {
                            z-index: 100000 !important;
                            font-family: inherit;
                            border-radius: 0.5rem;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
                            margin-top: 4px;
                        }
                    </style>
                    <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=' . e(config('services.google.maps_browser_key')) . '&libraries=places"></script>
                ')
            );
    }
}
