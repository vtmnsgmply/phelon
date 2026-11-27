// One time zone for the whole back office, set by VITE_BOOKING_TIMEZONE in login/.env.
const FALLBACK = 'America/New_York'

function usable(zone) {
  // Only region names (Area/City) or UTC, which is what the server accepts.
  if (!zone || !(zone === 'UTC' || zone.includes('/'))) return false
  try { new Intl.DateTimeFormat('en-US', { timeZone: zone }); return true } catch { return false }
}

const configured = import.meta.env.VITE_BOOKING_TIMEZONE?.trim()
export const BUSINESS_ZONE = usable(configured) ? configured : FALLBACK
if (configured && configured !== BUSINESS_ZONE) console.warn(`VITE_BOOKING_TIMEZONE "${configured}" is not usable. Using ${FALLBACK}.`)

const part = (style, date, zone) => new Intl.DateTimeFormat('en-US', { timeZone: zone, timeZoneName: style })
  .formatToParts(date).find(item => item.type === 'timeZoneName').value

// For example "Eastern Standard Time (EST, UTC-05:00)", for the given moment.
export function zoneLabel(date = new Date(), zone = BUSINESS_ZONE) {
  const offset = part('longOffset', date, zone).replace('GMT', 'UTC')
  return `${part('long', date, zone)} (${part('short', date, zone).replace('GMT', 'UTC')}, ${offset === 'UTC' ? 'UTC+00:00' : offset})`
}
