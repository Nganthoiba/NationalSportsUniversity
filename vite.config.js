import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

var host_ip = "10.178.2.16";
// var host_ip = "127.0.0.1";
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],

    server: {
        host: host_ip,  // Or set your specific IP
        port: 5173,        // Change if needed
        strictPort: true,
        hmr: {
            host: host_ip, // Set this to the IP you are running on
        },
    },
});
