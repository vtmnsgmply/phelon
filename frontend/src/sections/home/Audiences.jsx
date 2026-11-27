import { Link } from 'react-router-dom'
import { sentence, services } from '../../catalog'
import { Card } from '../../components/Card'
import { Section } from '../../components/Section'
import { audiences } from '../../content/home'

export function Audiences() {
  return <Section id="who" title="Who we support" lede="When life becomes unstable, people need structure, clarity, and a calm plan.">
    <ul className="ph-rail ph-audiences">
      {audiences.map(([title, copy, key]) => <Card as="li" key={title} title={title}>
        <p>{copy}</p>
        <Link className="ph-link" to={'/services/' + key}>Explore {sentence(services[key].title)}</Link>
      </Card>)}
    </ul>
  </Section>
}
