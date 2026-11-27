import { useEffect } from 'react'
import { useLocation, useNavigationType } from 'react-router-dom'

// Scrolls to hash targets even when they arrive with a lazy-loaded page.
export function ScrollManager() {
  const { pathname, hash } = useLocation()
  const navType = useNavigationType()
  useEffect(() => {
    let frame = 0
    if (hash) {
      const id = decodeURIComponent(hash.slice(1))
      const started = performance.now()
      const seek = () => {
        const element = document.getElementById(id)
        if (element) { element.scrollIntoView({ block: 'start' }); return }
        if (performance.now() - started < 3000) frame = requestAnimationFrame(seek)
      }
      seek()
    } else if (navType !== 'POP') {
      window.scrollTo({ top: 0, left: 0, behavior: 'instant' })
    }
    return () => cancelAnimationFrame(frame)
  }, [pathname, hash, navType])
  return null
}
