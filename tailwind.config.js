/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    light: '#6366F1', // indigo-500
                    DEFAULT: '#4F46E5', // indigo-600
                    dark: '#4338CA',   // indigo-700
                },
            },
            boxShadow: {
                'custom': '0 10px 25px -5px rgba(99, 102, 241, 0.1), 0 8px 10px -6px rgba(99, 102, 241, 0.1)',
            }
        },
    },
    plugins: [require("daisyui")],
} 