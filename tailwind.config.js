import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/app.css',
        './resources/views/components.css',
        './resources/views/theme.css',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                display: ['Dancing Script', 'cursive'],
            },
            colors: {
                // Custom Pink Palette
                'soft-pink': {
                    50: '#FDF2F8',
                    100: '#FCE7F3',
                    200: '#FBCFE8',
                    300: '#F9A8D4',
                    400: '#F472B6',
                    500: '#EC4899',
                    600: '#DB2777',
                    700: '#BE185D',
                    800: '#9D174D',
                    900: '#831843',
                },
                // Accent Colors
                'rose-soft': '#FFF1F2',
                'pink-gradient': {
                    start: '#EC4899',
                    middle: '#F9A8D4',
                    end: '#FCE7F3',
                },
            },
            backgroundImage: {
                'gradient-pink': 'linear-gradient(135deg, #EC4899 0%, #F9A8D4 50%, #FCE7F3 100%)',
                'gradient-pink-soft': 'linear-gradient(135deg, #F9A8D4 0%, #FBCFE8 50%, #FCE7F3 100%)',
                'gradient-pink-vibrant': 'linear-gradient(135deg, #EC4899 0%, #F472B6 100%)',
                'gradient-pink-light': 'linear-gradient(135deg, #FCE7F3 0%, #FDF2F8 100%)',
            },
            boxShadow: {
                'pink-sm': '0 1px 2px 0 rgba(236, 72, 153, 0.05)',
                'pink-md': '0 4px 6px -1px rgba(236, 72, 153, 0.1)',
                'pink-lg': '0 10px 15px -3px rgba(236, 72, 153, 0.1)',
                'pink-xl': '0 20px 25px -5px rgba(236, 72, 153, 0.1)',
                'pink-2xl': '0 25px 50px -12px rgba(236, 72, 153, 0.15)',
                'pink-inner': 'inset 0 2px 4px 0 rgba(236, 72, 153, 0.06)',
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'blob': 'blob 7s infinite',
                'sparkle': 'sparkle 2s ease-in-out infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                blob: {
                    '0%, 100%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                },
                sparkle: {
                    '0%, 100%': { opacity: 0, transform: 'scale(0)' },
                    '50%': { opacity: 1, transform: 'scale(1)' },
                },
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [
        forms,
        // Custom plugin untuk komponen glass morphism
        function({ addUtilities }) {
            const newUtilities = {
                '.glass-pink': {
                    background: 'rgba(255, 255, 255, 0.85)',
                    backdropFilter: 'blur(10px)',
                    border: '1px solid rgba(251, 207, 232, 0.3)',
                    boxShadow: '0 4px 6px -1px rgba(236, 72, 153, 0.1)',
                },
                '.glass-pink-dark': {
                    background: 'rgba(219, 39, 119, 0.1)',
                    backdropFilter: 'blur(10px)',
                    border: '1px solid rgba(219, 39, 119, 0.2)',
                    boxShadow: '0 4px 6px -1px rgba(219, 39, 119, 0.1)',
                },
                '.text-gradient-pink': {
                    background: 'linear-gradient(135deg, #EC4899, #F9A8D4)',
                    '-webkit-background-clip': 'text',
                    '-webkit-text-fill-color': 'transparent',
                    'background-clip': 'text',
                },
            }
            addUtilities(newUtilities)
        }
    ],
}