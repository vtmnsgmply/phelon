import { bookLink, DISCOVERY } from '../catalog'
import { ArchFrame } from '../components/ArchFrame'
import { Boundary } from '../components/Boundary'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { ClosingCta } from '../components/ClosingCta'
import { Picture } from '../components/Picture'
import { Reveal } from '../components/Reveal'
import { Section } from '../components/Section'
import { gap, intro, method, quotes, serve, stats, support, values } from '../content/about'
import { flags } from '../content/flags'
import { usePageMeta } from '../hooks/usePageMeta'
import { CheckList } from '../components/CheckList'

export function About() {
  usePageMeta('About', intro.lede)
  return <>
    <div className="ph-pagehero ph-glow" data-surface="dark"><div className="ph-container ph-split ph-split--wide-start ph-split--center">
      <div className="ph-stack-lg">
        <h1>{intro.title}</h1>
        <p className="ph-lede ph-muted">{intro.lede}</p>
        <ul className="ph-tags">{intro.badges.map(item => <li key={item}>{item}</li>)}</ul>
        <div className="ph-actions"><Button to={bookLink()} size="lg">{DISCOVERY}</Button><Button to="/about#how-we-work" variant="secondary" size="lg">Learn our approach</Button></div>
        <ul className="ph-trust">{intro.tags.map(item => <li key={item}>{item}</li>)}</ul>
        {flags.showUnverifiedClaims && <dl className="ph-stats">{stats.map(([figure, label]) => <div key={label}><dt>{figure}</dt><dd>{label}</dd></div>)}</dl>}
      </div>
      <ArchFrame><Picture name="founders" alt="Two members of the PHELONPRENEUR Network standing together" sizes="(min-width: 56em) 26rem, 90vw" priority /></ArchFrame>
    </div></div>

    <Section title="The gap no one talks about" lede="When serious charges arise, legal representation becomes the focus — as it should. But the human impact often goes entirely unaddressed.">
      <ul className="ph-grid ph-grid--4">{gap.map(([title, copy]) => <Card as="li" key={title} title={title}><p>{copy}</p></Card>)}</ul>
      <div className="ph-stack ph-subheading">
        <p className="ph-lede">Most people are not prepared for this reality — and no one teaches them how to slow down and think clearly through it. <strong>That gap is where we work.</strong></p>
        <div><Button to="/about#how-we-work" variant="text">See how we fill it</Button></div>
      </div>
    </Section>

    <Section bg="tint" title="Our philosophy is simple">
      <div className="ph-split ph-split--center">
        <div className="ph-stack-lg">
          <blockquote className="ph-quote ph-flush">Panic creates problems. Structure creates options.</blockquote>
          <p>In moments of pressure, people do not need noise. They need guidance, clarity, and a calm process that helps them understand what comes next.</p>
          <p>We do not rush. We do not push. We do not promise outcomes. We focus on readiness.</p>
        </div>
        {/* OWNER: replaces guide.jpg, which read "Legal Guidance". Approve this photo. */}
        <div className="ph-photo"><Picture name="about-support" alt="A person standing quietly beside a window with a packed bag nearby" sizes="(min-width: 56em) 36rem, 90vw" /></div>
      </div>
      <h3 className="ph-subheading">Structure when everything feels uncertain</h3>
      <ul className="ph-grid ph-grid--4">{support.map(([title, copy]) => <Card as="li" key={title}><h4>{title}</h4><p>{copy}</p></Card>)}</ul>
    </Section>

    <Section id="how-we-work" title="How we work" lede="A structured, compassionate approach designed to restore clarity, stability, and control.">
      <Reveal as="ul" stagger className="ph-grid ph-grid--2">
        {method.map(([stage, title, copy, points]) => <Card as="li" key={title}>
          <p className="ph-label ph-flush">{stage}</p><h3>{title}</h3><p>{copy}</p>
          {points.length > 0 && <CheckList items={points} />}
        </Card>)}
      </Reveal>
      <div className="ph-stack ph-subheading">
        <h3>Decisions made from clarity, not fear</h3>
        <p>We help individuals and families organize what matters most — so every decision is informed, deliberate, and aligned with protecting your future.</p>
        <div><Button to={bookLink()}>{DISCOVERY}</Button></div>
      </div>
    </Section>

    <Section cover="team" title="A note on leadership">
      <div className="ph-stack-lg">
        <div className="ph-stack">
          <p className="ph-label ph-flush">Founder &amp; principal</p><h3>Craig Buchan</h3>
          <p>The PHELONPRENEUR Network was created by individuals who understand — <strong>personally and professionally</strong> — the consequences that serious charges can have on lives, families, and futures.</p>
          <p>Leadership here is informed by <strong>lived experience</strong>, strategic thinking, and an unwavering commitment to discretion. Every decision is made with the full weight of what is at stake.</p>
        </div>
        <ul className="ph-grid ph-grid--3">{values.map(([title, copy]) => <Card as="li" key={title} title={title}><p>{copy}</p></Card>)}</ul>
      </div>
    </Section>

    <Section title="Who we serve" lede="We work with individuals and families who value preparation, discretion, and strategic clarity.">
      <ul className="ph-grid ph-grid--4">{serve.map(([title, copy]) => <Card as="li" key={title} title={title}><p>{copy}</p></Card>)}</ul>
      <p className="ph-lede ph-subheading">If clarity matters to you, you are in the right place.</p>
      <Boundary level="h2" />
    </Section>

    {flags.showUnverifiedClaims && <Section bg="tint" title="What our clients say">
      <ul className="ph-grid ph-grid--2">{quotes.map(([quote, by]) => <Card as="li" key={by}><blockquote className="ph-flush">{quote}</blockquote><p className="ph-muted">{by}</p></Card>)}</ul>
    </Section>}

    <ClosingCta title="This is not about fixing everything today" text="It’s about protecting what matters while you move forward with clarity, structure, and confidence." />
  </>
}
