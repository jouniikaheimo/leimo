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
        // Set default theme opacity settings
        $defaultOpacities = [
            'theme.tab_background_opacity' => 0.08,
            'theme.main_background_opacity' => 0.03,
            'theme.sidebar_opacity' => 0.95,
        ];

        foreach ($defaultOpacities as $key => $value) {
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
        $opacityKeys = [
            'theme.tab_background_opacity',
            'theme.main_background_opacity',
            'theme.sidebar_opacity',
        ];

        Setting::whereIn('key', $opacityKeys)->delete();
    }
};
