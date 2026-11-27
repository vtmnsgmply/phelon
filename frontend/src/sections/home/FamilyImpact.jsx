import { Link } from 'react-router-dom'
import { ArchFrame } from '../../components/ArchFrame'
import { Picture } from '../../components/Picture'
import { familyPoints } from '../../content/home'

export function FamilyImpact() {
  return <section id="family" className="ph-section" data-bg="blush" aria-labelledby="family-title">
    <div className="ph-container ph-split ph-split--center">
      <ArchFrame outline={false}><Picture name="family" alt="A family planning together at a kitchen table" sizes="(min-width: 56em) 26rem, 90vw" /></ArchFrame>
      <div className="ph-stack-lg">
        <h2 id="family-title">The family gets hit too</h2>
        <p className="ph-lede">When one person faces a legal crisis, the entire household can feel the impact through relationships, routines, and finances.</p>
        <dl className="ph-points">{familyPoints.map(([term, detail]) => <div key={term}><dt>{term}</dt><dd>{detail}</dd></div>)}</dl>
        <div><Link className="ph-link" to="/services/family-household-stability">Explore family support</Link></div>
      </div>
    </div>
  </section>
}
