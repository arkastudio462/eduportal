import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: '#000421',
                'on-primary': '#FFFFFF',
                'primary-container': '#D6E2FF',
                'on-primary-container': '#001946',
                'primary-fixed': '#D6E2FF',
                'on-primary-fixed': '#001946',
                'primary-fixed-dim': '#AAC7FF',
                'on-primary-fixed-variant': '#445E8A',

                secondary: '#A6600C',
                'on-secondary': '#FFFFFF',
                'secondary-container': '#FDDCA3',
                'on-secondary-container': '#351B00',
                'secondary-fixed': '#FDDCA3',
                'on-secondary-fixed': '#351B00',
                'secondary-fixed-dim': '#F9BA67',

                tertiary: '#FEAF2C',
                'on-tertiary': '#FFFFFF',
                'tertiary-container': '#FFDDB3',
                'on-tertiary-container': '#2D1600',
                'tertiary-fixed': '#FFDDB3',
                'tertiary-fixed-dim': '#C48D4D',

                error: '#BA1A1A',
                'on-error': '#FFFFFF',
                'error-container': '#FFDAD6',
                'on-error-container': '#410002',

                background: '#F9F9FF',
                'on-background': '#191C20',

                surface: '#F9F9FF',
                'on-surface': '#191C20',
                'surface-container-lowest': '#FFFFFF',
                'surface-container-low': '#F2F2F9',
                'surface-container': '#ECECF3',
                'surface-container-high': '#E6E6ED',
                'surface-container-highest': '#E0E0E8',
                'on-surface-variant': '#44474F',

                outline: '#74777F',
                'outline-variant': '#C4C6D0',
            },

            fontFamily: {
                'headline-xl': ['"Work Sans"', 'sans-serif'],
                'headline-lg': ['"Work Sans"', 'sans-serif'],
                'headline-md': ['"Work Sans"', 'sans-serif'],
                'headline-sm': ['"Work Sans"', 'sans-serif'],
                'body-lg': ['"Source Sans 3"', 'sans-serif'],
                'body-md': ['"Source Sans 3"', 'sans-serif'],
                'body-sm': ['"Source Sans 3"', 'sans-serif'],
                'label-md': ['"Work Sans"', 'sans-serif'],
                'label-sm': ['"Work Sans"', 'sans-serif'],
            },

            fontSize: {
                'headline-xl': ['2.5rem', { lineHeight: '1.2', fontWeight: '700' }],
                'headline-lg': ['2rem', { lineHeight: '1.25', fontWeight: '700' }],
                'headline-md': ['1.5rem', { lineHeight: '1.3', fontWeight: '700' }],
                'headline-sm': ['1.25rem', { lineHeight: '1.4', fontWeight: '600' }],
                'body-lg': ['1.125rem', { lineHeight: '1.6' }],
                'body-md': ['1rem', { lineHeight: '1.6' }],
                'body-sm': ['0.875rem', { lineHeight: '1.6' }],
                'label-md': ['0.875rem', { lineHeight: '1.4', fontWeight: '600' }],
                'label-sm': ['0.75rem', { lineHeight: '1.4', fontWeight: '600' }],
            },

            spacing: {
                'margin-desktop': '32px',
                'margin-mobile': '1rem',
                gutter: '1.5rem',
                base: '1rem',
            },

            maxWidth: {
                'max-width': '1280px',
            },
        },
    },

    plugins: [forms],
};
