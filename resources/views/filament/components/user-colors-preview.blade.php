{{-- Käyttäjävärien esikatselu --}}
<div class="rounded-lg border border-gray-200 p-4 bg-gray-50/50" x-data="{ formColors: @js($formUserColors ?? []) }">
    <h4 class="font-medium text-gray-900 mb-3">🎨 Esikatselunäkymä: Miltä värit näyttävät Event Log -taulukossa</h4>

    <div class="space-y-2">
        @php
            // Käytä form-dataa jos saatavilla, muuten hae servicestä
            $userColorsData = $formUserColors ?? [];
            $userColors = [];

            // Muunna form data värilistaksi
            if (is_array($userColorsData)) {
                foreach ($userColorsData as $item) {
                    if (is_array($item) && isset($item['color']) && !empty($item['color'])) {
                        $userColors[] = $item['color'];
                    }
                }
            }

            // Jos ei form-dataa, hae servicestä
            if (empty($userColors)) {
                $userColors = \App\Services\ThemeColorService::getUserColors();
            }
        @endphp

        @foreach($userColors as $index => $color)
            @php
                // Käytä samaa logiikkaa kuin oikeassa event log -sivussa


                $hsl = \App\Services\ThemeColorService::hexToHsl($color);
                $bg = $hsl['original'];   // pastel background
                $border = $hsl['original']; // slightly darker for border

                // Luo esimerkkinimikirjaimet
                $initials = ['JK', 'MM', 'TS', 'AL', 'KL', 'MH', 'PV', 'AS'][$index] ?? 'U' . ($index + 1);
            @endphp

            <div class="flex items-center gap-3 p-2 rounded border bg-white">
                {{-- Käyttäjäavatar preview --}}
                <div
                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-gray-700 border-2"
                    style="background-color: {{ $bg }}; border-color: {{ $border }};"
                >
                    {{ $initials }}
                </div>

                {{-- Väritiedot --}}
                <div class="flex-1">
                    <div class="text-sm font-medium text-gray-900">
                        Käyttäjä {{ $index + 1 }} ({{ $initials }})
                    </div>
                    <div class="text-xs text-gray-500">
                        Väri: <code class="px-1 py-0.5 bg-gray-100 rounded text-xs">{{ $color }}</code>
                    </div>
                </div>

                {{-- Värinäyte --}}
                <div
                    class="w-6 h-6 rounded border-2 border-gray-200"
                    style="background-color: {{ $color }};"
                    title="Alkuperäinen väri: {{ $color }}"
                ></div>
            </div>
        @endforeach

        @if(count($userColors) === 0)
            <div class="text-center py-6 text-gray-500">
                <div class="text-sm">Ei määriteltyjä käyttäjävärejä.</div>
                <div class="text-xs mt-1">Lisää värejä yllä olevalla repeater-kentällä.</div>
            </div>
        @endif
    </div>

    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
        <div class="text-sm text-blue-800">
            <strong>💡 Näin värit toimivat:</strong>
        </div>
        <ul class="text-xs text-blue-700 mt-1 space-y-1">
            <li>• Värit kiertävät käyttäjien kesken (1. käyttäjä = 1. väri, 2. käyttäjä = 2. väri, jne.)</li>
            <li>• Jos käyttäjiä on enemmän kuin värejä, värit alkavat alusta</li>
            <li>• Alkuperäinen väri muunnetaan automaattisesti pastellisävyiksi event log -taulukossa</li>
        </ul>
    </div>
</div>
