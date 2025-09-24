// Dynamic Theme Colors Loader
// This script loads theme colors from the server and updates CSS variables globally

class ThemeColorLoader {
    constructor() {
        this.loadThemeColors();
    }

    async loadThemeColors() {
        try {
            // Create a simple endpoint to get theme colors
            const response = await fetch('/api/theme-colors');
            if (!response.ok) {
                console.warn('Could not load theme colors, using defaults');
                return;
            }

            const colors = await response.json();
            this.applyThemeColors(colors);
        } catch (error) {
            console.warn('Theme colors fetch failed:', error);
            // Fallback to inline method if API fails
            this.loadColorsFromMeta();
        }
    }

    loadColorsFromMeta() {
        // Fallback: try to get colors from meta tags
        const colorMeta = document.querySelector('meta[name="theme-colors"]');
        if (colorMeta) {
            try {
                const colors = JSON.parse(colorMeta.getAttribute('content'));
                this.applyThemeColors(colors);
            } catch (error) {
                console.warn('Could not parse theme colors from meta tag');
            }
        }
    }

    applyThemeColors(colors) {
        const root = document.documentElement;

        // Convert hex to RGB for each color
        Object.entries(colors).forEach(([colorType, hexValue]) => {
            const rgb = this.hexToRgb(hexValue);
            if (rgb) {
                root.style.setProperty(`--theme-${colorType}`, `${rgb.r}, ${rgb.g}, ${rgb.b}`);
            }
        });

        console.log('Theme colors applied:', colors);
    }

    hexToRgb(hex) {
        // Remove # if present
        hex = hex.replace('#', '');

        // Handle 3-character hex
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }

        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);

        return isNaN(r) || isNaN(g) || isNaN(b) ? null : { r, g, b };
    }

    // Method to refresh colors (useful when settings are saved)
    refresh() {
        this.loadThemeColors();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.themeColorLoader = new ThemeColorLoader();
});

// Make refresh available globally for when settings are saved
window.refreshThemeColors = () => {
    if (window.themeColorLoader) {
        window.themeColorLoader.refresh();
    }
};
