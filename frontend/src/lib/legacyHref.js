import { bookLink } from '../catalog'

const routes = {
  index: '/', about: '/about', starthere: '/start-here', 'start-here': '/start-here', services: '/services',
  contact: '/contact', book: '/book', 'privacy-policy': '/privacy-policy', 'cookie-policy': '/cookie-policy',
  'terms-of-service': '/terms-of-service', disclaimer: '/disclaimer', thankyou: '/thank-you', thanks: '/thanks',
}

// Maps links written for the old PHP site onto the current routes.
export function legacyHref(value = '') {
  if (value.startsWith('#') || /^(https?:|mailto:|tel:)/i.test(value)) return value
  const match = value.match(/^\/?([^?#]*)(.*)$/)
  if (!match) return '#'
  const path = match[1].replace(/\.php$/i, '').toLowerCase()
  if (path === 'book') return bookLink()
  return (routes[path] ?? '/' + path) + match[2]
}
