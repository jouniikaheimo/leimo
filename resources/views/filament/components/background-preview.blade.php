@php
use App\Services\ThemeColorService;

// Get current form state for live preview
$formData = $this->form->getState();
$backgroundMode = $formData['background_mode'] ?? 'solid';
$backgroundColor = $formData['background_color'] ?? '#f3f4f6';
$gradientStartColor = $formData['gradient_start_color'] ?? '#3b82f6';
$gradientEndColor = $formData['gradient_end_color'] ?? '#8b5cf6';
$gradientType = $formData['gradient_type'] ?? 'linear';
$gradientDirection = $formData['gradient_direction'] ?? 'to right';

// Generate preview CSS
if ($backgroundMode === 'gradient') {
    if ($gradientType === 'radial') {
        $previewBackground = "radial-gradient(circle, {$gradientStartColor}, {$gradientEndColor})";
    } else {
        $previewBackground = "linear-gradient({$gradientDirection}, {$gradientStartColor}, {$gradientEndColor})";
    }
} else {
    $previewBackground = $backgroundColor;
}
@endphp

<div class="space-y-4">
    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
        🎨 Reaaliaikainen esikatselu
    </div>

    <!-- Large preview box -->
    <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 dark:border-gray-700"
         style="height: 200px; background: {{ $previewBackground }};">

        <!-- Content overlay to show how it looks -->
        <div class="absolute inset-4 bg-white/80 dark:bg-gray-900/80 rounded-md p-4 shadow-sm">
            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Sovelluksen sisältö
            </div>
            <div class="space-y-2">
                <div class="h-2 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
                <div class="h-2 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                <div class="h-2 bg-gray-300 dark:bg-gray-600 rounded w-2/3"></div>
            </div>
        </div>

        <!-- Settings info overlay -->
        <div class="absolute bottom-2 right-2 bg-black/75 text-white text-xs px-2 py-1 rounded">
            @if($backgroundMode === 'gradient')
                {{ ucfirst($gradientType) }} gradient: {{ $gradientDirection }}
            @else
                Solid color
            @endif
        </div>
    </div>

    <!-- Mini previews for different modes -->
    <div class="grid grid-cols-2 gap-3">
        <div class="text-center">
            <div class="h-16 rounded border border-gray-300 dark:border-gray-600 mb-1"
                 style="background: {{ $backgroundColor }};">
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-400">Yksivärinen</div>
        </div>

        <div class="text-center">
            <div class="h-16 rounded border border-gray-300 dark:border-gray-600 mb-1"
                 style="background: linear-gradient({{ $gradientDirection }}, {{ $gradientStartColor }}, {{ $gradientEndColor }});">
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-400">Gradient</div>
        </div>
    </div>

    <!-- Current settings display -->
    <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-3 text-xs space-y-1">
        <div class="font-medium text-gray-700 dark:text-gray-300">Nykyiset asetukset:</div>
        <div class="text-gray-600 dark:text-gray-400">
            <strong>Tyyppi:</strong> {{ $backgroundMode === 'gradient' ? 'Gradient' : 'Yksivärinen' }}
        </div>
        @if($backgroundMode === 'gradient')
            <div class="text-gray-600 dark:text-gray-400">
                <strong>Alkuväri:</strong> {{ $gradientStartColor }}
            </div>
            <div class="text-gray-600 dark:text-gray-400">
                <strong>Loppuväri:</strong> {{ $gradientEndColor }}
            </div>
            <div class="text-gray-600 dark:text-gray-400">
                <strong>Gradient:</strong> {{ ucfirst($gradientType) }} - {{ $gradientDirection }}
            </div>
        @else
            <div class="text-gray-600 dark:text-gray-400">
                <strong>Väri:</strong> {{ $backgroundColor }}
            </div>
        @endif
    </div>
</div>
