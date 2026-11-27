import { useEffect, useRef, useState } from 'react'
import { Avatar } from '../components/Avatar'
import { Badge } from '../components/Badge'
import { Icon } from '../components/Icon'
import { navigationFor, PAGES } from './navigation'
import { NavLink } from './NavLink'
import { NavSection } from './NavSection'

const logo = import.meta.env.BASE_URL + 'logo-mark.webp'

export function AppShell({ user, route, preview, onSignOut, children }) {
  const [open, setOpen] = useState(false)
  const menu = useRef(null)
  const main = useRef(null)
  const first = useRef(true)
  const items = navigationFor(user)
  const current = PAGES.find(item => item.route === route)

  useEffect(() => {
    document.title = `${current?.label ?? 'Page not found'} | Staff back office`
    if (first.current) { first.current = false; return }
    setOpen(false)
    main.current.focus({ preventScroll: true })
  }, [route, current])
  useEffect(() => {
    if (!open) return
    const key = event => { if (event.key === 'Escape') { setOpen(false); menu.current.focus() } }
    document.addEventListener('keydown', key)
    return () => document.removeEventListener('keydown', key)
  }, [open])

  return <div className="app" data-open={open ? '' : undefined}>
    <a className="skip" href={'#/' + route} onClick={event => { event.preventDefault(); main.current.focus() }}>Skip to content</a>
    <header className="topbar">
      <button type="button" className="icon-btn menu-btn" ref={menu} aria-label={open ? 'Close menu' : 'Open menu'} aria-expanded={open} aria-controls="sidebar"
        onClick={() => setOpen(value => !value)}><Icon name={open ? 'close' : 'menu'} /></button>
      <span className="brand"><img src={logo} alt="" width="32" height="32" />Phelonpreneur</span>
    </header>
    <aside className="sidebar" id="sidebar">
      <span className="brand"><img src={logo} alt="" width="40" height="40" /><span>Phelonpreneur<small>Back office</small></span></span>
      <nav aria-label="Back office">
        {items.map((item, index) => <div key={item.id || item.route}>
          {item.group && items[index - 1]?.group !== item.group && <p className="nav-group">{item.group}</p>}
          {item.children ? <NavSection section={item} route={route} onNavigate={() => setOpen(false)} />
            : <NavLink item={item} route={route} onNavigate={() => setOpen(false)} />}
        </div>)}
      </nav>
      <div className="who">
        <Avatar name={user.displayName} src={user.avatarUrl} />
        <div><strong>{user.displayName}</strong><Badge tone={user.role === 'admin' ? 'gold' : 'neutral'}>{user.role === 'admin' ? 'Admin' : 'Staff'}</Badge></div>
        <button type="button" className="signout" onClick={onSignOut}><Icon name="signOut" size={18} />Sign out</button>
      </div>
    </aside>
    <button type="button" className="scrim" tabIndex={-1} aria-hidden="true" onClick={() => setOpen(false)} />
    <main className="content" ref={main} tabIndex={-1} inert={open}>
      {preview && <p className="preview-banner"><Icon name="info" size={16} />Preview mode: sample data. Nothing is sent to or saved on the server.</p>}
      <div className="content-inner">{children}</div>
    </main>
  </div>
}
