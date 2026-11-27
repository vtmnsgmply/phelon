import { Icon } from './Icon'

export function EmptyState({ icon = 'info', title, children }) {
  return <div className="empty"><span className="empty-icon"><Icon name={icon} size={24} /></span><strong>{title}</strong>{children && <p className="muted">{children}</p>}</div>
}
