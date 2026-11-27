import { bookLink, DISCOVERY } from '../catalog'
import { Button } from './Button'
import { Section } from './Section'

export function ClosingCta({ id, title = 'Start with a private conversation', text = 'Not sure where to begin? Tell us what needs to stay steady, and we’ll take it from there.', to = bookLink() }) {
  return <Section id={id} bg="band" cover="corridor" className="ph-closing" title={title}>
    <svg className="ph-watermark" viewBox="0 0 400 500" preserveAspectRatio="xMidYMax meet" aria-hidden="true" focusable="false">
      <path d="M0 500V200a200 200 0 0 1 400 0v300z" />
    </svg>
    <div className="ph-stack-lg">
      <p className="ph-lede ph-center">{text}</p>
      <div className="ph-actions"><Button to={to} size="lg">{DISCOVERY}</Button><Button to="/contact" variant="secondary" size="lg">Contact us</Button></div>
    </div>
  </Section>
}
