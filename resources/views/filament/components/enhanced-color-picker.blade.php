{{-- Enhanced Color Picker with Real-time Preview and Sliders --}}
<div class="space-y-6">
    {{-- Enhanced Color Controls with Live Preview --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Primary Colors Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                🎨 Päävärit
            </h3>

            <div class="space-y-4">
                {{-- Primary Color --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pääväri</label>
                        <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="primary-hex">#3b82f6</span>
                    </div>

                    {{-- Live Color Preview --}}
                    <div class="relative mb-3">
                        <div class="h-20 rounded-lg border-2 border-gray-200 overflow-hidden" id="primary-preview">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                                Esikatselu: Napit ja linkit
                            </div>
                        </div>
                        {{-- Example buttons --}}
                        <div class="absolute bottom-2 left-2 flex space-x-2">
                            <button class="px-3 py-1 text-xs text-white rounded" id="primary-button" style="background-color: #3b82f6;">Nappi</button>
                            <span class="text-xs text-blue-600 underline cursor-pointer" id="primary-link" style="color: #3b82f6;">Linkki</span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <input type="color"
                               value="#3b82f6"
                               class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                               id="primary-color-picker"
                               onchange="updateColorPreview('primary', this.value)">
                        <div class="flex-1">
                            <input type="text"
                                   value="#3b82f6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                                   id="primary-input"
                                   onchange="updateColorFromInput('primary', this.value)"
                                   placeholder="#3b82f6">
                        </div>
                    </div>
                </div>

                {{-- Secondary Color --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Toissijainen väri</label>
                        <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="secondary-hex">#6366f1</span>
                    </div>

                    <div class="relative mb-3">
                        <div class="h-20 rounded-lg border-2 border-gray-200 overflow-hidden" id="secondary-preview">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-blue-500 flex items-center justify-center text-white font-semibold">
                                Esikatselu: Toissijaiset toiminnot
                            </div>
                        </div>
                        <div class="absolute bottom-2 left-2 flex space-x-2">
                            <button class="px-3 py-1 text-xs text-white rounded" id="secondary-button" style="background-color: #6366f1;">Toissijainen</button>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <input type="color"
                               value="#6366f1"
                               class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                               id="secondary-color-picker"
                               onchange="updateColorPreview('secondary', this.value)">
                        <div class="flex-1">
                            <input type="text"
                                   value="#6366f1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                                   id="secondary-input"
                                   onchange="updateColorFromInput('secondary', this.value)">
                        </div>
                    </div>
                </div>

                {{-- Accent Color --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Korostusväri</label>
                        <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="accent-hex">#8b5cf6</span>
                    </div>

                    <div class="relative mb-3">
                        <div class="h-20 rounded-lg border-2 border-gray-200 overflow-hidden" id="accent-preview">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold">
                                Esikatselu: Progress & korostukset
                            </div>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <div class="w-24 h-2 bg-gray-300 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 w-3/4" id="accent-progress" style="background-color: #8b5cf6;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <input type="color"
                               value="#8b5cf6"
                               class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                               id="accent-color-picker"
                               onchange="updateColorPreview('accent', this.value)">
                        <div class="flex-1">
                            <input type="text"
                                   value="#8b5cf6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                                   id="accent-input"
                                   onchange="updateColorFromInput('accent', this.value)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Colors Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                🚦 Toimintavärit
            </h3>

            <div class="space-y-4">
                {{-- Success Color --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Onnistumisväri</label>
                        <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="success-hex">#10b981</span>
                    </div>

                    <div class="relative mb-3">
                        <div class="h-16 rounded-lg border-2 border-gray-200 overflow-hidden" id="success-preview">
                            <div class="h-full bg-green-500 flex items-center justify-center text-white font-semibold">
                                ✅ Onnistunut toiminto
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <input type="color"
                               value="#10b981"
                               class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                               id="success-color-picker"
                               onchange="updateColorPreview('success', this.value)">
                        <div class="flex-1">
                            <input type="text"
                                   value="#10b981"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                                   id="success-input"
                                   onchange="updateColorFromInput('success', this.value)">
                        </div>
                    </div>
                </div>

                {{-- Warning Color --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Varoitusväri</label>
                        <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="warning-hex">#f59e0b</span>
                    </div>

                    <div class="relative mb-3">
                        <div class="h-16 rounded-lg border-2 border-gray-200 overflow-hidden" id="warning-preview">
                            <div class="h-full bg-yellow-500 flex items-center justify-center text-white font-semibold">
                                ⚠️ Varoitus
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <input type="color"
                               value="#f59e0b"
                               class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                               id="warning-color-picker"
                               onchange="updateColorPreview('warning', this.value)">
                        <div class="flex-1">
                            <input type="text"
                                   value="#f59e0b"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                                   id="warning-input"
                                   onchange="updateColorFromInput('warning', this.value)">
                        </div>
                    </div>
                </div>

                {{-- Danger Color --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Vaaraväri</label>
                        <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="danger-hex">#ef4444</span>
                    </div>

                    <div class="relative mb-3">
                        <div class="h-16 rounded-lg border-2 border-gray-200 overflow-hidden" id="danger-preview">
                            <div class="h-full bg-red-500 flex items-center justify-center text-white font-semibold">
                                🚨 Kriittinen toiminto
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <input type="color"
                               value="#ef4444"
                               class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                               id="danger-color-picker"
                               onchange="updateColorPreview('danger', this.value)">
                        <div class="flex-1">
                            <input type="text"
                                   value="#ef4444"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                                   id="danger-input"
                                   onchange="updateColorFromInput('danger', this.value)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Background Colors Section --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            📑 Event Log -sivun tabit
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Entries Tab Background --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Merkinnät-tabin tausta</label>
                    <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="entries-tab-hex">#ffffff</span>
                </div>

                <div class="relative mb-3">
                    <div class="h-24 rounded-lg border-2 border-gray-200 overflow-hidden" id="entries-tab-preview">
                        <div class="h-full p-4" style="background-color: #ffffff;">
                            <div class="text-sm font-medium text-gray-800 mb-2">📋 Merkinnät & leimaukset</div>
                            <div class="text-xs text-gray-600">Tässä näkyy miltä tabin tausta näyttää</div>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <input type="color"
                           value="#ffffff"
                           class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                           id="entries-tab-color-picker"
                           onchange="updateColorPreview('entries_tab_bg', this.value)">
                    <div class="flex-1">
                        <input type="text"
                               value="#ffffff"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                               id="entries-tab-input"
                               onchange="updateColorFromInput('entries_tab_bg', this.value)">
                    </div>
                </div>
            </div>

            {{-- Summary Tab Background --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Yhteenveto-tabin tausta</label>
                    <span class="text-xs font-mono bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded" id="summary-tab-hex">#ffffff</span>
                </div>

                <div class="relative mb-3">
                    <div class="h-24 rounded-lg border-2 border-gray-200 overflow-hidden" id="summary-tab-preview">
                        <div class="h-full p-4" style="background-color: #ffffff;">
                            <div class="text-sm font-medium text-gray-800 mb-2">📊 Kuukausikertymä</div>
                            <div class="text-xs text-gray-600">Tässä näkyy yhteenveto-tabin tausta</div>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <input type="color"
                           value="#ffffff"
                           class="w-16 h-10 rounded border border-gray-300 cursor-pointer"
                           id="summary-tab-color-picker"
                           onchange="updateColorPreview('summary_tab_bg', this.value)">
                    <div class="flex-1">
                        <input type="text"
                               value="#ffffff"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono"
                               id="summary-tab-input"
                               onchange="updateColorFromInput('summary_tab_bg', this.value)">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Opacity Controls with Sliders --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            💫 Läpinäkyvyys ja efektit
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Tab Background Opacity --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tab-taustan läpinäkyvyys</label>
                    <span class="text-sm font-bold text-blue-600" id="tab-opacity-value">8%</span>
                </div>

                {{-- Visual preview --}}
                <div class="relative h-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg overflow-hidden">
                    <div class="absolute inset-0 bg-white rounded-lg opacity-92" id="tab-opacity-overlay"></div>
                    <div class="relative z-10 flex items-center justify-center h-full text-gray-800 font-medium text-sm">
                        Tab esikatselu
                    </div>
                </div>

                {{-- Custom styled slider --}}
                <div class="space-y-2">
                    <input type="range"
                           min="1"
                           max="100"
                           value="8"
                           class="w-full h-3 bg-gradient-to-r from-blue-200 to-blue-500 rounded-lg appearance-none cursor-pointer opacity-slider"
                           id="tab-opacity-slider"
                           oninput="updateOpacity('tab_background_opacity', this.value)"
                           onchange="saveOpacity('tab_background_opacity', this.value)">

                    <div class="flex justify-between text-xs text-gray-500">
                        <span>1% (läpinäkyvä)</span>
                        <span>100% (peittävä)</span>
                    </div>
                </div>

                {{-- Manual input --}}
                <input type="number"
                       min="0.01"
                       max="1.0"
                       step="0.01"
                       value="0.08"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                       id="tab-opacity-input"
                       onchange="updateOpacityFromDecimal('tab_background_opacity', this.value)"
                       placeholder="0.08">
            </div>

            {{-- Main Background Opacity --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pääsivun taustan läpinäkyvyys</label>
                    <span class="text-sm font-bold text-green-600" id="main-opacity-value">3%</span>
                </div>

                <div class="relative h-20 bg-gradient-to-r from-green-400 to-teal-500 rounded-lg overflow-hidden">
                    <div class="absolute inset-0 bg-white rounded-lg opacity-97" id="main-opacity-overlay"></div>
                    <div class="relative z-10 flex items-center justify-center h-full text-gray-800 font-medium text-sm">
                        Sivu esikatselu
                    </div>
                </div>

                <div class="space-y-2">
                    <input type="range"
                           min="1"
                           max="100"
                           value="3"
                           class="w-full h-3 bg-gradient-to-r from-green-200 to-green-500 rounded-lg appearance-none cursor-pointer opacity-slider"
                           id="main-opacity-slider"
                           oninput="updateOpacity('main_background_opacity', this.value)"
                           onchange="saveOpacity('main_background_opacity', this.value)">

                    <div class="flex justify-between text-xs text-gray-500">
                        <span>1% (läpinäkyvä)</span>
                        <span>100% (peittävä)</span>
                    </div>
                </div>

                <input type="number"
                       min="0.01"
                       max="1.0"
                       step="0.01"
                       value="0.03"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                       id="main-opacity-input"
                       onchange="updateOpacityFromDecimal('main_background_opacity', this.value)">
            </div>

            {{-- Sidebar Opacity --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sivupalkin läpinäkyvyys</label>
                    <span class="text-sm font-bold text-purple-600" id="sidebar-opacity-value">95%</span>
                </div>

                <div class="relative h-20 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg overflow-hidden">
                    <div class="absolute inset-0 bg-white rounded-lg opacity-5" id="sidebar-opacity-overlay"></div>
                    <div class="relative z-10 flex items-center justify-center h-full text-white font-medium text-sm">
                        Sivupalkki esikatselu
                    </div>
                </div>

                <div class="space-y-2">
                    <input type="range"
                           min="10"
                           max="100"
                           value="95"
                           step="5"
                           class="w-full h-3 bg-gradient-to-r from-purple-200 to-purple-500 rounded-lg appearance-none cursor-pointer opacity-slider"
                           id="sidebar-opacity-slider"
                           oninput="updateOpacity('sidebar_opacity', this.value)"
                           onchange="saveOpacity('sidebar_opacity', this.value)">

                    <div class="flex justify-between text-xs text-gray-500">
                        <span>10% (läpinäkyvä)</span>
                        <span>100% (peittävä)</span>
                    </div>
                </div>

                <input type="number"
                       min="0.1"
                       max="1.0"
                       step="0.05"
                       value="0.95"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                       id="sidebar-opacity-input"
                       onchange="updateOpacityFromDecimal('sidebar_opacity', this.value)">
            </div>
        </div>
    </div>

    {{-- Real-time Status --}}
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
                    Kaikki muutokset näkyvät välittömästi esikatselussa ja tallentuvat automaattisesti!
                </p>
            </div>
            <div class="text-2xl">🎨</div>
        </div>
    </div>
</div>

<script>
// Enhanced Color and Opacity Management
window.enhancedColorManager = {
    // Load current values from Livewire
    loadCurrentValues: function() {
        if (window.Livewire) {
            // Try to get current form data
            const component = window.Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
            if (component && component.data) {
                this.updateAllPreviews(component.data);
            }
        }
    },

    updateAllPreviews: function(data) {
        // Update color previews
        const colors = ['primary', 'secondary', 'accent', 'success', 'warning', 'danger', 'entries_tab_bg', 'summary_tab_bg'];
        colors.forEach(color => {
            if (data[color]) {
                this.updateColorPreview(color, data[color]);
            }
        });

        // Update opacity previews
        if (data.tab_background_opacity) {
            this.updateOpacity('tab_background_opacity', Math.round(data.tab_background_opacity * 100));
        }
        if (data.main_background_opacity) {
            this.updateOpacity('main_background_opacity', Math.round(data.main_background_opacity * 100));
        }
        if (data.sidebar_opacity) {
            this.updateOpacity('sidebar_opacity', Math.round(data.sidebar_opacity * 100));
        }
    }
};

// Color preview update function
function updateColorPreview(colorType, hexValue) {
    // Update hex display
    const hexElement = document.getElementById(colorType.replace('_', '-') + '-hex');
    if (hexElement) {
        hexElement.textContent = hexValue;
    }

    // Update input field
    const inputElement = document.getElementById(colorType.replace('_', '-') + '-input');
    if (inputElement) {
        inputElement.value = hexValue;
    }

    // Update color picker
    const pickerElement = document.getElementById(colorType.replace('_', '-') + '-color-picker');
    if (pickerElement) {
        pickerElement.value = hexValue;
    }

    // Update preview background
    const previewElement = document.getElementById(colorType.replace('_', '-') + '-preview');
    if (previewElement) {
        if (colorType === 'entries_tab_bg' || colorType === 'summary_tab_bg') {
            // For tab backgrounds, update the inner content area
            const innerDiv = previewElement.querySelector('div');
            if (innerDiv) {
                innerDiv.style.backgroundColor = hexValue;
                // Update text color for better contrast
                const textColor = isLightColor(hexValue) ? '#374151' : '#ffffff';
                innerDiv.style.color = textColor;
            }
        } else {
            // For other colors, update the gradient background
            const innerDiv = previewElement.querySelector('div');
            if (innerDiv) {
                innerDiv.style.backgroundColor = hexValue;
            }
        }
    }

    // Update example elements
    const buttonElement = document.getElementById(colorType + '-button');
    if (buttonElement) {
        buttonElement.style.backgroundColor = hexValue;
    }

    const linkElement = document.getElementById(colorType + '-link');
    if (linkElement) {
        linkElement.style.color = hexValue;
    }

    const progressElement = document.getElementById(colorType + '-progress');
    if (progressElement) {
        progressElement.style.backgroundColor = hexValue;
    }

    // Save to Livewire
    saveColorToLivewire(colorType, hexValue);
}

// Update color from text input
function updateColorFromInput(colorType, hexValue) {
    // Validate hex color
    if (/^#[0-9A-F]{6}$/i.test(hexValue)) {
        updateColorPreview(colorType, hexValue);
    }
}

// Opacity update function
function updateOpacity(opacityType, percentage) {
    const value = parseInt(percentage);

    // Update slider
    const sliderElement = document.getElementById(opacityType.replace('_', '-') + '-slider');
    if (sliderElement) {
        sliderElement.value = value;
    }

    // Update percentage display
    const valueElement = document.getElementById(opacityType.replace('_', '-') + '-value');
    if (valueElement) {
        valueElement.textContent = value + '%';
    }

    // Update decimal input
    const inputElement = document.getElementById(opacityType.replace('_', '-') + '-input');
    if (inputElement) {
        inputElement.value = (value / 100).toFixed(2);
    }

    // Update preview overlay
    const overlayElement = document.getElementById(opacityType.replace('_', '-') + '-overlay');
    if (overlayElement) {
        const opacity = (100 - value) / 100;
        overlayElement.style.opacity = opacity;
    }
}

// Update opacity from decimal input
function updateOpacityFromDecimal(opacityType, decimalValue) {
    const percentage = Math.round(parseFloat(decimalValue) * 100);
    updateOpacity(opacityType, percentage);
    saveOpacityToLivewire(opacityType, parseFloat(decimalValue));
}

// Save opacity (called on slider change)
function saveOpacity(opacityType, percentage) {
    const decimalValue = parseInt(percentage) / 100;
    saveOpacityToLivewire(opacityType, decimalValue);
}

// Save color to Livewire
function saveColorToLivewire(colorType, hexValue) {
    if (window.Livewire) {
        const component = window.Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
        if (component) {
            component.set('data.' + colorType, hexValue);
        }
    }
}

// Save opacity to Livewire
function saveOpacityToLivewire(opacityType, decimalValue) {
    if (window.Livewire) {
        const component = window.Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
        if (component) {
            component.set('data.' + opacityType, decimalValue);
        }
    }
}

// Helper function to determine if color is light
function isLightColor(hexColor) {
    const rgb = hexToRgb(hexColor);
    if (!rgb) return true;

    // Calculate luminance
    const luminance = (0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b) / 255;
    return luminance > 0.5;
}

// Helper function to convert hex to RGB
function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Load current values
    if (window.enhancedColorManager) {
        setTimeout(() => {
            window.enhancedColorManager.loadCurrentValues();
        }, 100);
    }
});

// Listen for Livewire updates
document.addEventListener('livewire:updated', function() {
    if (window.enhancedColorManager) {
        window.enhancedColorManager.loadCurrentValues();
    }
});
</script>

<style>
/* Enhanced slider styles */
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

/* Color picker enhancements */
input[type="color"] {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

input[type="color"]:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
