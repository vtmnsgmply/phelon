import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { PageHero } from '../components/PageHero'
import { Picture } from '../components/Picture'
import { Section } from '../components/Section'
import { isNot, isPage, paths, sections } from '../content/start-here'
import { usePageMeta } from '../hooks/usePageMeta'
import { CheckList } from '../components/CheckList'

function Block({ data }) {
  return <div className="ph-stack">
    <h2>{data.title}</h2>
    {data.before.map(text => <p key={text}>{text}</p>)}
    <CheckList items={data.list} />
    {data.after.map(text => <p key={text}><strong>{text}</strong></p>)}
  </div>
}

export function StartHere() {
  const lede = 'When serious charges surface, the instinct is to react. This page is designed to help you slow down, orient yourself, and take the right first step.'
  usePageMeta('Start here', lede)
  return <>
    <PageHero cover="doorway" title="Start here. Breathe first." lede={lede}><ul className="ph-trust"><li>Confidential</li><li>Non-legal</li><li>Nationwide</li></ul></PageHero>
    <Section><div className="ph-split"><Block data={sections.told} /><Block data={sections.structure} /></div></Section>
    <Section bg="blush"><div className="ph-split ph-split--center">
      <div className="ph-photo"><Picture name="start-family" alt="A person comforting their partner, who is covering their face" sizes="(min-width: 56em) 36rem, 90vw" /></div>
      <Block data={sections.family} />
    </div></Section>
    <Section title="What this page is — and is not">
      <div className="ph-grid ph-grid--2">
        <Card title="What this page is"><CheckList items={isPage} /></Card>
        <Card title="What this page is not"><CheckList items={isNot} icon="close" /></Card>
      </div>
    </Section>
    <Section bg="tint" title="Your first responsible step" lede="You don’t need to solve everything today.">
      <ul className="ph-grid ph-grid--3">{paths.map(([title, copy, to, label], index) => <Card as="li" key={title} title={title}>
        <p>{copy}</p><Button to={to} variant={index ? 'secondary' : 'primary'}>{label}</Button>
      </Card>)}</ul>
      <div className="ph-stack ph-subheading"><p>You are not expected to have answers yet.</p><p>You are expected to protect yourself and your family — calmly.</p></div>
    </Section>
  </>
}
