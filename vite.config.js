import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0', // Bindet den Server an alle verfügbaren IP-Adressen
        port: 5173,      // Standard-Vite-Port
        strictPort: true, // Gibt einen Fehler aus, wenn der Port bereits verwendet wird
        hmr: {
            host: 'localhost', // Hostname für Hot Module Replacement
            protocol: 'ws',    // WebSocket-Protokoll für HMR
        },
    },
});
