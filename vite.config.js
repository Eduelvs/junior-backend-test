import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  server: {
    https: false,
    host: 'localhost',  // ou '0.0.0.0' se quiser aceitar conexões externas na rede local
    port: 5173,
    hmr: {
      host: 'localhost',  // importantíssimo ser o mesmo host usado no navegador
      protocol: 'ws',
    },
    cors: true, // libera CORS para evitar erros se backend e frontend tiverem portas diferentes
  },
})
