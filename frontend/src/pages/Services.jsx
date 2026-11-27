import { bookLink, DISCOVERY } from '../catalog'
import { Button } from '../components/Button'
import { ClosingCta } from '../components/ClosingCta'
import { PageHero } from '../components/PageHero'
import { Section } from '../components/Section'
import { ServiceGrid } from '../components/ServiceGrid'
import { usePageMeta } from '../hooks/usePageMeta'
import { Options } from '../sections/home/Options'

export function Services() {
  usePageMeta('Readiness services')
  return <>
    <PageHero cover="team" title="Structured readiness support" lede="Explore nine ways to organize responsibilities, protect continuity, and prepare for what comes next.">
      <Button to={bookLink()} size="lg">{DISCOVERY}</Button>
    </PageHero>
    <Options boundary={false} />
    <Section bg="tint" title="Our services"><ServiceGrid /></Section>
    <ClosingCta />
  </>
}
