import { useCallback, useRef, useState } from 'react'
import { useLocation } from 'react-router-dom'
import { Footer } from './Footer'
import { Header } from './Header'
import { MenuSheet } from './MenuSheet'
import { RouteAnnouncer } from './RouteAnnouncer'
import { StickyCta } from './StickyCta'

const NO_CTA = /^\/(?:book|intake|webinar\/checkout\/|webinar\/resources)/

export function Shell({ children }) {
  const { pathname } = useLocation()
  const [menuOpen, setMenuOpen] = useState(false)
  const menuButton = useRef(null)
  const closeMenu = useCallback(() => setMenuOpen(false), [])
  const showCta = !NO_CTA.test(pathname)
  return <div data-has-cta={showCta ? '' : undefined}>
    <a className="ph-skip" href="#main">Skip to content</a>
    <Header menuOpen={menuOpen} setMenuOpen={setMenuOpen} menuButton={menuButton} />
    <MenuSheet open={menuOpen} onClose={closeMenu} menuButton={menuButton} />
    <main id="main" tabIndex={-1} inert={menuOpen}>{children}</main>
    <Footer inert={menuOpen} />
    {showCta && <StickyCta key={pathname} home={pathname === '/' || pathname === '/home'} inert={menuOpen} />}
    <RouteAnnouncer />
  </div>
}
