/**
 * Theme manager for Downtown Bellefontaine.
 * Three preferences: 'light', 'dark', or 'system' (default).
 * 'system' follows the OS preference live and is stored as the absence of a key.
 */

const ThemeManager = {
    STORAGE_KEY: 'theme',
    DARK_CLASS: 'dark',

    init() {
        this.applyPreference(this.getPreference());

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupButtons());
        } else {
            this.setupButtons();
        }

        this.watchSystemTheme();
    },

    getPreference() {
        return localStorage.getItem(this.STORAGE_KEY) || 'system';
    },

    resolve(preference) {
        if (preference === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return preference;
    },

    applyPreference(preference) {
        const theme = this.resolve(preference);
        document.documentElement.classList.toggle(this.DARK_CLASS, theme === 'dark');
        this.updateButtons(preference);
    },

    setPreference(preference) {
        if (preference === 'system') {
            localStorage.removeItem(this.STORAGE_KEY);
        } else {
            localStorage.setItem(this.STORAGE_KEY, preference);
        }
        this.applyPreference(preference);
    },

    CYCLE: ['light', 'dark', 'system'],

    // Advance to the next preference in the cycle.
    cycle() {
        const current = this.getPreference();
        const next = this.CYCLE[(this.CYCLE.indexOf(current) + 1) % this.CYCLE.length];
        this.setPreference(next);
    },

    // Legacy two-way toggle (kept for any [data-theme-toggle]).
    toggle() {
        const current = document.documentElement.classList.contains(this.DARK_CLASS) ? 'dark' : 'light';
        this.setPreference(current === 'dark' ? 'light' : 'dark');
    },

    setupButtons() {
        document.querySelectorAll('[data-theme-cycle]').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.cycle();
            });
        });

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
        });

        document.querySelectorAll('[data-theme-set]').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.setPreference(button.getAttribute('data-theme-set'));
            });
        });

        this.updateButtons(this.getPreference());
    },

    updateButtons(preference) {
        const labels = { light: 'Light', dark: 'Dark', system: 'System' };

        // Single cycling button: show only the current mode's icon.
        document.querySelectorAll('[data-theme-cycle]').forEach((button) => {
            button.querySelectorAll('[data-theme-icon]').forEach((icon) => {
                icon.classList.toggle('hidden', icon.getAttribute('data-theme-icon') !== preference);
            });
            button.setAttribute('aria-label', `Theme: ${labels[preference]} (click to change)`);
            button.setAttribute('title', `Theme: ${labels[preference]}`);
        });

        // Legacy segmented buttons (if present).
        document.querySelectorAll('[data-theme-set]').forEach((button) => {
            const active = button.getAttribute('data-theme-set') === preference;
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
            button.classList.toggle('active', active);
        });
    },

    watchSystemTheme() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.getPreference() === 'system') {
                this.applyPreference('system');
            }
        });
    },
};

ThemeManager.init();

window.ThemeManager = ThemeManager;
