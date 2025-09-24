{{-- Live Theme Editor - Yksinkertainen ja reaaliaikainen teeman muokkaus --}}

<script>
// Global theme manager for real-time updates
window.liveThemeManager = function() {
    return {
        currentColors: {
            primary: '#3b82f6',
            secondary: '#6366f1',
            accent: '#8b5cf6',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            entries_tab_bg: '#ffffff',
            summary_tab_bg: '#ffffff'
        },

        opacityValues: {
            tab_background_opacity: 8,
            main_background_opacity: 3,
            sidebar_opacity: 95
        },

        // Quick presets for easy switching
        presets: {
            'default': {
                primary: '#3b82f6', secondary: '#6366f1', accent: '#8b5cf6',
                success: '#10b981', warning: '#f59e0b', danger: '#ef4444'
            },
            'forest': {
                primary: '#059669', secondary: '#047857', accent: '#065f46',
                success: '#10b981', warning: '#d97706', danger: '#dc2626'
            },
            'sunset': {
                primary: '#dc2626', secondary: '#ea580c', accent: '#d97706',
                success: '#16a34a', warning: '#ca8a04', danger: '#dc2626'
            },
            'ocean': {
                primary: '#0891b2', secondary: '#0e7490', accent: '#155e75',
                success: '#059669', warning: '#d97706', danger: '#dc2626'
            },
            'purple': {
                primary: '#7c3aed', secondary: '#8b5cf6', accent: '#a855f7',
                success: '#10b981', warning: '#f59e0b', danger: '#ef4444'
            },
            'minimal': {
                primary: '#374151', secondary: '#4b5563', accent: '#6b7280',
                success: '#059669', warning: '#d97706', danger: '#dc2626'
            }
        },

        init() {
            console.log('🎨 Live Theme Editor initialized');
            this.loadCurrentColorsFromWire();
            this.loadOpacityValues();
            this.watchFormChanges();
            this.applyThemeToDocument();
        },

        loadCurrentColorsFromWire() {
            if (this.$wire && this.$wire.data) {
                Object.keys(this.currentColors).forEach(key => {
                    if (this.$wire.data.hasOwnProperty(key)) {
                        this.currentColors[key] = this.$wire.data[key];
                    }
                });
                console.log('✅ Colors loaded from form');
            }
        },

        loadOpacityValues() {
            if (this.$wire && this.$wire.data) {
                Object.keys(this.opacityValues).forEach(key => {
                    if (this.$wire.data.hasOwnProperty(key)) {
                        this.opacityValues[key] = Math.round(this.$wire.data[key] * 100);
                    }
                });
                console.log('✅ Opacity values loaded');
            }
        },

        updateColor(key, value) {
            this.currentColors[key] = value;

            // Update Livewire form instantly
            if (this.$wire && this.$wire.data) {
                this.$wire.set(`data.${key}`, value);
            }

            // Apply to document immediately for real-time preview
            this.applyThemeToDocument();
            console.log(`🎨 Updated ${key} to ${value}`);
        },

        updateOpacity(key, value) {
            this.opacityValues[key] = value;
            const decimalValue = value / 100;

            if (this.$wire && this.$wire.data) {
                this.$wire.set(`data.${key}`, decimalValue);
            }

            this.applyThemeToDocument();
            console.log(`🎚️ Updated ${key} to ${value}%`);
        },

        applyPreset(presetName) {
            const preset = this.presets[presetName];
            if (!preset) return;

            // Update all colors from preset
            Object.keys(preset).forEach(key => {
                this.updateColor(key, preset[key]);
            });

            // Show notification
            this.showNotification(`Värimaailma "${presetName}" käytössä!`);
        },

        applyThemeToDocument() {
            // Apply CSS custom properties for real-time preview
            const root = document.documentElement;

            // Convert colors to RGB for CSS variables
            Object.keys(this.currentColors).forEach(key => {
                const hex = this.currentColors[key];
                const rgb = this.hexToRgb(hex);
                if (rgb) {
                    root.style.setProperty(`--theme-${key}`, `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                    root.style.setProperty(`--theme-${key}-hex`, hex);
                }
            });

            // Apply opacities
            Object.keys(this.opacityValues).forEach(key => {
                root.style.setProperty(`--theme-${key}`, this.opacityValues[key] / 100);
            });
        },

        hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },

        showNotification(message) {
            // Simple notification (can be enhanced)
            console.log(`🔔 ${message}`);
            // TODO: Add proper toast notification
        },

        watchFormChanges() {
            if (this.$wire) {
                this.$wire.$watch('data', (newData) => {
                    if (newData) {
                        // Update colors
                        Object.keys(this.currentColors).forEach(key => {
                            if (newData.hasOwnProperty(key)) {
                                this.currentColors[key] = newData[key];
                            }
                        });

                        // Update opacities
                        Object.keys(this.opacityValues).forEach(key => {
                            if (newData.hasOwnProperty(key)) {
                                this.opacityValues[key] = Math.round(newData[key] * 100);
                            }
                        });

                        this.applyThemeToDocument();
                    }
                });
            }
        }
    };
};
</script>

<div x-data="liveThemeManager()" x-init="init()" class="space-y-6">
    {{-- Quick Color Presets - New Feature --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
            </svg>
            ⚡ Pika-värimaailmat - Kokeile eri tunnelmia
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <template x-for="(preset, name) in presets" :key="name">
                <button
                    @click="applyPreset(name)"
                    class="group relative p-3 rounded-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md"
                    :class="{'ring-2 ring-blue-500': false}"
                >
                    <div class="flex space-x-1 mb-2">
                        <div class="w-4 h-4 rounded-full border border-gray-200" :style="`background-color: ${preset.primary}`"></div>
                        <div class="w-4 h-4 rounded-full border border-gray-200" :style="`background-color: ${preset.secondary}`"></div>
                        <div class="w-4 h-4 rounded-full border border-gray-200" :style="`background-color: ${preset.accent}`"></div>
                    </div>
                    <div class="text-xs font-medium text-gray-700 dark:text-gray-300 capitalize" x-text="name === 'default' ? 'Oletus' : name"></div>
                    <div class="text-xs text-gray-500 mt-1">Klikkaa soveltaaksesi</div>
                </button>
            </template>
        </div>

        <div class="mt-4 p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
            <p class="text-sm text-blue-700 dark:text-blue-300">
                💡 <strong>Nopea tapa:</strong> Kokeile eri värimaailmoja klikkaamalla yllä olevia esiasetuksia!
            </p>
        </div>
    </div>

    {{-- Simplified Color Controls --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"/>
            </svg>
            🎨 Värien hienosäätö - Reaaliaikainen muokkaus
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Primary Colors --}}
            <template x-for="(color, key) in {primary: 'Pääväri', secondary: 'Toissijainen', accent: 'Korostus', success: 'Onnistuminen'}" :key="key">
                <div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="color"></label>
                        <span class="text-xs font-mono text-gray-500" x-text="currentColors[key]"></span>
                    </div>

                    {{-- Large color preview --}}
                    <div class="w-full h-16 rounded-lg border-2 border-gray-200 shadow-inner"
                         :style="`background-color: ${currentColors[key]}`"></div>

                    {{-- Color picker --}}
                    <input
                        type="color"
                        :value="currentColors[key]"
                        @input="updateColor(key, $event.target.value)"
                        class="w-full h-10 rounded-md border border-gray-300 cursor-pointer"
                    >
                </div>
            </template>
        </div>

        {{-- Action colors in separate row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <template x-for="(color, key) in {warning: 'Varoitus', danger: 'Vaara'}" :key="key">
                <div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="color"></label>
                        <span class="text-xs font-mono text-gray-500" x-text="currentColors[key]"></span>
                    </div>

                    <div class="w-full h-12 rounded-lg border-2 border-gray-200"
                         :style="`background-color: ${currentColors[key]}`"></div>

                    <input
                        type="color"
                        :value="currentColors[key]"
                        @input="updateColor(key, $event.target.value)"
                        class="w-full h-8 rounded-md border border-gray-300 cursor-pointer"
                    >
                </div>
            </template>
        </div>
    </div>

    {{-- Simplified Opacity Controls --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            💫 Läpinäkyvyys ja efektit
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <template x-for="(label, key) in {tab_background_opacity: 'Tabit', main_background_opacity: 'Tausta', sidebar_opacity: 'Sivupalkki'}" :key="key">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="label"></label>
                        <span class="text-sm font-bold text-blue-600" x-text="`${opacityValues[key]}%`"></span>
                    </div>

                    {{-- Visual preview of opacity --}}
                    <div class="relative h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg overflow-hidden">
                        <div class="absolute inset-0 bg-white rounded-lg"
                             :style="`opacity: ${1 - (opacityValues[key] / 100)}`"></div>
                        <div class="relative z-10 flex items-center justify-center h-full text-white font-medium text-sm">
                            Esikatselu
                        </div>
                    </div>

                    <input
                        type="range"
                        :min="key === 'sidebar_opacity' ? 10 : 1"
                        :max="key === 'sidebar_opacity' ? 100 : 100"
                        :step="key === 'sidebar_opacity' ? 5 : 1"
                        :value="opacityValues[key]"
                        @input="updateOpacity(key, parseInt($event.target.value))"
                        class="w-full h-3 bg-gradient-to-r from-blue-200 to-blue-500 rounded-lg appearance-none cursor-pointer opacity-slider"
                    >
                </div>
            </template>
        </div>
    </div>

    {{-- Live Preview Status --}}
    <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium text-green-800 dark:text-green-200">
                    ✨ Reaaliaikainen esikatselu käytössä
                </h4>
                <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                    Kaikki muutokset näkyvät välittömästi. Muista tallentaa lopuksi!
                </p>
            </div>
            <div class="text-2xl">🎨</div>
        </div>
    </div>
</div>

<style>
/* Enhanced slider styles */
.opacity-slider::-webkit-slider-thumb {
    appearance: none;
    height: 24px;
    width: 24px;
    border-radius: 50%;
    background: linear-gradient(45deg, #3b82f6, #8b5cf6);
    border: 3px solid #ffffff;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
}

.opacity-slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.opacity-slider::-webkit-slider-track {
    height: 12px;
    border-radius: 6px;
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
    .opacity-slider::-webkit-slider-thumb {
        border-color: #374151;
    }
}
</style>
