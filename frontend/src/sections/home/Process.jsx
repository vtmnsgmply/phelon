import { Link } from 'react-router-dom'
import { Section } from '../../components/Section'
import { processSteps } from '../../content/home'

export function Process() {
  return <Section id="how-it-works" bg="tint" title="How the process works">
    <div className="ph-process-wrap">
      <div className="ph-process-line" aria-hidden="true" />
      <ol className="ph-process">{processSteps.map(([title, copy]) => <li key={title}><h3>{title}</h3><p>{copy}</p></li>)}</ol>
    </div>
    <p><Link className="ph-link" to="/start-here">See what to expect</Link></p>
  </Section>
}
