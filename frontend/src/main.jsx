import { createRoot } from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import { AppRoutes } from './app/routes'
import { ScrollManager } from './app/ScrollManager'
import { Shell } from './app/Shell'
import { initSpotlight } from './hooks/spotlight'
import './styles/index.css'

initSpotlight()

createRoot(document.getElementById('root')).render(
  <BrowserRouter><ScrollManager /><Shell><AppRoutes /></Shell></BrowserRouter>,
)
