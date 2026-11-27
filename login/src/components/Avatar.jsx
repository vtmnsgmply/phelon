const initials = name => String(name || '').split(/\s+/).filter(Boolean).map(part => part[0]).slice(0, 2).join('').toUpperCase()

// The photo when there is one, otherwise initials. The name is shown beside it, so it is decorative.
export function Avatar({ name, src, large = false }) {
  const className = `avatar${large ? ' avatar--large' : ''}`
  return src ? <img className={className} src={src} alt="" /> : <span className={className} aria-hidden="true">{initials(name)}</span>
}
