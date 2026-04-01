/**
 * Theme Toggle for Downtown Bellefontaine
 * Handles light/dark mode switching with localStorage persistence
 */

const ThemeManager = {
    STORAGE_KEY: 'theme',
    DARK_CLASS: 'dark',

    init() {
        // Apply theme immediately to prevent flash
        this.applyTheme(this.getStoredTheme());

        // Set up toggle buttons when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupToggles());
        } else {
            this.setupToggles();
        }

        // Listen for system theme changes
        this.watchSystemTheme();
    },

    getStoredTheme() {
        const stored = localStorage.getItem(this.STORAGE_KEY);
        if (stored) {
            return stored;
        }
        // Default to system preference
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    },

    applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add(this.DARK_CLASS);
        } else {
            document.documentElement.classList.remove(this.DARK_CLASS);
        }
        // Update any toggle buttons
        this.updateToggleButtons(theme);
    },

    setTheme(theme) {
        localStorage.setItem(this.STORAGE_KEY, theme);
        this.applyTheme(theme);
    },

    toggle() {
        const currentTheme = document.documentElement.classList.contains(this.DARK_CLASS) ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    },

    setupToggles() {
        // Find all theme toggle buttons
        document.querySelectorAll('[data-theme-toggle]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
        });

        // Find specific theme buttons (light/dark)
        document.querySelectorAll('[data-theme-set]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const theme = button.getAttribute('data-theme-set');
                this.setTheme(theme);
            });
        });

        // Initial update of toggle button states
        const currentTheme = document.documentElement.classList.contains(this.DARK_CLASS) ? 'dark' : 'light';
        this.updateToggleButtons(currentTheme);
    },

    updateToggleButtons(theme) {
        // Update toggle button icons/states
        document.querySelectorAll('[data-theme-toggle]').forEach(button => {
            const sunIcon = button.querySelector('[data-theme-icon="sun"]');
            const moonIcon = button.querySelector('[data-theme-icon="moon"]');

            if (sunIcon && moonIcon) {
                if (theme === 'dark') {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
        });

        // Update active states on theme set buttons
        document.querySelectorAll('[data-theme-set]').forEach(button => {
            const buttonTheme = button.getAttribute('data-theme-set');
            if (buttonTheme === theme) {
                button.classList.add('active');
                button.setAttribute('aria-pressed', 'true');
            } else {
                button.classList.remove('active');
                button.setAttribute('aria-pressed', 'false');
            }
        });
    },

    watchSystemTheme() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            // Only update if user hasn't set a preference
            if (!localStorage.getItem(this.STORAGE_KEY)) {
                this.applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }
};

// Initialize theme manager
ThemeManager.init();

// Export for use in other scripts
window.ThemeManager = ThemeManager;
