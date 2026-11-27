import { useEffect, useRef, useState } from 'react'
import { Link, NavLink } from 'react-router-dom'
import { bookLink, DISCOVERY, sentence, serviceIcons, services } from '../catalog'
import { Button } from '../components/Button'
import { Icon } from '../components/Icon'
import { Brand } from './Brand'
import { links } from './links'

export function Header({ menuOpen, setMenuOpen, menuButton }) {
  const [panel, setPanel] = useState(false)
  const [scrolled, setScrolled] = useState(false)
  const header = useRef(null)
  const trigger = useRef(null)
  const sentinel = useRef(null)

  useEffect(() => {
    const observer = new IntersectionObserver(([entry]) => setScrolled(!entry.isIntersecting))
    observer.observe(sentinel.current)
    return () => observer.disconnect()
  }, [])

  useEffect(() => {
    if (!panel) return
    const key = event => { if (event.key === 'Escape') { setPanel(false); trigger.current.focus() } }
    const outside = event => { if (!header.current.contains(event.target)) setPanel(false) }
    document.addEventListener('keydown', key)
    document.addEventListener('pointerdown', outside)
    return () => { document.removeEventListener('keydown', key); document.removeEventListener('pointerdown', outside) }
  }, [panel])

  const close = () => setPanel(false)
  return <>
    <div className="ph-sentinel" ref={sentinel} />
    <header className="ph-header" data-surface="dark" data-scrolled={scrolled ? '' : undefined} ref={header}>
      <div className="ph-container ph-header-row">
        <Brand />
        <nav className="ph-nav" aria-label="Main">
          <button type="button" className="ph-nav-trigger" ref={trigger} aria-expanded={panel} aria-controls="services-panel"
            onClick={() => setPanel(open => !open)}>Services<Icon name="chevronDown" size={16} /></button>
          {panel && <div className="ph-panel" id="services-panel">
            <ul>{Object.entries(services).map(([key, service]) => <li key={key}>
              <Link to={'/services/' + key} onClick={close}><Icon name={serviceIcons[key]} size={20} />
                <span>{sentence(service.title)}<small>{service.audience}</small></span></Link>
            </li>)}</ul>
            <div className="ph-panel-foot"><Link to="/services" onClick={close}>All services</Link><Link to="/start-here" onClick={close}>Start here</Link></div>
          </div>}
          {links.map(([to, label]) => <NavLink key={to} to={to} onClick={close}>{label}</NavLink>)}
          <Button to={bookLink()} onClick={close}>{DISCOVERY}</Button>
        </nav>
        <button type="button" className="ph-menu-button" ref={menuButton} aria-expanded={menuOpen} aria-controls="menu-sheet"
          aria-label={menuOpen ? 'Close menu' : 'Open menu'} onClick={() => setMenuOpen(open => !open)}><Icon name={menuOpen ? 'close' : 'menu'} /></button>
      </div>
    </header>
  </>
}
