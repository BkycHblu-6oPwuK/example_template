import { defineConfig } from 'vite'

import vue from '@vitejs/plugin-vue';
import VueMacros from "unplugin-vue-macros/vite";

import dotenv from 'dotenv';
import path from 'path';

const envPath = path.resolve(__dirname, '../../../local/php_interface/include/.env'); // путь до .env относительно текущей директории p.s. MODE и значение production в js файле так же установится автоматически
const result = dotenv.config({ path: envPath });

if (result.error) {
  throw result.error; // в контейнере node, в консоли, можно посмотреть сообщение об ошибке
}

const env = process.env;
const base = env.MODE === 'production' ?  `/${env.VITE_BASE_PATH}/${env.VITE_CLIENT_PATH}` : `/${env.VITE_BASE_PATH}`;
export default defineConfig({
    plugins: [
        VueMacros({
            plugins: {
                vue: vue(),
            },
        }),
    ],
    //define: {
    //    'process.env': env // можно передать переменные в клиентский код
    //},
    base: base,
    build: {
        outDir: env.VITE_CLIENT_PATH,
        assetsDir: '.',
        copyPublicDir: false,
        manifest: true,
        rollupOptions: {
            input: {
                bundle: 'src/common/js/bundle.ts',
                header: 'src/common/js/header.ts',
                main: 'src/common/js/main.ts',
                footer: 'src/common/js/footer.ts',
                app: 'src/app/index.ts'
            },
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            }
        },
    },
    resolve: {
        alias: {
            '@': '/src',
        },
    },
    server: {
        host: '0.0.0.0',
        port: env.VITE_PORT,
        open: false,
        cors: {
            origin: '*'
        },
        hmr: {
            host: 'localhost',
        },
    }
});
