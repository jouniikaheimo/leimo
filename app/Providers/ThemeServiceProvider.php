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
                $bg = \App\Services\ThemeColorService::getBackgroundCss();
                $css = preg_replace('/[^#(),.%a-zA-Z0-9\s:-]/', '', $bg['css']);
                // Luo lopullinen style - $css sisältää esim. "background: linear-gradient(...);"
                return "<style id=\"app-background-style\">body{ {$css} } .fi-body{ {$css} }</style>";
            });
        } catch (\Throwable $e) {
            // hiljainen fallback
        }
    }
}
