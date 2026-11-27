import { Icon } from './Icon'

const icons = { info: 'info', success: 'success', warning: 'alert', danger: 'alert' }

export function Notice({ tone = 'info', role, children }) {
  return <div className={`ph-notice ph-notice--${tone}`} role={role ?? (tone === 'danger' ? 'alert' : 'status')}>
    <Icon name={icons[tone]} size={20} /><div>{children}</div>
  </div>
}
