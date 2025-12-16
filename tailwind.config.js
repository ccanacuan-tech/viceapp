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
                beige: '#F5F5DC',
                red: { // Paleta de colores personalizada para el rojo
                  '500': '#EF4444', // Utilizado para botones principales
                  '600': '#DC2626', // El color rojo principal de la marca
                  '700': '#B91C1C', // Un tono más oscuro para estados hover
                  '900': '#7F1D1D'   // Un tono aún más oscuro
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
