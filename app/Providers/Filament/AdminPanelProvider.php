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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->path('dash')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.webp'))
            ->brandLogoHeight('3rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
                fn (): HtmlString => new HtmlString('
                    <style>
                        /* Container styles for the horizontal scrolling repeater */
                        .admin-marquee-repeater .fi-fo-repeater-items {
                            display: flex !important;
                            flex-direction: row !important;
                            overflow-x: auto !important;
                            flex-wrap: nowrap !important;
                            gap: 1.25rem !important;
                            padding: 0.5rem 0.5rem 1.25rem 0.5rem !important;
                            scroll-behavior: smooth;
                            -webkit-overflow-scrolling: touch;
                            width: 100% !important;
                        }

                        /* Individual card items inside the marquee repeater */
                        .admin-marquee-repeater .fi-fo-repeater-item {
                            flex: 0 0 280px !important;
                            min-width: 280px !important;
                            max-width: 280px !important;
                            margin: 0 !important;
                            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
                            border-radius: 0.75rem !important;
                            transition: all 0.2s ease-in-out !important;
                            background: white !important;
                        }

                        .dark .admin-marquee-repeater .fi-fo-repeater-item {
                            background: rgb(17 24 39) !important; /* dark:bg-gray-900 */
                            border-color: rgb(37 99 235 / 0.1) !important;
                        }

                        /* Add a subtle hover effect on cards for premium dynamic feel */
                        .admin-marquee-repeater .fi-fo-repeater-item:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
                        }

                        /* Custom horizontal scrollbar for premium aesthetics */
                        .admin-marquee-repeater .fi-fo-repeater-items::-webkit-scrollbar {
                            height: 8px;
                        }
                        .admin-marquee-repeater .fi-fo-repeater-items::-webkit-scrollbar-track {
                            background: transparent;
                        }
                        .admin-marquee-repeater .fi-fo-repeater-items::-webkit-scrollbar-thumb {
                            background: #cbd5e1;
                            border-radius: 9999px;
                        }
                        .dark .admin-marquee-repeater .fi-fo-repeater-items::-webkit-scrollbar-thumb {
                            background: #475569;
                        }
                        .admin-marquee-repeater .fi-fo-repeater-items::-webkit-scrollbar-thumb:hover {
                            background: #94a3b8;
                        }
                        .dark .admin-marquee-repeater .fi-fo-repeater-items::-webkit-scrollbar-thumb:hover {
                            background: #334155;
                        }
                        
                        /* Make FileUpload within horizontal repeater more compact and aesthetic */
                        .admin-marquee-repeater .fi-fo-repeater-item .filepond--root {
                            min-height: 120px !important;
                        }
                    </style>
                ')
            );
    }
}
