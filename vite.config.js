import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    base: './',  // ← ใช้ relative path
    server: {
        https: false,
        host: true,
    },
    build: {
        manifest: true,
        outDir: 'public/build',
    },
});