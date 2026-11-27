import { Link } from 'react-router-dom'
import { bookLink, DISCLAIMER, DISCOVERY, sentence, services } from '../catalog'
import { Button } from '../components/Button'
import { Brand } from './Brand'

const company = [['/about', 'About'], ['/start-here', 'Start here'], ['/webinar', 'Webinar'], ['/contact', 'Contact'], ['/#who', 'Who we support']]
const legal = [['/privacy-policy', 'Privacy policy'], ['/terms-of-service', 'Terms of service'], ['/cookie-policy', 'Cookie policy'], ['/disclaimer', 'Disclaimer']]

function Column({ title, items }) {
  return <nav aria-label={title}><h2>{title}</h2><ul>{items.map(([to, label]) => <li key={to}><Link to={to}>{label}</Link></li>)}</ul></nav>
}

export function Footer({ inert }) {
  return <footer className="ph-footer" data-surface="dark" inert={inert}>
    <div className="ph-container">
      <div className="ph-footer-grid">
        <div className="ph-footer-brand ph-stack">
          <div><Brand /></div>
          <p className="ph-muted">The attorney handles the legal case. PHELONPRENEUR helps stabilize the life around it.</p>
          <div><Button to={bookLink()}>{DISCOVERY}</Button></div>
        </div>
        <Column title="Services" items={[...Object.entries(services).map(([key, service]) => ['/services/' + key, sentence(service.title)]), ['/services', 'All services']]} />
        <Column title="Company" items={company} />
        <Column title="Legal" items={legal} />
      </div>
      <div className="ph-footer-legal"><p>{DISCLAIMER}</p><p>© {new Date().getFullYear()} The PHELONPRENEUR Network.</p></div>
    </div>
  </footer>
}
