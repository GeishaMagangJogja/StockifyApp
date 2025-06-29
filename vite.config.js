import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/auth/login.js',
                'resources/js/auth/register.js',
                'resources/js/dashboard/index.js',
                'resources/js/product/list.js',
                'resources/js/supplier/crud.js',
                'resources/js/stock/transaction.js',
            ],
            refresh: true,
        }),
    ],
});
