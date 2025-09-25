<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // Load theme colors for inline CSS
        $themeColors = \App\Services\ThemeColorService::getAllColors();
        $backgroundSettings = \App\Services\ThemeColorService::getBackgroundSettings();
        $backgroundCssData = \App\Services\ThemeColorService::getBackgroundCss();
        $backgroundCss = $backgroundCssData['css'] ?? 'background: #f3f4f6;';
    @endphp

    <style>
        /* Dynamic theme colors - loaded inline to ensure they work */
        :root {
            --theme-primary: {{ \App\Services\ThemeColorService::hexToRgb($themeColors['primary'])['r'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['primary'])['g'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['primary'])['b'] }};
            --theme-secondary: {{ \App\Services\ThemeColorService::hexToRgb($themeColors['secondary'])['r'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['secondary'])['g'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['secondary'])['b'] }};
            --theme-accent: {{ \App\Services\ThemeColorService::hexToRgb($themeColors['accent'])['r'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['accent'])['g'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['accent'])['b'] }};
            --theme-success: {{ \App\Services\ThemeColorService::hexToRgb($themeColors['success'])['r'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['success'])['g'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['success'])['b'] }};
            --theme-warning: {{ \App\Services\ThemeColorService::hexToRgb($themeColors['warning'])['r'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['warning'])['g'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['warning'])['b'] }};
            --theme-danger: {{ \App\Services\ThemeColorService::hexToRgb($themeColors['danger'])['r'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['danger'])['g'] }}, {{ \App\Services\ThemeColorService::hexToRgb($themeColors['danger'])['b'] }};
        }

        /* Filament main container theming - NOW USES PROPER GRADIENT SYSTEM */
        .fi-panel-admin,
        .fi-main-ctn,
        .fi-main {
            {{ $backgroundCss }} !important;
        }

        /* EXTRA HIGH SPECIFICITY - FORCE OVERRIDE ANY CONFLICTING CSS */
        body .fi-panel-admin,
        body .fi-main-ctn,
        body .fi-main,
        html body .fi-panel-admin,
        html body .fi-main-ctn,
        html body .fi-main {
            {{ $backgroundCss }} !important;
        }

        /* Additional targeting for common Filament containers */
        .fi-page,
        .fi-simple-page,
        .fi-resource-page,
        .fi-dashboard-page {
            {{ $backgroundCss }} !important;
        }

        /* Filament sidebar theming */
        .fi-sidebar {
            background: linear-gradient(180deg,
                rgb(var(--theme-primary) / 0.95) 0%,
                rgb(var(--theme-secondary) / 0.9) 100%) !important;
            border-color: rgb(var(--theme-primary) / 0.2) !important;
        }

        /* Filament header theming */
        .fi-topbar {
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: rgb(var(--theme-primary) / 0.1) !important;
            backdrop-filter: blur(8px);
        }

        /* Navigation items theming */
        .fi-sidebar-nav-item:hover {
            background: rgb(var(--theme-primary) / 0.1) !important;
        }

        .fi-sidebar-nav-item[aria-current="page"] {
            background: rgb(var(--theme-primary) / 0.2) !important;
            border-color: rgb(var(--theme-primary)) !important;
        }

        /* Button theming overrides */
        .fi-btn-primary {
            background: rgb(var(--theme-primary)) !important;
            border-color: rgb(var(--theme-primary)) !important;
        }

        .fi-btn-primary:hover {
            background: rgb(var(--theme-primary) / 0.9) !important;
        }

        /* Form field theming */
        .fi-input {
            border-color: rgb(var(--theme-primary) / 0.2) !important;
        }

        .fi-input:focus {
            border-color: rgb(var(--theme-primary)) !important;
            box-shadow: 0 0 0 1px rgb(var(--theme-primary) / 0.3) !important;
        }

        /* Card and section theming */
        .fi-section,
        .fi-card {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgb(var(--theme-primary) / 0.1) !important;
            backdrop-filter: blur(4px);
        }

        /* Dark mode support - ALSO USES PROPER GRADIENT SYSTEM */
        .dark .fi-panel-admin,
        .dark .fi-main-ctn,
        .dark .fi-main {
            {{ $backgroundCss }} !important;
        }

        /* Extra dark mode specificity */
        html.dark body .fi-panel-admin,
        html.dark body .fi-main-ctn,
        html.dark body .fi-main {
            {{ $backgroundCss }} !important;
        }

        /* Theme utility classes for custom use */
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
    </style>

    <title>{{ config('app.name', 'Laravel') }}</title>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased">
    @livewire('notifications')

    {{ $slot }}

    @filamentScripts
    @vite('resources/js/app.js')

    <script>
        // Simple theme color refresh function for live updates
        window.refreshThemeColors = function() {
            // Force page reload to get new colors - simple but effective
            if (window.location.pathname.includes('/admin/settings')) {
                // Don't reload on settings page to avoid losing form data
                console.log('Theme colors updated');
            } else {
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        };
    </script>
</body>
</html>
