import { ClosingCta } from '../components/ClosingCta'
import { usePageMeta } from '../hooks/usePageMeta'
import { Audiences } from '../sections/home/Audiences'
import { FamilyImpact } from '../sections/home/FamilyImpact'
import { Hero } from '../sections/home/Hero'
import { Options } from '../sections/home/Options'
import { Pillars } from '../sections/home/Pillars'
import { Process } from '../sections/home/Process'
import { Protect } from '../sections/home/Protect'
import { ServiceStrip } from '../sections/home/ServiceStrip'
import { Trust } from '../sections/home/Trust'
import { WhatWeDo } from '../sections/home/WhatWeDo'

export function Home() {
  usePageMeta('')
  return <>
    <Hero />
    <ServiceStrip />
    <WhatWeDo />
    <Pillars />
    <Audiences />
    <FamilyImpact />
    <Protect />
    <Process />
    <Options />
    <Trust />
    <ClosingCta id="contact" />
  </>
}
