import services from '../../backend/resources/catalog/services.json'

export { services }
export const DISCOVERY = 'Book a free discovery call'
export const DISCLAIMER = 'The PHELONPRENEUR Network provides confidential, non-legal readiness and life-stability support. We do not provide legal advice, legal representation, financial advice, tax advice, therapy, medical advice, or guarantees of legal, financial, professional, or other outcomes.'

export const hasService = key => Boolean(key) && Object.hasOwn(services, key)

// Catalog titles are Title Case and shared with the backend, so sentence case is applied on display only.
export const sentence = title => title.charAt(0) + title.slice(1).toLowerCase()

export const serviceIcons = {
  'private-readiness-life-planning': 'compass',
  'family-household-stability': 'home',
  'financial-stability': 'wallet',
  'business-career-continuity': 'briefcase',
  'reputation-digital-presence': 'globe',
  'federal-prison-readiness': 'shield',
  'productive-time-planning': 'clock',
  'release-reentry-preparation': 'sprout',
  '90-day-ongoing-support': 'calendarCheck',
}

// Textless photos only, one per service. Decorative: the title beside each one names the service.
export const serviceImages = {
  'private-readiness-life-planning': 'readiness',
  'family-household-stability': 'household',
  'financial-stability': 'finances',
  'business-career-continuity': 'business',
  'reputation-digital-presence': 'reputation',
  'federal-prison-readiness': 'about-support',
  'productive-time-planning': 'video-call',
  'release-reentry-preparation': 'doorway',
  '90-day-ongoing-support': 'family',
}

export function bookLink(key = '', subtype = '') {
  const query = new URLSearchParams()
  if (key) query.set('service', key)
  if (subtype) query.set('subservice', subtype)
  return '/book' + (query.size ? '?' + query.toString() : '')
}

export function serviceLabel(key, subtype) {
  if (!hasService(key)) return 'General / not sure yet'
  return sentence((subtype && services[key].subtypes?.[subtype]?.title) || services[key].title)
}
