import { bookLink, DISCOVERY } from '../../catalog'
import { ArchFrame } from '../../components/ArchFrame'
import { Button } from '../../components/Button'
import { Picture } from '../../components/Picture'
import { hero } from '../../content/home'

export function Hero() {
  return <div id="hero" className="ph-hero ph-glow" data-surface="dark">
    <div className="ph-container ph-hero-grid">
      <div>
        <h1 className="ph-hero-title">{hero.title}</h1>
        <p className="ph-lede ph-hero-copy">{hero.lede}</p>
        <div className="ph-actions ph-hero-actions">
          <Button to={bookLink()} size="lg">{DISCOVERY}</Button>
          <Button to="/#how-it-works" variant="secondary" size="lg">How it works</Button>
        </div>
        <ul className="ph-trust">{hero.trust.map(item => <li key={item}>{item}</li>)}</ul>
      </div>
      <div className="ph-hero-visual">
        <ArchFrame><Picture name="hero-portrait" alt="Portrait in front of the PHELONPRENEUR Network logo" sizes="(min-width: 56em) 24rem, 72vw" priority /></ArchFrame>
        <p className="ph-hero-chip ph-flush"><strong>Next step: a private 15-minute conversation</strong><span>Structure over chaos. Clarity over confusion.</span></p>
      </div>
    </div>
  </div>
}
