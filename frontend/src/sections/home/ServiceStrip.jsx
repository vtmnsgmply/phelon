import { sentence, services } from '../../catalog'
import { Marquee } from '../../components/Marquee'

export function ServiceStrip() {
  const items = Object.entries(services).map(([key, service]) => ({ to: '/services/' + key, label: sentence(service.title) }))
  return <div className="ph-strip" data-surface="dark"><Marquee items={items} label="Our services" /></div>
}
