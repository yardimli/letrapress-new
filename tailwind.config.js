import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                serif: ['Georgia', 'Cambria', 'Times New Roman', 'Times', 'serif'],
                sans: ['Arial', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                paper: '#f3eddf',
                ink: '#201e1a',
                claret: '#7f1d1d',
                brass: '#a16207',
            },
            boxShadow: {
                print: '4px 4px 0 rgba(32, 30, 26, .16)',
            },
        },
    },

    plugins: [forms],
};
