import { useEffect } from 'react'
import { Link, NavLink } from 'react-router-dom'
import { bookLink, DISCOVERY, sentence, serviceIcons, services } from '../catalog'
import { Button } from '../components/Button'
import { Icon } from '../components/Icon'
import { links } from './links'

export function MenuSheet({ open, onClose, menuButton }) {
  useEffect(() => {
    if (!open) return
    const key = event => { if (event.key === 'Escape') { onClose(); menuButton.current?.focus() } }
    const wide = matchMedia('(min-width: 62em)')
    const resize = () => { if (wide.matches) onClose() }
    document.addEventListener('keydown', key)
    wide.addEventListener('change', resize)
    document.documentElement.style.overflow = 'hidden'
    return () => {
      document.removeEventListener('keydown', key)
      wide.removeEventListener('change', resize)
      document.documentElement.style.overflow = ''
    }
  }, [open, onClose, menuButton])

  return <div className="ph-sheet" id="menu-sheet" data-surface="dark" data-open={open ? '' : undefined}>
    <nav className="ph-sheet-scroll" aria-label="Menu">
      <details><summary>Services<Icon name="chevronDown" size={20} /></summary>
        <ul>{Object.entries(services).map(([key, service]) => <li key={key}>
          <Link to={'/services/' + key} onClick={onClose}><Icon name={serviceIcons[key]} size={20} />{sentence(service.title)}</Link></li>)}
          <li><Link to="/services" onClick={onClose}>All services</Link></li></ul>
      </details>
      <ul>{links.map(([to, label]) => <li key={to}><NavLink to={to} onClick={onClose}>{label}</NavLink></li>)}</ul>
    </nav>
    <div className="ph-sheet-foot"><Button to={bookLink()} size="lg" onClick={onClose}>{DISCOVERY}</Button></div>
  </div>
}
