import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input:
            [
                'resources/css/app.css',
                'resources/css/output.css',
                'resources/js/app.js',
                'resources/js/user/script.js',
                'resources/js/user/analytics.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
