import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import VueMacros from "unplugin-vue-macros/vite";
import dotenv from 'dotenv';
import path from 'path';

const envPath = path.resolve(__dirname, '../../../local/php_interface/include/.env');
const result = dotenv.config({ path: envPath });
if (result.error) {
    throw result.error;
}

const env = process.env;
export default defineConfig({
    plugins: [
        VueMacros({
            plugins: {
                vue: vue(),
            },
        }),
    ],
    base: `/${env.VITE_BASE_PATH}/${env.VITE_CLIENT_PATH}`,
    build: {
        ssr: true,
        outDir: 'dist/server',
        assetsDir: '.',
        copyPublicDir: false,
        rollupOptions: {
            input: {
                test: 'src/pages/test/entry-server.js',
            }
        },
    },
    resolve: {
        alias: {
            '@': '/src',
        },
    },
});
