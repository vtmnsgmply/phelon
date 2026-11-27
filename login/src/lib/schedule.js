import { FEDERAL } from './holidays'
import { BUSINESS_ZONE } from './timezone'

export const DAYS = [['mon', 'Monday'], ['tue', 'Tuesday'], ['wed', 'Wednesday'], ['thu', 'Thursday'], ['fri', 'Friday'], ['sat', 'Saturday'], ['sun', 'Sunday']]
// The server accepts quarter hours only.
export const TIMES = Array.from({ length: 96 }, (_, i) => `${String(Math.floor(i / 4)).padStart(2, '0')}:${String((i % 4) * 15).padStart(2, '0')}`)
export const timeLabel = value => { const [h, m] = value.split(':').map(Number); return `${h % 12 || 12}:${String(m).padStart(2, '0')} ${h < 12 ? 'AM' : 'PM'}` }
const minutes = value => { const [h, m] = value.split(':').map(Number); return h * 60 + m }

export const defaultSchedule = () => ({
  timezone: BUSINESS_ZONE, slotMinutes: 15, noticeHours: 1, windowDays: 45,
  days: Object.fromEntries(DAYS.map(([key]) => [key, { open: !['sat', 'sun'].includes(key), from: '09:00', to: '17:00', breakFrom: '', breakTo: '' }])),
})
export const defaultHolidays = () => ({ federal: FEDERAL.map(([id]) => id), observances: [], custom: [] })

export function dayError(day) {
  if (!day.open) return ''
  if (day.to <= day.from) return 'Closing time must be later than opening time.'
  if (!day.breakFrom && !day.breakTo) return ''
  if (!day.breakFrom || !day.breakTo) return 'Choose both the start and the end of the break.'
  if (day.breakTo <= day.breakFrom) return 'The break must end after it starts.'
  if (day.breakFrom <= day.from || day.breakTo >= day.to) return 'The break must fall inside opening hours.'
  return ''
}

export function scheduleError(schedule) {
  if (!DAYS.some(([key]) => schedule.days[key].open)) return 'Open at least one day of the week.'
  if (DAYS.some(([key]) => dayError(schedule.days[key]))) return 'Fix the highlighted days before saving.'
  if (!Number.isInteger(schedule.windowDays) || schedule.windowDays < 1 || schedule.windowDays > 365) return 'The booking window must be between 1 and 365 days.'
  return ''
}

export function slotCount(day, length) {
  if (!day.open || dayError(day)) return 0
  const span = (from, to) => Math.floor((minutes(to) - minutes(from)) / length)
  return day.breakFrom ? span(day.from, day.breakFrom) + span(day.breakTo, day.to) : span(day.from, day.to)
}
