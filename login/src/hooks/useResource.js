import { useCallback, useEffect, useState } from 'react'

const MISSING = [404, 405, 501]

// Loads data once, and again on reload(). "unavailable" means the server has no such endpoint yet.
export function useResource(load, fallback) {
  const [state, setState] = useState({ status: 'loading', data: undefined, error: '' })
  const [version, setVersion] = useState(0)
  useEffect(() => {
    const controller = new AbortController()
    load(controller.signal)
      .then(data => { if (!controller.signal.aborted) setState({ status: 'ready', data, error: '' }) })
      .catch(error => {
        if (controller.signal.aborted || error.status === 401) return
        if (MISSING.includes(error.status) && fallback) setState({ status: 'unavailable', data: fallback(), error: '' })
        else setState({ status: 'error', data: undefined, error: error.status ? error.message : 'We couldn’t reach the server. Check your connection and try again.' })
      })
    return () => controller.abort()
    // The loader and fallback are stable for the life of a page.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [version])
  const reload = useCallback(() => { setState({ status: 'loading', data: undefined, error: '' }); setVersion(value => value + 1) }, [])
  return { ...state, reload }
}
