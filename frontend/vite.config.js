import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { visualizer } from 'rollup-plugin-visualizer'
import { fileURLToPath } from 'node:url'
const path = value => fileURLToPath(new URL(value, import.meta.url))

// Avoids a white flash before React mounts. Injected here because Vite's inline <style> handling
// fails on Windows when the working directory uses a lower-case drive letter.
const bootStyle = {
  name: 'ph-boot-style',
  transformIndexHtml: {
    order: 'post',
    handler: () => [{
      tag: 'style', injectTo: 'head',
      children: 'html{background:#fbf6ed}body{margin:0}#root:empty{min-height:100dvh;background:linear-gradient(#1b141c 0 4rem,#fbf6ed 4rem)}',
    }],
  },
}
export default defineConfig(({ command, mode }) => ({
  base: command === 'serve' ? '/' : '/site/',
  plugins: [react(), bootStyle, mode === 'analyze' && visualizer({ filename: 'qa/bundle.html', gzipSize: true })].filter(Boolean),
  build: {
    outDir: path('../backend/public/site/'),
    emptyOutDir: true,
    sourcemap: false,
    rollupOptions: {
      output: {
        // Stable vendor chunk: stays cached across deploys when only app code changes.
        manualChunks(id) {
          if (/node_modules[\\/](react|react-dom|scheduler|react-router|react-router-dom)[\\/]/.test(id)) return 'react'
        },
      },
    },
  },
  server: {
    fs: { allow: [path('./'), path('../backend/resources/catalog/')] },
    proxy: { '/api': process.env.PHELON_API_TARGET || 'http://127.0.0.1:8000' },
  },
}))
