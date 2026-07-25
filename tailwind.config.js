import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                sage: {
                    50: '#F8FBFA',
                    100: '#D4E6DF',
                    200: '#C8DDD5',
                },
                emeraldAction: {
                    DEFAULT: '#157F5C',
                    hover: '#0F6E52',
                }
            },
        },
    },

    plugins: [forms],
};