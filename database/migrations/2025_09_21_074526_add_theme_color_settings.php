<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default theme colors
        $defaultColors = [
            'theme.primary_color' => '#3b82f6',      // Blue
            'theme.secondary_color' => '#6366f1',    // Indigo
            'theme.accent_color' => '#8b5cf6',       // Violet
            'theme.success_color' => '#10b981',      // Emerald
            'theme.warning_color' => '#f59e0b',      // Amber
            'theme.danger_color' => '#ef4444',       // Red
        ];

        foreach ($defaultColors as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $colorKeys = [
            'theme.primary_color',
            'theme.secondary_color',
            'theme.accent_color',
            'theme.success_color',
            'theme.warning_color',
            'theme.danger_color',
        ];

        Setting::whereIn('key', $colorKeys)->delete();
    }
};
