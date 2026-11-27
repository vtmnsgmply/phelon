import { Link } from 'react-router-dom'
import { sentence, services } from '../../catalog'
import { Card } from '../../components/Card'
import { Section } from '../../components/Section'
import { protections } from '../../content/home'

export function Protect() {
  return <Section id="protect" title="What we help protect" lede="The goal is stability in the present while protecting long-term direction.">
    <ul className="ph-grid ph-grid--pairs">
      {protections.map(([title, copy, key]) => <Card as="li" key={key} title={title}>
        <p>{copy}</p>
        <Link className="ph-link" to={'/services/' + key}>Explore {sentence(services[key].title)}</Link>
      </Card>)}
    </ul>
  </Section>
}
