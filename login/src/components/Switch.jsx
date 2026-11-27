import { useId } from 'react'

export function Switch({ label, help, checked, onChange, disabled = false, hideLabel = false }) {
  const id = useId()
  const name = useId()
  return <label className="switch" data-disabled={disabled ? '' : undefined}>
    <input type="checkbox" role="switch" checked={checked} disabled={disabled} aria-labelledby={name} aria-describedby={help ? id : undefined}
      onChange={event => onChange(event.target.checked)} />
    <span className="switch-track" aria-hidden="true" />
    <span className={hideLabel ? 'sr-only' : 'switch-text'}><span id={name}>{label}</span>{help && <small id={id}>{help}</small>}</span>
  </label>
}
