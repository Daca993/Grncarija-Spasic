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
                // Bio: Inter / Fraunces -> novi izbor: Work Sans / Lora + Caveat za etno akcente
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Lora', ...defaultTheme.fontFamily.serif],
                accent: ['Caveat', 'cursive'],
            },
            colors: {
                etno: {
                    primary: 'oklch(52% 0.1 55)',   /* nova terakota, manje zasićena */
                    dark: 'oklch(24% 0.03 50)',      /* tamno braon-crna */
                    light: 'oklch(97% 0.015 75)',    /* topao off-white */
                    accent: 'oklch(58% 0.09 140)',   /* maslinasta, sekundarni akcenat */
                    border: 'oklch(24% 0.02 50 / 0.12)',
                    text: 'oklch(24% 0.02 50)',
                    /* Alias-i ka starim imenima klasa (etno-cream, etno-terracotta...) iz doba
                       pre v2 paletke, da postojeći blade fajlovi ne ostanu bez definisanih boja. */
                    cream: 'oklch(97% 0.015 75)',
                    beige: 'oklch(93% 0.02 65)',
                    sand: 'oklch(93% 0.025 60)',
                    clay: 'oklch(24% 0.02 50 / 0.35)',
                    brown: 'oklch(24% 0.02 50 / 0.85)',
                    terracotta: 'oklch(52% 0.1 55)',
                    rust: 'oklch(45% 0.1 55)',
                    red: 'oklch(52% 0.1 55)',
                },
            },
            borderRadius: {
                xs: '2px',
                sm: '4px',
                4: '4px',
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
