import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { fileURLToPath } from 'node:url'
const path = value => fileURLToPath(new URL(value, import.meta.url))
const target = process.env.PHELON_API_TARGET || 'http://127.0.0.1:8000'
export default defineConfig({
  base: '/staff/',
  plugins: [react()],
  build: { outDir: path('../backend/public/staff/'), emptyOutDir: true },
  server: { fs: { allow: [path('./'), path('../backend/resources/catalog/')] }, proxy: { '/api': target } },
})
