<?php

namespace App\Providers;

use App\Services\ThemeColorService;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Apply theme colors from settings when not running in console
        // or when running tests
        if (!app()->runningInConsole() || app()->runningUnitTests()) {
            try {
                ThemeColorService::applyThemeColors();
            } catch (\Exception $e) {
                // ignore
            }
        }

        // Rekisteröidään taustagradientti / taustaväri head:iin (Filament panel)
        try {
            FilamentView::registerRenderHook(PanelsRenderHook::HEAD_END, function () {
                $backgroundData = \App\Services\ThemeColorService::getBackgroundCss();
                // getBackgroundCss() palauttaa arrayn jossa on 'css' avain
                $backgroundCss = $backgroundData['css'] ?? '';
                // Parannettu sanitointi joka sallii kaikki kelvolliset CSS gradient merkit
                $safeCss = preg_replace('/[^#(),.%a-zA-Z0-9\s:;\-]/', '', $backgroundCss);

                // Luo kattava CSS joka kohdistuu Filament-paneliin
                return "
                <style id=\"app-background-style\">
                    body,
                    .fi-body,
                    .fi-panel-admin,
                    .fi-main-ctn,
                    .fi-main {
                        {$safeCss}
                    }
                    
                    /* Varmista että koko sivu on peitetty */
                    html {
                        {$safeCss}
                    }
                </style>";
            });
        } catch (\Throwable $e) {
            // hiljainen fallback
        }
    }
}
