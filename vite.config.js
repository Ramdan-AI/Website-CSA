import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
                'resources/css/vendor/landing-fonts.css',
                'resources/css/vendor/admin-fonts.css',
                'resources/css/vendor/auth-fonts.css',
                'resources/js/vendor/alpine.js',
                'resources/js/vendor/cropper.js',
                'resources/js/vendor/qr-scanner.js'
            ],
            refresh: true,
        }),
    ],
});
