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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Fraunces', 'Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                etno: {
                    primary: '#C1652F',    /* Terakota */
                    dark: '#2A2320',       /* Tamni braon */
                    light: '#FAF7F2',      /* Svetli bež */
                    accent: '#B8956E',     /* Zlatna */
                    border: '#E8DECC',     /* Siva bež */
                    text: '#2A2320',       /* Tekst */
                },
            },
            borderRadius: {
                xs: '2px',
                sm: '4px',
            },
            boxShadow: {
                sm: '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                md: '0 4px 6px -1px rgba(42, 35, 32, 0.1)',
                lg: '0 12px 32px rgba(42, 35, 32, 0.15)',
                xl: '0 20px 48px rgba(42, 35, 32, 0.2)',
            },
        },
    },

    plugins: [forms],
};
