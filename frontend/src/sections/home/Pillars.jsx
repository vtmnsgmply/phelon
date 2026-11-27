import { Link } from 'react-router-dom'
import { sentence, services } from '../../catalog'
import { Card } from '../../components/Card'
import { Reveal } from '../../components/Reveal'
import { Section } from '../../components/Section'
import { ServiceGrid } from '../../components/ServiceGrid'
import { pillars } from '../../content/home'

export function Pillars() {
  return <Section id="services" bg="tint" title="Three pillars of readiness">
    <Reveal as="ul" stagger className="ph-bento">
      {pillars.map(([title, copy, key, icon]) => <Card as="li" key={key} icon={icon} title={title} spotlight className="ph-service-card ph-span-4">
        <p>{copy}</p>
        <Link className="ph-link" to={'/services/' + key}>Explore {sentence(services[key].title)}</Link>
      </Card>)}
    </Reveal>
    <h2 className="ph-subheading">Explore all nine services</h2>
    <ServiceGrid reveal />
  </Section>
}
