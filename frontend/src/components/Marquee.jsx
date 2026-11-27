import { Link } from 'react-router-dom'

// Two identical lists make a seamless loop; the second is hidden from assistive technology.
export function Marquee({ items, label }) {
  return <nav className="ph-marquee" aria-label={label}>
    <div className="ph-marquee-track">
      {[false, true].map(copy => <ul className="ph-marquee-list" key={String(copy)} aria-hidden={copy || undefined}>
        {items.map(item => <li key={item.to}><Link to={item.to} tabIndex={copy ? -1 : undefined}>{item.label}</Link></li>)}
      </ul>)}
    </div>
  </nav>
}
