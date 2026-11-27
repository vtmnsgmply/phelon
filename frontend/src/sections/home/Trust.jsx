import { Card } from '../../components/Card'
import { Section } from '../../components/Section'
import { trustTiles } from '../../content/home'

export function Trust() {
  return <Section id="trust" surface="dark" cover="team" title="Built on trust, discretion, and real stability"
    lede="The PHELONPRENEUR Network was founded to provide calm structure for the real-life impact of serious charges. Our focus is practical, confidential support so individuals and families can make informed plans for everyday life.">
    <h3 className="ph-trust-title">Confidential by design</h3>
    <ul className="ph-grid ph-grid--pairs">
      {trustTiles.map(([icon, text]) => <Card as="li" key={text} icon={icon}><p>{text}</p></Card>)}
    </ul>
  </Section>
}
