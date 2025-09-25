<script>
// Enhanced liveThemeManager with proper Livewire integration
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

        init() {
            console.log('🎨 LiveThemeManager initialized');
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
                console.log('✅ Colors loaded from Livewire:', this.currentColors);
            }
        },

        loadOpacityValues() {
            if (this.$wire && this.$wire.data) {
                Object.keys(this.opacityValues).forEach(key => {
                    if (this.$wire.data.hasOwnProperty(key)) {
                        this.opacityValues[key] = Math.round(this.$wire.data[key] * 100);
                    }
                });
                console.log('✅ Opacity values loaded:', this.opacityValues);
            }
        },

        updateColor(key, value) {
            this.currentColors[key] = value;

            // Update Livewire form instantly
            if (this.$wire && this.$wire.data) {
                this.$wire.set(`data.${key}`, value);

                // Trigger auto-save
                setTimeout(() => {
                    if (this.$wire.autoSave) {
                        this.$wire.autoSave();
                    }
                }, 100);
            }

            this.applyThemeToDocument();
            console.log(`🎨 Updated color ${key} to ${value}`);
        },

        updateOpacity(key, value) {
            const percentage = parseInt(value);
            this.opacityValues[key] = percentage;
            const decimalValue = percentage / 100;

            // Update Livewire form instantly
            if (this.$wire && this.$wire.data) {
                this.$wire.set(`data.${key}`, decimalValue);

                // Trigger auto-save
                setTimeout(() => {
                    if (this.$wire.autoSave) {
                        this.$wire.autoSave();
                    }
                }, 100);
            }

            this.applyThemeToDocument();
            console.log(`🎚️ Updated opacity ${key} to ${percentage}% (${decimalValue})`);
        },

        applyThemeToDocument() {
            // Apply CSS custom properties for real-time preview
            const root = document.documentElement;

            // Apply colors
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

            console.log('🚀 Theme applied to document');
        },

        hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },

        watchFormChanges() {
            if (this.$wire) {
                this.$wire.$watch('data', (newData) => {
                    if (newData) {
                        // Update colors
                        Object.keys(this.currentColors).forEach(key => {
                            if (newData.hasOwnProperty(key) && newData[key] !== this.currentColors[key]) {
                                this.currentColors[key] = newData[key];
                            }
                        });

                        // Update opacities
                        Object.keys(this.opacityValues).forEach(key => {
                            if (newData.hasOwnProperty(key)) {
                                const newPercentage = Math.round(newData[key] * 100);
                                if (newPercentage !== this.opacityValues[key]) {
                                    this.opacityValues[key] = newPercentage;
                                }
                            }
                        });

                        this.applyThemeToDocument();
                        console.log('🔄 Data synchronized from form changes');
                    }
                });
            }
        }
    };
};
</script>

<div x-data="liveThemeManager()" x-init="init()" class="space-y-6">
    {{-- Enhanced Opacity Controls with Working Sliders --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            🎚️ Interaktiiviset läpinäkyvyyssäätimet
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Tab Background Opacity --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tab-taustan läpinäkyvyys</label>
                    <span class="text-sm font-bold text-blue-600" x-text="`${opacityValues.tab_background_opacity}%`"></span>
                </div>

                <div class="text-xs text-gray-500 mb-2">Pienempi arvo = läpinäkyvämpi</div>

                {{-- Visual preview using actual primary color --}}
                <div class="relative h-20 rounded-lg overflow-hidden border-2 border-gray-200">
                    <div class="h-full transition-colors duration-200"
                         :style="`background-color: ${currentColors.primary}`"></div>
                    <div class="absolute inset-0 bg-white rounded-lg transition-opacity duration-200"
                         :style="`opacity: ${(100 - opacityValues.tab_background_opacity) / 100}`"></div>
                    <div class="absolute inset-0 flex items-center justify-center font-medium text-sm"
                         :class="opacityValues.tab_background_opacity > 50 ? 'text-gray-800' : 'text-white'">
                        Tab esikatselu (pääväri)
                    </div>
                </div>

                {{-- Working slider --}}
                <input type="range"
                       min="1"
                       max="100"
                       :value="opacityValues.tab_background_opacity"
                       @input="updateOpacity('tab_background_opacity', $event.target.value)"
                       class="w-full h-3 mt-3 rounded-lg appearance-none cursor-pointer opacity-slider"
                       :style="`background: linear-gradient(to right, ${currentColors.primary}40, ${currentColors.primary})`">

                <div class="flex justify-between text-xs text-gray-500">
                    <span>1% (läpinäkyvä)</span>
                    <span>100% (peittävä)</span>
                </div>
            </div>

            {{-- Main Background Opacity --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pääsivun taustan läpinäkyvyys</label>
                    <span class="text-sm font-bold text-green-600" x-text="`${opacityValues.main_background_opacity}%`"></span>
                </div>

                <div class="text-xs text-gray-500 mb-2">Vaikuttaa pääsivun taustaefekteihin</div>

                {{-- Visual preview using actual secondary color --}}
                <div class="relative h-20 rounded-lg overflow-hidden border-2 border-gray-200">
                    <div class="h-full transition-colors duration-200"
                         :style="`background-color: ${currentColors.secondary}`"></div>
                    <div class="absolute inset-0 bg-white rounded-lg transition-opacity duration-200"
                         :style="`opacity: ${(100 - opacityValues.main_background_opacity) / 100}`"></div>
                    <div class="absolute inset-0 flex items-center justify-center font-medium text-sm"
                         :class="opacityValues.main_background_opacity > 50 ? 'text-gray-800' : 'text-white'">
                        Sivu esikatselu (toissijainen)
                    </div>
                </div>

                <input type="range"
                       min="1"
                       max="100"
                       :value="opacityValues.main_background_opacity"
                       @input="updateOpacity('main_background_opacity', $event.target.value)"
                       class="w-full h-3 rounded-lg appearance-none cursor-pointer opacity-slider"
                       :style="`background: linear-gradient(to right, ${currentColors.secondary}40, ${currentColors.secondary})`">

                <div class="flex justify-between text-xs text-gray-500">
                    <span>1% (läpinäkyvä)</span>
                    <span>100% (peittävä)</span>
                </div>
            </div>

            {{-- Sidebar Opacity --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sivupalkin läpinäkyvyys</label>
                    <span class="text-sm font-bold text-purple-600" x-text="`${opacityValues.sidebar_opacity}%`"></span>
                </div>

                <div class="text-xs text-gray-500 mb-2">Sivupalkin näkyvyys ja kontrastit</div>

                {{-- Visual preview using actual accent color --}}
                <div class="relative h-20 rounded-lg overflow-hidden border-2 border-gray-200">
                    <div class="h-full transition-colors duration-200"
                         :style="`background-color: ${currentColors.accent}`"></div>
                    <div class="absolute inset-0 bg-white rounded-lg transition-opacity duration-200"
                         :style="`opacity: ${(100 - opacityValues.sidebar_opacity) / 100}`"></div>
                    <div class="absolute inset-0 flex items-center justify-center font-medium text-sm"
                         :class="opacityValues.sidebar_opacity > 50 ? 'text-gray-800' : 'text-white'">
                        Sivupalkki esikatselu (korostus)
                    </div>
                </div>

                <input type="range"
                       min="10"
                       max="100"
                       step="5"
                       :value="opacityValues.sidebar_opacity"
                       @input="updateOpacity('sidebar_opacity', $event.target.value)"
                       class="w-full h-3 rounded-lg appearance-none cursor-pointer opacity-slider"
                       :style="`background: linear-gradient(to right, ${currentColors.accent}40, ${currentColors.accent})`">

                <div class="flex justify-between text-xs text-gray-500">
                    <span>10% (läpinäkyvä)</span>
                    <span>100% (peittävä)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Color Preview Cards --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            🎨 Väriesikatselut
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Primary Color Preview --}}
            <div class="space-y-3">
                <div class="h-20 rounded-lg border-2 border-gray-200 flex items-center justify-center text-white font-semibold text-sm transition-colors duration-200"
                     :style="`background-color: ${currentColors.primary}`">
                    Pääväri
                </div>
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary</div>
                    <div class="text-xs font-mono text-gray-500" x-text="currentColors.primary"></div>
                </div>
            </div>

            {{-- Success Color Preview --}}
            <div class="space-y-3">
                <div class="h-20 rounded-lg border-2 border-gray-200 flex items-center justify-center text-white font-semibold text-sm transition-colors duration-200"
                     :style="`background-color: ${currentColors.success}`">
                    Onnistuminen
                </div>
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Success</div>
                    <div class="text-xs font-mono text-gray-500" x-text="currentColors.success"></div>
                </div>
            </div>

            {{-- Warning Color Preview --}}
            <div class="space-y-3">
                <div class="h-20 rounded-lg border-2 border-gray-200 flex items-center justify-center text-white font-semibold text-sm transition-colors duration-200"
                     :style="`background-color: ${currentColors.warning}`">
                    Varoitus
                </div>
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Warning</div>
                    <div class="text-xs font-mono text-gray-500" x-text="currentColors.warning"></div>
                </div>
            </div>

            {{-- Danger Color Preview --}}
            <div class="space-y-3">
                <div class="h-20 rounded-lg border-2 border-gray-200 flex items-center justify-center text-white font-semibold text-sm transition-colors duration-200"
                     :style="`background-color: ${currentColors.danger}`">
                    Vaara
                </div>
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Danger</div>
                    <div class="text-xs font-mono text-gray-500" x-text="currentColors.danger"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Background Previews --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            📑 Tab-taustat (Event Log sivu)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Entries Tab Preview --}}
            <div class="space-y-3">
                <div class="h-24 rounded-lg border-2 border-gray-200 p-4 transition-colors duration-200"
                     :style="`background-color: ${currentColors.entries_tab_bg}`">
                    <div class="text-sm font-medium mb-2"
                         :class="isLightColor(currentColors.entries_tab_bg) ? 'text-gray-800' : 'text-white'">
                        📋 Merkinnät & leimaukset
                    </div>
                    <div class="text-xs"
                         :class="isLightColor(currentColors.entries_tab_bg) ? 'text-gray-600' : 'text-gray-300'">
                        Tässä näkyy merkinnät-tabin tausta
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Entries Tab</div>
                    <div class="text-xs font-mono text-gray-500" x-text="currentColors.entries_tab_bg"></div>
                </div>
            </div>

            {{-- Summary Tab Preview --}}
            <div class="space-y-3">
                <div class="h-24 rounded-lg border-2 border-gray-200 p-4 transition-colors duration-200"
                     :style="`background-color: ${currentColors.summary_tab_bg}`">
                    <div class="text-sm font-medium mb-2"
                         :class="isLightColor(currentColors.summary_tab_bg) ? 'text-gray-800' : 'text-white'">
                        📊 Kuukausikertymä
                    </div>
                    <div class="text-xs"
                         :class="isLightColor(currentColors.summary_tab_bg) ? 'text-gray-600' : 'text-gray-300'">
                        Tässä näkyy yhteenveto-tabin tausta
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Summary Tab</div>
                    <div class="text-xs font-mono text-gray-500" x-text="currentColors.summary_tab_bg"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Live Status --}}
    <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium text-green-800 dark:text-green-200">
                    ✨ Reaaliaikainen esikatselu ja tallennus käytössä
                </h4>
                <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                    Sliderien muutokset päivittyvät välittömästi sekä lomakkeeseen että koko sovellukseen!
                </p>
            </div>
            <div class="text-2xl">🎚️</div>
        </div>
    </div>
</div>

<script>
// Helper function for Alpine.js
window.isLightColor = function(hexColor) {
    const rgb = hexToRgb(hexColor);
    if (!rgb) return true;

    const luminance = (0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b) / 255;
    return luminance > 0.5;
};

window.hexToRgb = function(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
};
</script>

<style>
/* Enhanced slider styles for better UX */
.opacity-slider {
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
    cursor: pointer;
}

.opacity-slider::-webkit-slider-track {
    height: 12px;
    border-radius: 6px;
}

.opacity-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
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

.opacity-slider::-moz-range-thumb {
    height: 24px;
    width: 24px;
    border-radius: 50%;
    background: linear-gradient(45deg, #3b82f6, #8b5cf6);
    border: 3px solid #ffffff;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
    .opacity-slider::-webkit-slider-thumb {
        border-color: #374151;
    }

    .opacity-slider::-moz-range-thumb {
        border-color: #374151;
    }
}
</style>
