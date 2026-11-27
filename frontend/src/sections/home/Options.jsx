import { Boundary } from '../../components/Boundary'
import { Card } from '../../components/Card'
import { Section } from '../../components/Section'
import { programs } from '../../content/home'
import { CheckList } from '../../components/CheckList'

export function Options({ boundary = true }) {
  return <Section id="programs" title="Ways we can help you" lede="Start with a discovery call. Any service proposal is discussed privately afterward.">
    <ul className="ph-grid ph-grid--3">
      {programs.map(([label, title, copy, features]) => <Card as="li" key={title}>
        <p className="ph-label ph-flush">{label}</p><h3>{title}</h3><p>{copy}</p>
        <CheckList items={features} />
      </Card>)}
    </ul>
    {boundary && <Boundary />}
  </Section>
}
