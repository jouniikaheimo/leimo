<?php

namespace App\Console\Commands;

use App\Services\ThemeColorService;
use Illuminate\Console\Command;

class ThemeColorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:colors 
                            {action : The action to perform (list, set, reset, get)}
                            {--color= : Color type (primary, secondary, accent, success, warning, danger)}
                            {--value= : Hex color value (e.g., #3b82f6)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application theme colors';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                return $this->listColors();
            case 'set':
                return $this->setColor();
            case 'reset':
                return $this->resetColors();
            case 'get':
                return $this->getColor();
            default:
                $this->error("Unknown action: {$action}");
                $this->info('Available actions: list, set, reset, get');
                return 1;
        }
    }

    /**
     * List all current theme colors
     */
    protected function listColors(): int
    {
        $colors = ThemeColorService::getAllColors();

        $this->info('Current Theme Colors:');
        $this->line('');

        $rows = [];
        foreach ($colors as $type => $hex) {
            $rows[] = [
                ucfirst($type),
                $hex,
                $this->getColorPreview($hex)
            ];
        }

        $this->table(['Type', 'Hex Value', 'Preview'], $rows);

        return 0;
    }

    /**
     * Set a specific color
     */
    protected function setColor(): int
    {
        $colorType = $this->option('color');
        $colorValue = $this->option('value');

        if (!$colorType) {
            $colorType = $this->choice(
                'Which color would you like to set?',
                ['primary', 'secondary', 'accent', 'success', 'warning', 'danger']
            );
        }

        if (!$colorValue) {
            $colorValue = $this->ask('Enter the hex color value (e.g., #3b82f6)');
        }

        // Validate hex color
        if (!preg_match('/^#[a-f0-9]{6}$/i', $colorValue)) {
            $this->error('Invalid hex color format. Use format: #3b82f6');
            return 1;
        }

        $validTypes = ['primary', 'secondary', 'accent', 'success', 'warning', 'danger'];
        if (!in_array($colorType, $validTypes)) {
            $this->error("Invalid color type. Available types: " . implode(', ', $validTypes));
            return 1;
        }

        ThemeColorService::setColors([$colorType => $colorValue]);

        $this->info("✅ {$colorType} color set to {$colorValue}");
        $this->line("Preview: {$this->getColorPreview($colorValue)}");

        return 0;
    }

    /**
     * Reset all colors to defaults
     */
    protected function resetColors(): int
    {
        if ($this->confirm('Are you sure you want to reset all colors to defaults?')) {
            ThemeColorService::resetToDefaults();
            $this->info('✅ All colors have been reset to defaults');
            return $this->listColors();
        }

        $this->info('Reset cancelled');
        return 0;
    }

    /**
     * Get a specific color
     */
    protected function getColor(): int
    {
        $colorType = $this->option('color');

        if (!$colorType) {
            $colorType = $this->choice(
                'Which color would you like to get?',
                ['primary', 'secondary', 'accent', 'success', 'warning', 'danger']
            );
        }

        $colors = ThemeColorService::getAllColors();

        if (!isset($colors[$colorType])) {
            $this->error("Color type '{$colorType}' not found");
            return 1;
        }

        $hex = $colors[$colorType];
        $this->info("{$colorType}: {$hex}");
        $this->line("Preview: {$this->getColorPreview($hex)}");

        return 0;
    }

    /**
     * Get a simple color preview for terminal
     */
    protected function getColorPreview(string $hex): string
    {
        // Convert hex to RGB for a simple text representation
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return sprintf('RGB(%d, %d, %d)', $r, $g, $b);
    }
}
