import { Accordion } from '../components/Accordion'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Notice } from '../components/Notice'
import { Picture } from '../components/Picture'
import { Section } from '../components/Section'
import { attendees, facts, faq, formats, learn, masterclassDate, offers, outputs, ratings, scopes, seat } from '../content/webinar'
import { usePageMeta } from '../hooks/usePageMeta'
import { CheckList } from '../components/CheckList'

export function Webinar() {
  usePageMeta('Webinar', 'What happens to everything else? A live readiness masterclass from the PHELONPRENEUR Network.')
  const register = <Button to={seat[0]} size="lg">{seat[1]}</Button>
  return <>
    <div className="ph-pagehero ph-glow" data-surface="dark"><div className="ph-container ph-split ph-split--wide-start ph-split--center">
      <div className="ph-stack-lg">
        <p className="ph-label ph-flush">PHELONPRENEUR Network presents Structure Before Panic™</p>
        <h1>When life changes overnight...</h1>
        <p className="ph-lede ph-muted">What happens to everything else? This free live readiness masterclass helps you identify the life-readiness areas that may need attention and begin creating a clearer plan for what comes next.</p>
        <p><strong>{masterclassDate}</strong></p>
        <p>The attorney handles the legal case. PHELONPRENEUR helps stabilize the life around it.</p>
        <div className="ph-actions">{register}<Button to="/webinar#learn" variant="secondary" size="lg">What you will learn</Button></div>
        <dl className="ph-stats">{facts.map(([figure, label]) => <div key={label}><dt>{figure}</dt><dd>{label}</dd></div>)}</dl>
      </div>
      <div className="ph-photo ph-product"><Picture name="webinar-host" alt="A webinar host standing in an office" sizes="(min-width: 56em) 24rem, 90vw" priority /></div>
    </div></div>

    <Section id="registration" bg="tint" title="Save your seat">
      <div className="ph-stack ph-narrow">
        <Notice><p>Online registration is currently unavailable. Contact our team for registration details. Please don’t send case details or court documents.</p></Notice>
        <div><Button to="/contact">Contact the team</Button></div>
        <ul className="ph-tags">{formats.map(item => <li key={item}>{item}</li>)}</ul>
      </div>
    </Section>

    <Section title="You have questions nobody seems to be answering" lede="How do I prepare my family? What happens to the household or business? What documents need to be organized? Who needs access to important information? Where do we even begin?">
      <h3 className="ph-flush">More than just the case</h3>
      <ul className="ph-grid ph-grid--2 ph-subheading">{scopes.map(([label, title, copy]) => <Card as="li" key={title}><p className="ph-label ph-flush">{label}</p><h4>{title}</h4><p>{copy}</p></Card>)}</ul>
    </Section>

    <Section id="learn" bg="tint" title="The readiness areas that bring order to the life around the case">
      <ul className="ph-grid ph-grid--3">{learn.map(([title, copy]) => <Card as="li" key={title} title={title}><p>{copy}</p></Card>)}</ul>
    </Section>

    <Section cover="video-call" title="Complete your Readiness Snapshot™" lede="You will rate major readiness areas as green, yellow, or red, then identify your top three readiness gaps, top three priorities, and first immediate action.">
      <div className="ph-split">
        <CheckList items={ratings} />
        <div className="ph-stack"><h3>Why PHELONPRENEUR exists</h3>
          <p>Serious legal disruption affects more than one person. It reaches families, children, households, businesses, relationships, responsibilities, and plans. PHELONPRENEUR exists because people deserve support preparing for those realities too.</p>
          <p><strong>Purpose. Plan. Progress. Legacy.</strong></p><p className="ph-muted">This is private readiness education for people who need structure, not pressure.</p></div>
      </div>
    </Section>

    <Section title="What you will identify during the masterclass">
      <ul className="ph-grid ph-grid--3">{outputs.map(([title, copy]) => <Card as="li" key={title} title={title}><p>{copy}</p></Card>)}</ul>
      <h3 className="ph-subheading">This masterclass is built for the people carrying real-life pressure</h3>
      <ul className="ph-tags">{attendees.map(item => <li key={item}>{item}</li>)}</ul>
    </Section>

    <Section id="offers" bg="tint" title="Choose the preparation path that fits your current season" lede="The free masterclass is the starting point. These next-step options move from self-guided preparation into structured implementation for people who need more support.">
      <ul className="ph-grid ph-grid--4">{offers.map(offer => <Card as="li" key={offer.title}>
        <p className="ph-label ph-flush">{offer.label}</p><p className="ph-price ph-flush">{offer.price}</p><h3>{offer.title}</h3><p>{offer.copy}</p>
        <CheckList items={offer.points} />
        <Button to={offer.to} variant="secondary">{offer.action}</Button>
      </Card>)}</ul>
      <p className="ph-muted ph-subheading">The $397 private readiness consultation is a separate path and should not be confused with the $497 group workshop. PHELONPRENEUR provides education and readiness support only.</p>
    </Section>

    <Section title="Frequently asked questions">
      <div className="ph-stack-lg ph-narrow">
        <Accordion items={faq.map(([title, answer]) => ({ title, content: <p>{answer}</p> }))} />
        <div><Button to="/webinar/resources" variant="secondary">Access a purchased resource</Button></div>
      </div>
    </Section>

    <Section bg="band" className="ph-closing" title="Life may have changed overnight. You do not have to face what comes next without a plan.">
      <div className="ph-stack-lg"><p className="ph-lede ph-center">{masterclassDate}</p><div className="ph-actions">{register}</div></div>
    </Section>
  </>
}
