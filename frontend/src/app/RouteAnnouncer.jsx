import { useEffect, useRef, useState } from 'react'
import { useLocation } from 'react-router-dom'

// Moves focus to the page and announces its title after a route change.
export function RouteAnnouncer() {
  const { pathname, hash } = useLocation()
  const previous = useRef(pathname + hash)
  const [text, setText] = useState('')
  useEffect(() => {
    if (previous.current === pathname + hash) return
    previous.current = pathname + hash
    if (!hash) document.getElementById('main')?.focus({ preventScroll: true })
    const timer = setTimeout(() => setText(document.title), 250)
    return () => clearTimeout(timer)
  }, [pathname, hash])
  return <div className="sr-only" role="status" aria-live="polite">{text}</div>
}
