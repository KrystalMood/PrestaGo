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
                'resources/js/admin/programs.js',
                'resources/js/admin/verification.js',
                'resources/js/admin/sub-competitions.js',
                'resources/js/admin/sub-competition-skills.js',
                'resources/js/admin/settings.js',
                'resources/js/admin/reports/index.js',
                'resources/js/admin/reports/achievements.js',
                'resources/js/admin/reports/programs.js',
                'resources/js/admin/reports/trends.js',
                'resources/js/admin/reports/periods.js',
                'resources/js/admin/reports/export.js',
                'resources/js/student/recommendations.js',
                'resources/js/student/competitions.js',
                'resources/js/dosen/competitions.js',
                'resources/js/dosen/sub-competitions.js',
                'resources/js/dosen/skills.js',
		'resources/js/admin/recommendations.js'
            ],
            refresh: true,
        }),
    ],
});
