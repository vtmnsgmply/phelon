import { useId } from 'react'
import { Icon } from './Icon'

export function Field({ label, hint, error, className = '', children }) {
  const id = useId()
  const describedBy = [hint && `${id}-hint`, error && `${id}-error`].filter(Boolean).join(' ') || undefined
  return <div className={`field ${className}`.trim()}>
    <label htmlFor={id}>{label}</label>
    {children({ id, className: 'control', 'aria-describedby': describedBy, 'aria-invalid': error ? true : undefined })}
    {hint && <p className="hint" id={`${id}-hint`}>{hint}</p>}
    {error && <p className="error" id={`${id}-error`}><Icon name="alert" size={16} />{error}</p>}
  </div>
}
