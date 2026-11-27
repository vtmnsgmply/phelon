import { useId } from 'react'
import { Icon } from './Icon'

// Label above, control, then hint and error, linked with aria-describedby.
export function Field({ label, hint, error, optional = false, className = '', children }) {
  const id = useId()
  const describedBy = [hint && `${id}-hint`, error && `${id}-error`].filter(Boolean).join(' ') || undefined
  return <div className={`ph-field ${className}`.trim()}>
    <label htmlFor={id}>{label}{optional && <span className="ph-optional"> (optional)</span>}</label>
    {children({ id, className: 'ph-control', 'aria-describedby': describedBy, 'aria-invalid': error ? true : undefined })}
    {hint && <p className="ph-hint" id={`${id}-hint`}>{hint}</p>}
    {error && <p className="ph-error" id={`${id}-error`}><Icon name="alert" size={18} /><span>{error}</span></p>}
  </div>
}
