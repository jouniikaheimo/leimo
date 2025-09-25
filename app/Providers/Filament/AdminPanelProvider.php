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
use Filament\Support\Enums\Width;
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
            ->colors([
                'primary' => Color::Amber,
            ])
            ->topNavigation()
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/admin/theme.css')
            // Use render hooks instead of assets for theme colors
            ->renderHook(
                'panels::body.start',
                fn (): string => $this->getThemeColorsStyles()
            )
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
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                AuthenticateSession::class,
            ]);
    }

    /**
     * Generate inline CSS for theme colors
     */
    private function getThemeColorsStyles(): string
    {
        try {
            $colors = \App\Services\ThemeColorService::getAllColors();
            $opacities = \App\Services\ThemeColorService::getOpacitySettings();

            $primaryRgb = $this->hexToRgb($colors['primary']);
            $secondaryRgb = $this->hexToRgb($colors['secondary']);
            $accentRgb = $this->hexToRgb($colors['accent']);
            $successRgb = $this->hexToRgb($colors['success']);
            $warningRgb = $this->hexToRgb($colors['warning']);
            $dangerRgb = $this->hexToRgb($colors['danger']);

            // Get dynamic opacity values
            $tabOpacity = $opacities['tab_background_opacity'];
            $mainOpacity = $opacities['main_background_opacity'];
            $sidebarOpacity = $opacities['sidebar_opacity'];

            return "
            <style>
                :root {
                    --theme-primary: {$primaryRgb};
                    --theme-secondary: {$secondaryRgb};
                    --theme-accent: {$accentRgb};
                    --theme-success: {$successRgb};
                    --theme-warning: {$warningRgb};
                    --theme-danger: {$dangerRgb};
                    --tab-opacity: {$tabOpacity};
                    --main-opacity: {$mainOpacity};
                    --sidebar-opacity: {$sidebarOpacity};
                }
                
                /* Tab content background with dynamic opacity */
                .tab-content-bg {
                    background: rgba({$accentRgb}, {$tabOpacity}) !important;
                }
                
                .tab-content-bg-primary {
                    background: rgba({$primaryRgb}, {$tabOpacity}) !important;
                }
                
                .tab-content-bg-secondary {
                    background: rgba({$secondaryRgb}, {$tabOpacity}) !important;
                }
                
                /* Debug classes with current opacity */
                .debug-accent-current { background: rgba({$accentRgb}, {$tabOpacity}) !important; }
                .debug-accent-full { background: rgb({$accentRgb}) !important; }
                .debug-accent-50 { background: rgba({$accentRgb}, 0.5) !important; }
                .debug-accent-20 { background: rgba({$accentRgb}, 0.2) !important; }
                .debug-accent-10 { background: rgba({$accentRgb}, 0.1) !important; }
                .debug-accent-08 { background: rgba({$accentRgb}, 0.08) !important; }
                .debug-accent-05 { background: rgba({$accentRgb}, 0.05) !important; }
                
               
                .fi-sidebar {
                    background: linear-gradient(180deg, 
                        rgba({$primaryRgb}, {$sidebarOpacity}) 0%, 
                        rgba({$secondaryRgb}, " . ($sidebarOpacity * 0.95) . ") 100%) !important;
                    border-color: rgba({$primaryRgb}, 0.2) !important;
                }
                
                .fi-btn-primary {
                    background: rgb({$primaryRgb}) !important;
                    border-color: rgb({$primaryRgb}) !important;
                }
                
                .fi-btn-primary:hover {
                    background: rgba({$primaryRgb}, 0.9) !important;
                }
                
                .fi-sidebar-nav-item:hover {
                    background: rgba({$primaryRgb}, 0.1) !important;
                }
                
                .fi-sidebar-nav-item[aria-current=\"page\"] {
                    background: rgba({$primaryRgb}, 0.2) !important;
                    border-color: rgb({$primaryRgb}) !important;
                }
                
                .theme-primary-bg { background-color: rgb({$primaryRgb}) !important; }
                .theme-primary-hover:hover { background-color: rgba({$primaryRgb}, 0.8) !important; }
                .theme-primary-text { color: rgb({$primaryRgb}) !important; }
                
                .theme-success-bg { background-color: rgba({$successRgb}, 0.1) !important; }
                .theme-success-text { color: rgba({$successRgb}, 0.8) !important; }
                
                .theme-warning-bg { background-color: rgba({$warningRgb}, 0.1) !important; }
                .theme-warning-text { color: rgba({$warningRgb}, 0.8) !important; }
                
                .theme-danger-bg { background-color: rgba({$dangerRgb}, 0.1) !important; }
                .theme-danger-text { color: rgba({$dangerRgb}, 0.8) !important; }
                
                .dark .fi-panel-admin,
                .dark .fi-main-ctn,
                .dark .fi-main {
                    background: linear-gradient(135deg, 
                        rgba({$primaryRgb}, " . ($mainOpacity * 2.67) . ") 0%, 
                        rgba({$secondaryRgb}, " . ($mainOpacity * 2) . ") 50%, 
                        rgba({$accentRgb}, " . ($mainOpacity * 2.67) . ") 100%) !important;
                }
            </style>
            ";
        } catch (\Exception $e) {
            return '<style>/* Theme colors not available */</style>';
        }
    }

    /**
     * Convert hex to RGB string
     */
    private function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "{$r}, {$g}, {$b}";
    }
}
