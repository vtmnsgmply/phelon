import { useState } from 'react'
import { Field } from './Field'
import { Icon } from './Icon'

// Password input with a show/hide control and a Caps Lock warning.
export function PasswordField({ label, hint, error, ...input }) {
  const [shown, setShown] = useState(false)
  const [caps, setCaps] = useState(false)
  const detect = event => setCaps(Boolean(event.getModifierState?.('CapsLock')))
  return <Field label={label} error={error} hint={caps ? 'Caps Lock is on.' : hint}>{props => <div className="password">
    <input {...props} {...input} type={shown ? 'text' : 'password'} onKeyUp={detect} onKeyDown={detect} onBlur={() => setCaps(false)} />
    <button type="button" aria-label={`${shown ? 'Hide' : 'Show'} ${label.toLowerCase()}`} aria-pressed={shown} onClick={() => setShown(value => !value)}>
      <Icon name={shown ? 'eyeOff' : 'eye'} size={18} />
    </button>
  </div>}</Field>
}
