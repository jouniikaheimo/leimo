import './bootstrap';

// Live Theme Manager for real-time color preview
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

        init() {
            console.log('LiveThemeManager initializing...');
            // Load current colors from form inputs
            this.loadCurrentColorsFromForm();

            // Watch for form changes
            this.watchFormChanges();

            // Also watch for Alpine data changes
            this.watchAlpineChanges();
        },

        loadCurrentColorsFromForm() {
            // Get Filament form data
            const formData = this.$wire?.get('data') || this.getFormDataDirectly();

            if (formData) {
                Object.keys(this.currentColors).forEach(key => {
                    if (formData.hasOwnProperty(key)) {
                        this.currentColors[key] = formData[key];
                    }
                });
                console.log('Colors loaded from form:', this.currentColors);
            }
        },

        getFormDataDirectly() {
            // Fallback: read directly from form inputs
            const formData = {};
            const form = document.querySelector('form[wire\\:submit\\.prevent]');

            if (form) {
                Object.keys(this.currentColors).forEach(key => {
                    const input = form.querySelector(`input[name="data.${key}"], input[data-field-name="${key}"]`);
                    if (input) {
                        formData[key] = input.value;
                    }
                });
            }

            return formData;
        },

        watchAlpineChanges() {
            // Watch for changes in Alpine data
            if (this.$wire) {
                this.$wire.$watch('data', (newData) => {
                    Object.keys(this.currentColors).forEach(key => {
                        if (newData && newData.hasOwnProperty(key)) {
                            this.currentColors[key] = newData[key];
                        }
                    });
                });
            }
        },

        watchFormChanges() {
            // Listen for color picker changes with more specific selectors
            document.addEventListener('input', (e) => {
                const input = e.target;
                if (input.type === 'color' || input.type === 'text') {
                    const fieldName = this.extractFieldName(input);
                    if (fieldName && this.currentColors.hasOwnProperty(fieldName)) {
                        this.currentColors[fieldName] = input.value;
                        console.log(`Updated ${fieldName} to ${input.value}`);
                    }
                }
            });

            // Listen for Livewire updates
            document.addEventListener('livewire:updated', () => {
                setTimeout(() => {
                    this.loadCurrentColorsFromForm();
                }, 100);
            });

            // Listen for Alpine updates
            document.addEventListener('alpine:updated', () => {
                this.loadCurrentColorsFromForm();
            });
        },

        extractFieldName(input) {
            // Try different ways to extract field name from Filament inputs
            if (input.name) {
                // Handle names like "data.primary" or "data[primary]"
                const match = input.name.match(/data[\.\[]([^\]\.]*)[\]\.]?/);
                if (match) {
                    return match[1];
                }
            }

            // Try data attributes
            if (input.dataset.fieldName) {
                return input.dataset.fieldName;
            }

            // Try wire:model
            const wireModel = input.getAttribute('wire:model');
            if (wireModel) {
                const match = wireModel.match(/data\.(.+)/);
                if (match) {
                    return match[1];
                }
            }

            return null;
        },

        updateColor(colorName, colorValue) {
            this.currentColors[colorName] = colorValue;

            // Update CSS custom property for live preview
            const cssVar = `--color-${colorName.replace('_', '-')}`;
            document.documentElement.style.setProperty(cssVar, colorValue);

            // Trigger any theme update events
            this.applyThemeChanges();
        },

        applyThemeChanges() {
            // Apply changes to Filament theme system if available
            if (window.Filament?.theme) {
                window.Filament.theme.update(this.currentColors);
            }

            // Dispatch custom event for other components
            window.dispatchEvent(new CustomEvent('theme-updated', {
                detail: { colors: this.currentColors }
            }));
        }
    };
};
