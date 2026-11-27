import { toDay } from './dates'

export const WEEKDAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

// Weeks of "YYYY-MM-DD" strings for one month; null fills the days outside it.
export function monthWeeks(year, month) {
  const lead = new Date(Date.UTC(year, month, 1)).getUTCDay()
  const length = new Date(Date.UTC(year, month + 1, 0)).getUTCDate()
  const cells = [...Array(lead).fill(null), ...Array.from({ length }, (_, i) => toDay(new Date(Date.UTC(year, month, i + 1))))]
  while (cells.length % 7) cells.push(null)
  return Array.from({ length: cells.length / 7 }, (_, i) => cells.slice(i * 7, i * 7 + 7))
}

export const monthTitle = (year, month) =>
  new Intl.DateTimeFormat('en-US', { timeZone: 'UTC', month: 'long', year: 'numeric' }).format(new Date(Date.UTC(year, month, 1)))
