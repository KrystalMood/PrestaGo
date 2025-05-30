import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/admin/users.js',
                'resources/js/admin/competitions.js',
                'resources/js/admin/periods.js',
                'resources/js/admin/programs.js'
            ],
            refresh: true,
        }),
    ],
});
