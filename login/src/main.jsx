import { createRoot } from 'react-dom/client'
import { App } from './App'
import { serverApi } from './api/server'
import './fonts.css'
import './tokens.css'
import './app.css'
import './alerts.css'

async function start() {
  let api = serverApi
  // Sample-data preview for development: /staff/?preview. The condition is false in production, so none of it is built.
  if (import.meta.env.DEV && new URLSearchParams(window.location.search).has('preview')) {
    api = (await import('./api/preview')).createPreviewApi()
  }
  createRoot(document.getElementById('root')).render(<App api={api} />)
}
start()
