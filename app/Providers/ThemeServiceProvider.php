<?php

namespace App\Providers;

use App\Services\ThemeColorService;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Varmista että sovellus on valmis ennen teeman käyttöönottoa
        $this->app->booted(function () {
            try {
                // Vältä teeman käyttöönottoa konsolikomentojen aikana
                if (!app()->runningInConsole() || app()->runningUnitTests()) {
                    ThemeColorService::applyThemeColors();
                    $this->registerThemeHooks();
                }
            } catch (\Exception $e) {
                // Kirjaa virhe ilman sivun kaatumista
                \Log::error('ThemeServiceProvider boot error: ' . $e->getMessage());
            }
        });
    }

    /**
     * Rekisteröi teeman render-hookit
     */
    private function registerThemeHooks(): void
    {
        // Rekisteröi CSS-tyylit head:iin
        try {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                function () {
                    return $this->getThemeStyles();
                }
            );

            FilamentView::registerRenderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                function () {
                    return view('filament.components.language-switcher');
                }
            );
        } catch (\Throwable $e) {
            \Log::error('Failed to register theme hooks: ' . $e->getMessage());
        }
    }

    /**
     * Luo teemakoodi CSS:lle
     */
    private function getThemeStyles(): string
    {
        try {
            // Hae värit turvallisesti
            $colors = ThemeColorService::getAllColors();
            $backgroundData = ThemeColorService::getBackgroundCss();
            $backgroundCss = $backgroundData['css'] ?? '';

            // Sanitize background CSS
            $safeBackgroundCss = $this->sanitizeCss($backgroundCss);

            // Luo CSS-tyylit
            $css = $this->buildThemeCss($colors, $safeBackgroundCss);

            return "<style id=\"filament-theme-styles\">{$css}</style>";
        } catch (\Exception $e) {
            \Log::error('Error building theme styles: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Rakenna teemakoodi CSS:lle
     */
    private function buildThemeCss(array $colors, string $backgroundCss): string
    {
        $primaryColor = $colors['primary'];
        $secondaryColor = $colors['secondary'];
        $textColor = $colors['section_text_color'];
        $accentColor = $colors['accent'];
        $successColor = $colors['success'];
        $warningColor = $colors['warning'];
        $dangerColor = $colors['danger'];
        $topbarBgColor = $colors['topbar_bg'];
        $topbarTextColor = $colors['topbar_text'];
        $topbarLinkColor = $colors['topbar_link'];

        // Muunna hex-värit RGB-muotoon
        $primaryRgb = $this->hexToRgb($primaryColor);
        $secondaryRgb = $this->hexToRgb($secondaryColor);
        $accentRgb = $this->hexToRgb($accentColor);
        $successRgb = $this->hexToRgb($successColor);
        $warningRgb = $this->hexToRgb($warningColor);
        $dangerRgb = $this->hexToRgb($dangerColor);
        $topbarBgRgb = $this->hexToRgb($topbarBgColor);

        // Luo CSS-muuttujat
        $cssVariables = $this->buildCssVariables([
                                                     'primary' => $primaryColor,
                                                     'secondary' => $secondaryColor,
                                                     'accent' => $accentColor,
                                                     'success' => $successColor,
                                                     'warning' => $warningColor,
                                                     'danger' => $dangerColor,
                                                     'section_text_color' => $textColor,
                                                     'topbar_bg' => $topbarBgColor,
                                                     'topbar_text' => $topbarTextColor,
                                                     'topbar_link' => $topbarLinkColor,
                                                 ]);

        return "
        :root {
            {$cssVariables}
        }

        /* Pääsivun tausta */
        .fi-panel-admin,
        .fi-main-ctn,
        .fi-main {
            {$backgroundCss}
        }

        /* Filament sivupalkki */
        .fi-sidebar {
            background: linear-gradient(180deg,
                rgb(var(--theme-primary) / 0.95) 0%,
                rgb(var(--theme-secondary) / 0.9) 100%) !important;
            border-color: rgb(var(--theme-primary) / 0.2) !important;
        }

        /* Filament header */
        .fi-topbar {
            background: {$topbarBgColor} !important;
            border-color: rgb(var(--theme-primary) / 0.1) !important;
            color: {$topbarTextColor} !important;
            backdrop-filter: blur(8px);
        }

        .fi-topbar .fi-topbar-ctn,
        .fi-topbar .fi-topbar-nav-groups,
        .fi-topbar .fi-topbar-item,
        .fi-topbar .fi-topbar-item-btn,
        .fi-topbar .fi-topbar-item.fi-active,
        .fi-topbar a,
        .fi-topbar button,
        .fi-topbar span,
        .fi-topbar label,
        .fi-topbar select {
            color: {$topbarLinkColor} !important;
        }

        .fi-topbar .fi-topbar-item.fi-active,
        .fi-topbar .fi-topbar-item-btn:hover,
        .fi-topbar .fi-topbar-item:hover,
        .fi-topbar a:hover,
        .fi-topbar button:hover {
            color: {$topbarTextColor} !important;
        }

        /* Navigation items */
        .fi-sidebar-nav-item:hover {
            background: rgb(var(--theme-primary) / 0.1) !important;
        }

        .fi-sidebar-nav-item[aria-current=\"page\"] {
            background: rgb(var(--theme-primary) / 0.2) !important;
            border-color: rgb(var(--theme-primary)) !important;
        }

        /* Button theming */
        .fi-btn-primary {
            background: rgb(var(--theme-primary)) !important;
            border-color: rgb(var(--theme-primary)) !important;
        }

        .fi-btn-primary:hover {
            background: rgb(var(--theme-primary) / 0.9) !important;
        }

        /* Form fields */
        .fi-input {
            border-color: rgb(var(--theme-primary) / 0.2) !important;
        }

        .fi-input:focus {
            border-color: rgb(var(--theme-primary)) !important;
            box-shadow: 0 0 0 1px rgb(var(--theme-primary) / 0.3) !important;
        }

        /* Cards and sections */
        .fi-section,
        .fi-card {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgb(var(--theme-primary) / 0.1) !important;
            backdrop-filter: blur(4px);
        }

        /* Dark mode support */
        .dark .fi-panel-admin,
        .dark .fi-main-ctn,
        .dark .fi-main {
            {$backgroundCss}
        }

        /* Utility classes */
        .theme-primary-bg { background-color: rgb(var(--theme-primary)) !important; }
        .theme-primary-hover:hover { background-color: rgb(var(--theme-primary) / 0.8) !important; }
        .theme-primary-text { color: rgb(var(--theme-primary)) !important; }
        .theme-primary-border { border-color: rgb(var(--theme-primary) / 0.3) !important; }

        .theme-success-bg { background-color: rgb(var(--theme-success) / 0.1) !important; }
        .theme-success-text { color: rgb(var(--theme-success) / 0.8) !important; }
        .theme-success-border { border-color: rgb(var(--theme-success) / 0.3) !important; }

        .theme-warning-bg { background-color: rgb(var(--theme-warning) / 0.1) !important; }
        .theme-warning-text { color: rgb(var(--theme-warning) / 0.8) !important; }
        .theme-warning-border { border-color: rgb(var(--theme-warning) / 0.3) !important; }

        .theme-danger-bg { background-color: rgb(var(--theme-danger) / 0.1) !important; }
        .theme-danger-text { color: rgb(var(--theme-danger) / 0.8) !important; }
        .theme-danger-border { border-color: rgb(var(--theme-danger) / 0.3) !important; }
        ";
    }

    /**
     * Muunna hex-väri RGB-muotoon
     */
    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Luo CSS-muuttujat
     */
    private function buildCssVariables(array $colors): string
    {
        $variables = [];
        foreach ($colors as $name => $hex) {
            $rgb = $this->hexToRgb($hex);
            $variables[] = "--theme-{$name}: {$rgb['r']}, {$rgb['g']}, {$rgb['b']}";
        }
        return implode(";\n    ", $variables);
    }

    /**
     * Sanitize CSS sisältö
     */
    private function sanitizeCss(string $css): string
    {
        // Poista mahdolliset vaaralliset merkit
        $safeCss = preg_replace('/[^#(),.%a-zA-Z0-9\s:;\-]/', '', $css);

        // Varmista että CSS on validia
        if (empty($safeCss)) {
            return 'background: #f3f4f6;';
        }

        return $safeCss;
    }
}