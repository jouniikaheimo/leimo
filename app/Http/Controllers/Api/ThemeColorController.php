<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ThemeColorService;
use Illuminate\Http\JsonResponse;

class ThemeColorController extends Controller
{
    /**
     * Get current theme colors as JSON
     */
    public function index(): JsonResponse
    {
        try {
            $colors = ThemeColorService::getAllColors();

            return response()->json($colors, 200, [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            // Return default colors if there's an error
            return response()->json([
                'primary' => '#3b82f6',
                'secondary' => '#6366f1',
                'accent' => '#8b5cf6',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#ef4444',
            ]);
        }
    }
}
