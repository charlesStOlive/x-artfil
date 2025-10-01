import fs from 'fs';
import path from 'path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const isDev = process.env.NODE_ENV === 'development';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                'resources/css/front/theme.css',
                'resources/js/front/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    ...(isDev && {
        server: {
            host: '0.0.0.0',
            port: 5200,
            strictPort: true,
            cors: true,
            origin: 'https://x-artfil.test:5200',
            allowedHosts: ['x-artfil.test'],
            hmr: {
                host: 'x-artfil.test',
                port: 5200,
                protocol: 'wss',
            },
            https: {
                key: fs.readFileSync(path.resolve(__dirname, 'C:/laragon/etc/ssl/laragon.key')),
                cert: fs.readFileSync(path.resolve(__dirname, 'C:/laragon/etc/ssl/laragon.crt')),
            },
        },
    }),
});

