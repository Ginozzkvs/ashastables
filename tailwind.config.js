import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                dark: '#0f1419',
                'dark-card': '#1a1f2e',
                gold: {
                    DEFAULT: '#d4af37',
                    light: '#e6c547',
                    dim: 'rgba(212, 175, 55, 0.1)',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Noto Sans Lao', ...defaultTheme.fontFamily.sans],
                serif: ['Cormorant Garamond', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms],
};
