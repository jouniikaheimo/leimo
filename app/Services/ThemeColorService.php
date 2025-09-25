<?php

namespace App\Services;

use App\Models\Setting;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class ThemeColorService
{
    /**
     * Apply theme colors from settings to Filament
     */
    public static function applyThemeColors(): void
    {
        $colors = [
            'primary' => self::hexToFilamentColor(Setting::get('theme.primary_color', '#3b82f6')),
            'success' => self::hexToFilamentColor(Setting::get('theme.success_color', '#10b981')),
            'warning' => self::hexToFilamentColor(Setting::get('theme.warning_color', '#f59e0b')),
            'danger' => self::hexToFilamentColor(Setting::get('theme.danger_color', '#ef4444')),
            'gray' => Color::Zinc,
            'info' => Color::Blue,
        ];

        FilamentColor::register($colors);
    }

    /**
     * Get all theme colors as an array
     */
    public static function getAllColors(): array
    {
        return [
            'primary' => Setting::get('theme.primary_color', '#3b82f6'),
            'secondary' => Setting::get('theme.secondary_color', '#6366f1'),
            'accent' => Setting::get('theme.accent_color', '#8b5cf6'),
            'success' => Setting::get('theme.success_color', '#10b981'),
            'warning' => Setting::get('theme.warning_color', '#f59e0b'),
            'danger' => Setting::get('theme.danger_color', '#ef4444'),
            'entries_tab_bg' => Setting::get('theme.entries_tab_bg', '#ffffff'),
            'summary_tab_bg' => Setting::get('theme.summary_tab_bg', '#ffffff'),
        ];
    }

    /**
     * Get theme opacity settings
     */
    public static function getOpacitySettings(): array
    {
        return [
            'tab_background_opacity' => Setting::get('theme.tab_background_opacity', 0.08),
            'main_background_opacity' => Setting::get('theme.main_background_opacity', 0.03),
            'sidebar_opacity' => Setting::get('theme.sidebar_opacity', 0.95),
        ];
    }

    /**
     * Set multiple theme colors at once
     */
    public static function setColors(array $colors): void
    {
        foreach ($colors as $type => $color) {
            // Handle special tab background colors
            if (in_array($type, ['entries_tab_bg', 'summary_tab_bg'])) {
                Setting::set("theme.{$type}", $color);
            } else {
                // Handle regular theme colors
                Setting::set("theme.{$type}_color", $color);
            }
        }

        self::applyThemeColors();
    }

    /**
     * Set opacity settings
     */
    public static function setOpacitySettings(array $opacities): void
    {
        foreach ($opacities as $type => $value) {
            Setting::set("theme.{$type}", $value);
        }

        self::applyThemeColors();
    }

    /**
     * Get user colors for event log
     */
    public static function getUserColors(): array
    {
        $colorsData = Setting::get('theme.user_colors', '[]');

        // Jos data on jo array, käytä sitä suoraan
        if (is_array($colorsData)) {
            $colors = $colorsData;
        } else {
            // Jos data on string, dekoodaa se JSON:sta
            $colors = json_decode($colorsData, true) ?: [];
        }

        // Default colors if none are set
        if (empty($colors)) {
            return [
                '#3b82f6', // Blue
                '#10b981', // Green
                '#f59e0b', // Yellow
                '#ef4444', // Red
                '#8b5cf6', // Purple
                '#06b6d4', // Cyan
                '#f97316', // Orange
                '#84cc16', // Lime
            ];
        }

        return $colors;
    }

    /**
     * Set user colors for event log
     */
    public static function setUserColors(array $colors): void
    {
        Setting::set('theme.user_colors', json_encode(array_values($colors)));
    }

    /**
     * Get color for specific user index (cycles through available colors)
     */
    public static function getUserColor(int $userIndex): array
    {
        $colors = self::getUserColors();
        $color = $colors[$userIndex % count($colors)];

        // Convert hex to HSL for background and border
        $hsl = self::hexToHsl($color);
        $bg = "hsl({$hsl['h']} 65% 88%)";   // pastel background
        $border = "hsl({$hsl['h']} 45% 70%)"; // slightly darker for border

        return [
            'original' => $color,
            'background' => $bg,
            'border' => $border
        ];
    }

    /**
     * Convert hex color to HSL
     */
    public static function hexToHsl(string $hex): array
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $h = $s = $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = $s = 0; // achromatic
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            switch ($max) {
                case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                case $g: $h = ($b - $r) / $d + 2; break;
                case $b: $h = ($r - $g) / $d + 4; break;
            }
            $h /= 6;
        }

        return [
            'h' => round($h * 360),
            's' => round($s * 100),
            'l' => round($l * 100),
            'original' => "#$hex"
        ];
    }


    /**
     * Reset all colors to defaults
     */
    public static function resetToDefaults(): void
    {
        $defaults = [
            'primary' => '#3b82f6',
            'secondary' => '#6366f1',
            'accent' => '#8b5cf6',
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'danger' => '#ef4444',
        ];

        self::setColors($defaults);

        // Reset user colors to defaults
        self::setUserColors([
            '#3b82f6', // Blue
            '#10b981', // Green
            '#f59e0b', // Yellow
            '#ef4444', // Red
            '#8b5cf6', // Purple
            '#06b6d4', // Cyan
            '#f97316', // Orange
            '#84cc16', // Lime
        ]);
    }

    /**
     * Convert hex color to Filament color palette
     */
    public static function hexToFilamentColor(string $hex): array
    {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // Ensure we have a 6-character hex
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return self::generateColorPalette($r, $g, $b);
    }

    /**
     * Generate a complete color palette from RGB values
     */
    private static function generateColorPalette(int $r, int $g, int $b): array
    {
        $palette = [];
        $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];

        foreach ($shades as $shade) {
            if ($shade < 500) {
                // Lighter shades - mix with white
                $factor = (500 - $shade) / 450; // Adjusted for better light shade distribution
                $newR = $r + ($factor * (255 - $r));
                $newG = $g + ($factor * (255 - $g));
                $newB = $b + ($factor * (255 - $b));
            } elseif ($shade === 500) {
                // Base color
                $newR = $r;
                $newG = $g;
                $newB = $b;
            } else {
                // Darker shades
                $factor = match($shade) {
                    600 => 0.8,
                    700 => 0.65,
                    800 => 0.5,
                    900 => 0.35,
                    950 => 0.2,
                };

                $newR = $r * $factor;
                $newG = $g * $factor;
                $newB = $b * $factor;
            }

            $palette[$shade] = sprintf('rgb(%d, %d, %d)',
                min(255, max(0, round($newR))),
                min(255, max(0, round($newG))),
                min(255, max(0, round($newB)))
            );
        }

        return $palette;
    }

    /**
     * Convert hex to RGB array (public method for templates)
     */
    public static function hexToRgb(string $hex): array
    {
        return self::hexToRgbPrivate($hex);
    }

    /**
     * Convert hex to RGB array
     */
    private static function hexToRgbPrivate(string $hex): array
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
     * Get CSS variables for theme colors
     */
    public static function getCssVariables(): string
    {
        $colors = self::getAllColors();
        $cssVars = [];

        foreach ($colors as $type => $hex) {
            $rgb = self::hexToRgbPrivate($hex);
            $cssVars[] = "--theme-{$type}: {$rgb['r']}, {$rgb['g']}, {$rgb['b']}";
        }

        return implode('; ', $cssVars);
    }

    /**
     * Get background related settings with defaults
     */
    public static function getBackgroundSettings(): array
    {
        return [
            'background_mode' => Setting::get('theme.background_mode', 'solid'), // solid | gradient
            'background_color' => Setting::get('theme.background_color', '#f3f4f6'),
            'gradient_start_color' => Setting::get('theme.gradient_start_color', '#3b82f6'), // Kiinteä oletusarvo, ei riippuvuutta primary_color:iin
            'gradient_end_color' => Setting::get('theme.gradient_end_color', '#8b5cf6'),   // Kiinteä oletusarvo, ei riippuvuutta accent_color:iin
            'gradient_type' => Setting::get('theme.gradient_type', 'linear'), // linear | radial
            'gradient_direction' => Setting::get('theme.gradient_direction', 'to right'), // linear dirs
        ];
    }

    /**
     * Persist background settings
     */
    public static function setBackgroundSettings(array $data): void
    {
        $allowed = [
            'background_mode','background_color','gradient_start_color','gradient_end_color','gradient_type','gradient_direction'
        ];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $data)) {
                Setting::set("theme.$key", $data[$key]);
            }
        }
    }

    /**
     * Build CSS for body background
     */
    public static function getBackgroundCss(): array
    {
        $bg = self::getBackgroundSettings();

        if ($bg['background_mode'] === 'gradient') {
            $type = $bg['gradient_type'] === 'radial' ? 'radial-gradient' : 'linear-gradient';
            if ($type === 'radial-gradient') {
                $spec = 'circle at center';
            } else {
                $spec = $bg['gradient_direction'];
            }
            $css = sprintf('background: %s(%s, %s, %s);', $type, $spec, $bg['gradient_start_color'], $bg['gradient_end_color']);
        } else {
            $css = sprintf('background: %s;', $bg['background_color']);
        }

        return [
            'settings' => $bg,
            'css' => $css,
        ];
    }
}
