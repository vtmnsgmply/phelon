import { useEffect, useId, useRef } from 'react'
import { Icon } from './Icon'

// Native modal dialog: focus is kept inside and returns to the opener when it closes.
export function Dialog({ title, onClose, children }) {
  const ref = useRef(null)
  const id = useId()
  useEffect(() => {
    const dialog = ref.current
    const opener = document.activeElement
    if (!dialog.open) dialog.showModal()
    return () => { dialog.close(); opener?.focus?.() }
  }, [])
  return <dialog className="dialog" ref={ref} aria-labelledby={id} onCancel={event => { event.preventDefault(); onClose() }}>
    <header className="dialog-head"><h2 id={id}>{title}</h2>
      <button type="button" className="icon-btn" aria-label="Close" onClick={onClose}><Icon name="close" /></button></header>
    {children}
  </dialog>
}
