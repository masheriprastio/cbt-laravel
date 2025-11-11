import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',                    // Breeze/Tailwind (jika dipakai)
        // 'resources/js/spike/scss/style.scss',  // aktifkan HANYA jika file ini ada
      ],
      refresh: true,
    }),
  ],
  server: { host: '127.0.0.1', hmr: { host: '127.0.0.1' } },
})
