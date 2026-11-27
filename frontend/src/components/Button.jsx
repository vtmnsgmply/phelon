import { Link } from 'react-router-dom'
import { Icon } from './Icon'

export function Button({ to, href, variant = 'primary', size = 'md', icon, loading = false, className = '', children, ...rest }) {
  const classes = `ph-button ph-button--${variant}${size === 'lg' ? ' ph-button--lg' : ''} ${className}`.trim()
  const content = <>{loading ? <span className="ph-spinner" aria-hidden="true" /> : icon && <Icon name={icon} size={18} />}{children}</>
  if (to) return <Link to={to} className={classes} {...rest}>{content}</Link>
  if (href) return <a href={href} className={classes} {...rest}>{content}</a>
  return <button type="button" className={classes} aria-busy={loading || undefined} {...rest}>{content}</button>
}
