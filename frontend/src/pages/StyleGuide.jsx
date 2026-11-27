// Development only (see app/routes.jsx). Not part of the production build.
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Field } from '../components/Field'
import { Notice } from '../components/Notice'
import { Section } from '../components/Section'
import { usePageMeta } from '../hooks/usePageMeta'

const colours = ['plum-950', 'plum-900', 'plum-800', 'plum-600', 'burgundy-700', 'burgundy-600', 'rose-500', 'rose-300', 'gold-200', 'gold-300', 'gold-400', 'gold-500', 'gold-700', 'cream-50', 'cream-100', 'cream-200', 'cream-300', 'ink', 'muted', 'line', 'line-strong', 'danger', 'success']

function Samples() {
  return <div className="ph-stack-lg">
    <div className="ph-actions"><Button>Primary</Button><Button variant="secondary">Secondary</Button><Button variant="text">Text</Button><Button loading>Loading</Button><Button disabled>Disabled</Button><Button size="lg" icon="mail">Large</Button></div>
    <ul className="ph-grid ph-grid--3"><Card as="li" icon="compass" title="Card" spotlight><p>Spotlight card with an icon.</p></Card><Card as="li" title="Plain card"><p>No icon.</p></Card></ul>
  </div>
}

export function StyleGuide() {
  usePageMeta('Style guide')
  return <>
    <Section title="Style guide" level="h1" lede="Tokens and components.">
      <ul className="ph-grid ph-grid--4">{colours.map(name => <li key={name} className="ph-card"><span style={{ background: `var(--${name})`, blockSize: '3rem', inlineSize: '100%', borderRadius: 'var(--r-sm)', border: '1px solid var(--line)' }} /><code>--{name}</code></li>)}</ul>
    </Section>
    <Section title="Type" bg="tint">{[5, 4, 3, 2, 1, 0].map(step => <p key={step} style={{ fontSize: `var(--step-${step})`, fontFamily: step > 1 ? 'var(--font-display)' : undefined }}>Step {step}: calm, clear, prepared</p>)}</Section>
    <Section title="Components"><Samples /></Section>
    <Section title="On dark" surface="dark"><Samples /></Section>
    <Section title="Forms and notices"><div className="ph-form ph-narrow">
      <Field label="Field" hint="Hint text">{props => <input {...props} />}</Field>
      <Field label="With error" error="Enter an email address like name@example.com.">{props => <input {...props} />}</Field>
      {['info', 'success', 'warning', 'danger'].map(tone => <Notice key={tone} tone={tone}><p>{tone} notice</p></Notice>)}
    </div></Section>
  </>
}
