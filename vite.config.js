import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue2 from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/site.css', 
                'resources/js/site.js',
                'resources/js/cp.js',
                'resources/css/cp.css'
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue2(),
    ],
    server: { 
        host: '0.0.0.0',
    }
});
