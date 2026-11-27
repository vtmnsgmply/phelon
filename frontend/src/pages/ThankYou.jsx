import pages from '../content/pages.json'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Icon } from '../components/Icon'
import { flags } from '../content/flags'
import { usePageMeta } from '../hooks/usePageMeta'

export function ThankYou({ slug }) {
  // OWNER: this copy promises confirmation emails. Check that something actually sends them.
  const page = pages[slug]
  usePageMeta(page.title.replace(/\.$/, ''))
  return <div className="ph-section ph-section--compact"><div className="ph-container ph-stack-lg ph-narrow">
    <div className="ph-card" role="status">
      <span className="ph-icon-tile"><Icon name="success" /></span>
      <p className="ph-label ph-flush">{page.label}</p>
      <h1>{page.title}</h1>
      {page.intro.map(text => <p key={text}>{text}</p>)}
      <Button to={page.action[0]} size="lg">{page.action[1]}</Button>
    </div>
    <section className="ph-stack">
      <h2>{page.stepsTitle}</h2>
      {page.stepsLede && <p>{page.stepsLede}</p>}
      <ul className="ph-checks">{page.steps.map(([title, text]) => <li key={title}><Icon name="check" size={18} /><span><strong>{title}</strong> {text}</span></li>)}</ul>
    </section>
    {page.notes.map(([title, text]) => <Card key={title} title={title} level="h2"><p>{text}</p></Card>)}
    {flags.showUnverifiedClaims && page.quote && <Card><blockquote className="ph-flush">{page.quote.text}</blockquote><p className="ph-muted">{page.quote.by}</p></Card>}
    <p className="ph-muted">{page.footnote}</p>
  </div></div>
}
