import { useEffect, useRef, useState } from 'react'
import { clearUnsaved, confirmLeave, hasUnsaved } from './useUnsaved'

const read = () => window.location.hash.replace(/^#\/?/, '') || 'overview'

// Hash routing keeps every screen refresh-safe under /login/ without server changes.
export function useHashRoute() {
  const [route, setRoute] = useState(read)
  const current = useRef(route)
  useEffect(() => {
    const change = async () => {
      const next = read()
      if (next === current.current) return
      if (hasUnsaved()) {
        // Stay put while asking. replaceState doesn't raise another hashchange.
        history.replaceState(null, '', '#/' + current.current)
        if (!await confirmLeave()) return
        history.replaceState(null, '', '#/' + next)
      }
      clearUnsaved()
      current.current = next
      setRoute(next)
      window.scrollTo(0, 0)
    }
    window.addEventListener('hashchange', change)
    return () => window.removeEventListener('hashchange', change)
  }, [])
  return route
}
