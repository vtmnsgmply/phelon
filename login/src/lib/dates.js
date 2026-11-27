import { BUSINESS_ZONE } from './timezone'

const inZone = (options, date) => new Intl.DateTimeFormat('en-US', { timeZone: BUSINESS_ZONE, ...options }).format(date)

// Moments that carry an offset are shown in the business time zone. Database timestamps without one
// are shown exactly as recorded, because their zone isn't known.
export function formatDateTime(value) {
  const text = String(value).replace(' ', 'T')
  const zoned = /(Z|[+-]\d{2}:?\d{2})$/.test(text)
  const date = new Date(zoned ? text : text + 'Z')
  if (Number.isNaN(date.getTime())) return '—'
  return new Intl.DateTimeFormat('en-US', { timeZone: zoned ? BUSINESS_ZONE : 'UTC', month: 'short', day: 'numeric', year: 'numeric',
    hour: 'numeric', minute: '2-digit', ...(zoned && { timeZoneName: 'short' }) }).format(date)
}
export const formatTime = value => inZone({ hour: 'numeric', minute: '2-digit' }, new Date(value))

// Plain calendar days ("2026-12-25") are handled in UTC so they never shift.
export const toDay = date => date.toISOString().slice(0, 10)
export const parseDay = value => new Date(value + 'T00:00:00Z')
export const formatDay = (value, options = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' }) =>
  new Intl.DateTimeFormat('en-US', { timeZone: 'UTC', ...options }).format(parseDay(value))

// The calendar day of a moment in the business time zone.
export function businessDay(instant) {
  const parts = Object.fromEntries(new Intl.DateTimeFormat('en-US', { timeZone: BUSINESS_ZONE, year: 'numeric', month: '2-digit', day: '2-digit' })
    .formatToParts(new Date(instant)).map(item => [item.type, item.value]))
  return `${parts.year}-${parts.month}-${parts.day}`
}
export const today = () => businessDay(Date.now())

// The moment when the business clock shows this hour on this day (used for preview sample data).
export function zonedInstant(day, hour) {
  const guess = Date.parse(`${day}T${String(hour).padStart(2, '0')}:00:00Z`)
  const clock = Object.fromEntries(new Intl.DateTimeFormat('en-US', { timeZone: BUSINESS_ZONE, hourCycle: 'h23', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' })
    .formatToParts(guess).map(item => [item.type, Number(item.value)]))
  const offset = Date.UTC(clock.year, clock.month - 1, clock.day, clock.hour, clock.minute) - guess
  return new Date(guess - offset).toISOString()
}
