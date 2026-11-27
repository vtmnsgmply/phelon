import { Icon } from './Icon'

export function PasswordRules({ checks }) {
  return <ul className="rules" aria-label="Password requirements">
    {checks.map(([label, met]) => <li key={label} data-met={met ? '' : undefined}>
      <Icon name={met ? 'success' : 'info'} size={16} />{label}<span className="sr-only">{met ? ' (met)' : ' (not met)'}</span>
    </li>)}
  </ul>
}
