import { lazy, Suspense } from 'react'
import { Route, Routes, useLocation } from 'react-router-dom'
import { Home } from '../pages/Home'

const page = (load, name) => lazy(() => load().then(module => ({ default: module[name] })))
const Services = page(() => import('../pages/Services'), 'Services')
const ServiceDetail = page(() => import('../pages/ServiceDetail'), 'ServiceDetail')
const Book = page(() => import('../pages/Book'), 'Book')
const Intake = page(() => import('../Intake'), 'Intake')
const About = page(() => import('../pages/About'), 'About')
const StartHere = page(() => import('../pages/StartHere'), 'StartHere')
const Contact = page(() => import('../pages/Contact'), 'Contact')
const Webinar = page(() => import('../pages/Webinar'), 'Webinar')
const Checkout = page(() => import('../pages/Checkout'), 'Checkout')
const Library = page(() => import('../pages/Library'), 'Library')
const Legal = page(() => import('../pages/Legal'), 'Legal')
const ThankYou = page(() => import('../pages/ThankYou'), 'ThankYou')
const NotFound = page(() => import('../pages/NotFound'), 'NotFound')
// Development only: the condition is false in production builds, so the page is never bundled.
const StyleGuide = import.meta.env.DEV ? page(() => import('../pages/StyleGuide'), 'StyleGuide') : null

export function AppRoutes() {
  const { pathname } = useLocation()
  return <Suspense fallback={<div className="ph-section ph-container" role="status">Loading…</div>}>
    <Routes>
      <Route path="/" element={<Home />} /><Route path="/home" element={<Home />} />
      <Route path="/services" element={<Services />} /><Route path="/services/:key" element={<ServiceDetail />} />
      <Route path="/book" element={<Book />} /><Route path="/intake" element={<Intake />} />
      <Route path="/about" element={<About />} /><Route path="/start-here" element={<StartHere />} />
      <Route path="/contact" element={<Contact />} /><Route path="/webinar" element={<Webinar />} />
      <Route path="/webinar/checkout/:offer" element={<Checkout />} />
      <Route path="/webinar/resources" element={<Library />} />
      {['privacy-policy', 'terms-of-service', 'cookie-policy', 'disclaimer'].map(slug =>
        <Route key={slug} path={'/' + slug} element={<Legal key={pathname} slug={slug} />} />)}
      {['thank-you', 'thanks'].map(slug => <Route key={slug} path={'/' + slug} element={<ThankYou slug={slug} />} />)}
      {StyleGuide && <Route path="/__styleguide" element={<StyleGuide />} />}
      <Route path="*" element={<NotFound />} />
    </Routes>
  </Suspense>
}
