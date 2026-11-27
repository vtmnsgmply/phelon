import { useState } from 'react'
import { bookLink, DISCOVERY } from '../catalog'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { PageHero } from '../components/PageHero'
import { Section } from '../components/Section'
import { email, expect, reasons } from '../content/contact'
import { usePageMeta } from '../hooks/usePageMeta'

export function Contact() {
  const lede = 'Your journey toward transformation begins with a conversation. Whether you’re ready to take the next step or simply exploring your options, we’re here to listen with care, discretion, and respect.'
  usePageMeta('Contact', lede)
  const [status, setStatus] = useState('')
  async function copy() {
    try { await navigator.clipboard.writeText(email); setStatus('Email address copied.') }
    catch { setStatus('Copying isn’t available. Select the address and copy it.') }
  }
  return <>
    <PageHero cover="video-call" title="Let’s connect" lede={lede} />
    <Section title="How to reach us" lede="We are currently available via email for inquiries related to readiness support, general questions, or next steps. All communications are handled with the utmost confidentiality and professionalism.">
      <Card icon="mail" title="Email" className="ph-narrow">
        <a className="ph-link" href={'mailto:' + email}>{email}</a>
        <p>Response times: typically within one business day.</p>
        <div className="ph-actions"><Button variant="secondary" icon="copy" onClick={copy}>Copy email</Button><span role="status" className="ph-hint">{status}</span></div>
      </Card>
    </Section>
    <Section bg="tint" title="What to expect">
      <ul className="ph-grid ph-grid--3">{expect.map(([icon, title, copy]) => <Card as="li" key={title} icon={icon} title={title}><p>{copy}</p></Card>)}</ul>
    </Section>
    <Section title="Why reach out?">
      <ul className="ph-grid ph-grid--4">{reasons.map(([title, copy]) => <Card as="li" key={title} title={title}><p>{copy}</p></Card>)}</ul>
      <div className="ph-split ph-subheading">
        <div className="ph-stack">
          <p className="ph-lede">We approach all communication with care, discretion, and respect. Every journey is unique, and we honor that with individualized attention. If you’re unsure where to begin, a <strong>free discovery call</strong> may be the most appropriate next step.</p>
          <div><Button to={bookLink()} size="lg">{DISCOVERY}</Button></div>
          <p className="ph-muted">No pressure. No judgment. Just clarity and support.</p>
        </div>
        <Card title="Connect with us"><p>We’re building our community presence. Official social media channels will be announced here as they become available.</p><p className="ph-muted">Social media links coming soon. Stay tuned.</p></Card>
      </div>
    </Section>
  </>
}
