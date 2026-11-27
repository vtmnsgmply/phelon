import { bookLink, DISCOVERY } from '../catalog'
import { Button } from '../components/Button'
import { usePageMeta } from '../hooks/usePageMeta'

export function NotFound() {
  usePageMeta('Page not found')
  return <div className="ph-section"><div className="ph-container ph-404">
    <svg viewBox="0 0 400 500" aria-hidden="true" focusable="false">
      <path d="M2 498V200a198 198 0 0 1 396 0v298" /><path d="M70 498V230a130 130 0 0 1 260 0v268" />
    </svg>
    <h1>We couldn’t find that page</h1>
    <p className="ph-lede">The address may have changed. Choose where to go next.</p>
    <div className="ph-actions">
      <Button to="/">Home</Button><Button to="/services" variant="secondary">Services</Button><Button to={bookLink()} variant="secondary">{DISCOVERY}</Button>
    </div>
  </div></div>
}
