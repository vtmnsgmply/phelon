import { Icon } from './Icon'

export function Button({ variant = 'primary', icon, loading = false, href, className = '', children, ...rest }) {
  const classes = `btn btn--${variant} ${className}`.trim()
  const content = <>{loading ? <span className="spinner" aria-hidden="true" /> : icon && <Icon name={icon} size={18} />}{children}</>
  if (href) return <a className={classes} href={href} {...rest}>{content}</a>
  return <button type="button" className={classes} aria-busy={loading || undefined} {...rest} disabled={rest.disabled || loading}>{content}</button>
}
