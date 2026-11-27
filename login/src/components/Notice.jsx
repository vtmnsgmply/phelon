import { Icon } from './Icon'

const icons = { info: 'info', success: 'success', warning: 'alert', danger: 'alert' }

export function Notice({ tone = 'info', title, children }) {
  return <div className={`notice notice--${tone}`} role={tone === 'danger' ? 'alert' : 'status'}>
    <Icon name={icons[tone]} />
    <div>{title && <strong>{title}</strong>}{children}</div>
  </div>
}
