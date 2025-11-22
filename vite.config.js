import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/calendar.js',
            ],
            refresh: true,
        }),
    ],

    // 🔥 Isso evita o HTTP e resolve o Mixed Content no Railway
    build: {
        manifest: true,
    },

    server: {
        https: true,
        host: true,
    },
});
