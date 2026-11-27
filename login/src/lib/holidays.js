// US holiday dates, calculated rather than typed in, so every year is covered.
import { toDay } from './dates'

const utc = (year, month, day) => new Date(Date.UTC(year, month - 1, day))
const shift = (date, days) => new Date(date.getTime() + days * 86400000)
const nth = (year, month, weekday, n) => { const first = utc(year, month, 1); return shift(first, (weekday - first.getUTCDay() + 7) % 7 + (n - 1) * 7) }
const last = (year, month, weekday) => { const end = utc(year, month + 1, 0); return shift(end, -((end.getUTCDay() - weekday + 7) % 7)) }
// Federal rule for fixed dates: Saturday is observed the Friday before, Sunday the Monday after.
const observed = date => date.getUTCDay() === 6 ? shift(date, -1) : date.getUTCDay() === 0 ? shift(date, 1) : date

function easter(year) {
  const a = year % 19, b = Math.floor(year / 100), c = year % 100
  const h = (19 * a + b - Math.floor(b / 4) - Math.floor((b - Math.floor((b + 8) / 25) + 1) / 3) + 15) % 30
  const l = (32 + 2 * (b % 4) + 2 * Math.floor(c / 4) - h - (c % 4)) % 7
  const m = Math.floor((a + 11 * h + 22 * l) / 451)
  return utc(year, Math.floor((h + l - 7 * m + 114) / 31), ((h + l - 7 * m + 114) % 31) + 1)
}

const MONDAY = 1, THURSDAY = 4
export const FEDERAL = [
  ['new-year', 'New Year’s Day', year => utc(year, 1, 1), true],
  ['mlk', 'Birthday of Martin Luther King, Jr.', year => nth(year, 1, MONDAY, 3)],
  ['washington', 'Washington’s Birthday (Presidents Day)', year => nth(year, 2, MONDAY, 3)],
  ['memorial', 'Memorial Day', year => last(year, 5, MONDAY)],
  ['juneteenth', 'Juneteenth National Independence Day', year => utc(year, 6, 19), true],
  ['independence', 'Independence Day', year => utc(year, 7, 4), true],
  ['labor', 'Labor Day', year => nth(year, 9, MONDAY, 1)],
  ['columbus', 'Columbus Day', year => nth(year, 10, MONDAY, 2)],
  ['veterans', 'Veterans Day', year => utc(year, 11, 11), true],
  ['thanksgiving', 'Thanksgiving Day', year => nth(year, 11, THURSDAY, 4)],
  ['christmas', 'Christmas Day', year => utc(year, 12, 25), true],
]
export const OBSERVANCES = [
  ['good-friday', 'Good Friday', year => shift(easter(year), -2)],
  ['thanksgiving-friday', 'Day after Thanksgiving', year => shift(nth(year, 11, THURSDAY, 4), 1)],
  ['christmas-eve', 'Christmas Eve', year => utc(year, 12, 24)],
  ['new-years-eve', 'New Year’s Eve', year => utc(year, 12, 31)],
]

export function holidaysFor(year, list = FEDERAL) {
  return list.map(([id, name, rule, hasObserved]) => {
    const actual = rule(year)
    return { id, name, actual: toDay(actual), date: toDay(hasObserved ? observed(actual) : actual) }
  })
}

// Every closed or special day for one calendar year, from the saved settings. Keyed by date.
export function closuresFor(year, settings) {
  const days = new Map()
  // The following year is included because New Year's Day can be observed on 31 December.
  for (const y of [year, year + 1]) {
    for (const item of holidaysFor(y, FEDERAL)) if (settings.federal.includes(item.id) && item.date.startsWith(String(year))) days.set(item.date, { name: item.name, allDay: true })
  }
  for (const item of holidaysFor(year, OBSERVANCES)) if (settings.observances.includes(item.id) && !days.has(item.date)) days.set(item.date, { name: item.name, allDay: true })
  for (const item of settings.custom) if (item.date.startsWith(String(year))) days.set(item.date, item)
  return days
}
