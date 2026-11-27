import { Link, useParams } from 'react-router-dom'
import { bookLink, DISCOVERY, hasService, sentence, serviceIcons, serviceImages, services } from '../catalog'
import { Accordion } from '../components/Accordion'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Icon } from '../components/Icon'
import { PageHero } from '../components/PageHero'
import { usePageMeta } from '../hooks/usePageMeta'
import { NotFound } from './NotFound'
import { CheckList } from '../components/CheckList'

function Detail({ id }) {
  const service = services[id]
  // The raw catalog title and description match what the server renders for this URL.
  usePageMeta(service.title, service.description)
  return <>
    <PageHero cover={serviceImages[id]} title={sentence(service.title)} lede={service.description} before={<>
      <div><Link className="ph-back" to="/services"><Icon name="chevronLeft" size={18} />All services</Link></div>
      <span className="ph-icon-tile"><Icon name={serviceIcons[id]} /></span>
    </>}>
      <Button to={bookLink(id)} size="lg">{DISCOVERY}</Button>
    </PageHero>
    <div className="ph-section"><div className="ph-container ph-detail">
      <div className="ph-stack-lg">
        <section className="ph-stack"><h2>How we help</h2>
          <CheckList items={service.support} /></section>
        <section className="ph-stack"><h2>Who this service is for</h2><p className="ph-lede">{service.audience}</p></section>
        {(service.subtypes || service.note) && <div className="ph-stack-lg">
          {service.subtypes && <Accordion items={Object.entries(service.subtypes).map(([subtype, value]) => ({
            title: sentence(value.title),
            content: <><p>{value.description}</p><Button to={bookLink(id, subtype)}>{DISCOVERY}</Button></>,
          }))} />}
          {service.note && <p className="ph-muted">{service.note}</p>}
        </div>}
      </div>
      <aside aria-label="Book a call">
        <Card title="Start with a private conversation" level="h2">
          <p>Every situation is different. Schedule a complimentary 15-minute discovery call to discuss your concerns, learn about our non-legal readiness services, and explore the next appropriate step.</p>
          <Button to={bookLink(id)}>{DISCOVERY}</Button>
        </Card>
      </aside>
    </div></div>
  </>
}

export function ServiceDetail() {
  const { key } = useParams()
  return hasService(key) ? <Detail key={key} id={key} /> : <NotFound />
}
