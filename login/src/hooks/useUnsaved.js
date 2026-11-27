import { useEffect } from 'react'
import { confirmAction } from '../lib/alerts'

// Every form with unsaved changes holds a token; the shell asks before leaving while any exist.
const holders = new Set()
export const hasUnsaved = () => holders.size > 0
export const clearUnsaved = () => holders.clear()
export const confirmLeave = () => hasUnsaved()
  ? confirmAction({ title: 'You have unsaved changes', text: 'Leave without saving?', confirmText: 'Leave without saving', cancelText: 'Stay on this page' })
  : Promise.resolve(true)

export function useUnsaved(dirty) {
  useEffect(() => {
    if (!dirty) return
    const token = {}
    holders.add(token)
    // Closing or reloading the tab can only show the browser's own warning.
    const warn = event => { event.preventDefault(); event.returnValue = '' }
    window.addEventListener('beforeunload', warn)
    return () => { holders.delete(token); window.removeEventListener('beforeunload', warn) }
  }, [dirty])
}
