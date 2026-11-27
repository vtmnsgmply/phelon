import { useState } from 'react'
import { Badge } from '../components/Badge'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { EmptyState } from '../components/EmptyState'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { monthTitle, monthWeeks, WEEKDAYS } from '../lib/calendar'
import { businessDay, formatDay, formatTime as time, parseDay, today } from '../lib/dates'
import { closuresFor } from '../lib/holidays'
import { DAYS, timeLabel } from '../lib/schedule'
import { zoneLabel } from '../lib/timezone'

const plural = count => `${count} booking${count === 1 ? '' : 's'}`

function View({ data }) {
  const now = today()
  const [selected, setSelected] = useState(now)
  const [cursor, setCursor] = useState(() => [Number(now.slice(0, 4)), Number(now.slice(5, 7)) - 1])
  const [year, month] = cursor
  const move = step => { const next = new Date(Date.UTC(year, month + step, 1)); setCursor([next.getUTCFullYear(), next.getUTCMonth()]) }
  const goToday = () => { setCursor([Number(now.slice(0, 4)), Number(now.slice(5, 7)) - 1]); setSelected(now) }

  const byDay = new Map()
  for (const booking of [...data.bookings].sort((a, b) => a.startsAt.localeCompare(b.startsAt))) {
    const day = businessDay(booking.startsAt)
    byDay.set(day, [...(byDay.get(day) || []), booking])
  }
  // Closures and weekly days off are shown only when the server actually provides them.
  const closureOn = date => data.holidays ? closuresFor(Number(date.slice(0, 4)), data.holidays).get(date) : undefined
  const dayOff = date => data.schedule ? !data.schedule.days[DAYS[(parseDay(date).getUTCDay() + 6) % 7][0]].open : false
  const chosen = byDay.get(selected) || []
  const chosenClosure = closureOn(selected)

  return <div className="agenda">
    <Card flush>
      <div className="agenda-head">
        <h2 aria-live="polite">{monthTitle(year, month)}</h2>
        <div className="actions">
          <Button variant="secondary" icon="previous" aria-label="Previous month" onClick={() => move(-1)} />
          <Button variant="secondary" onClick={goToday}>Today</Button>
          <Button variant="secondary" icon="next" aria-label="Next month" onClick={() => move(1)} />
        </div>
      </div>
      <table className="month">
        <caption className="sr-only">Bookings in {monthTitle(year, month)}</caption>
        <thead><tr>{WEEKDAYS.map(day => <th key={day} scope="col">{day}</th>)}</tr></thead>
        <tbody>{monthWeeks(year, month).map((week, row) => <tr key={row}>{week.map((date, column) => {
          if (!date) return <td key={column} />
          const list = byDay.get(date) || []
          const closure = closureOn(date)
          const label = [formatDay(date, { weekday: 'long', month: 'long', day: 'numeric' }), plural(list.length),
            closure ? `${closure.name}, ${closure.allDay ? 'closed' : 'special hours'}` : dayOff(date) ? 'not a working day' : ''].filter(Boolean).join(', ')
          return <td key={column}>
            <button type="button" className="day" aria-label={label} aria-pressed={date === selected} aria-current={date === now ? 'date' : undefined}
              data-closure={closure ? (closure.allDay ? 'closed' : 'special') : undefined} data-off={dayOff(date) ? '' : undefined} onClick={() => setSelected(date)}>
              <span className="day-number">{Number(date.slice(8))}</span>
              {closure && <span className="day-closure">{closure.name}</span>}
              {list.slice(0, 2).map(item => <span className="day-event" key={item.reference}>{time(item.startsAt)} {item.name}</span>)}
              {list.length > 2 && <span className="day-more">+{list.length - 2} more</span>}
              {list.length > 0 && <span className="day-count">{list.length}</span>}
            </button>
          </td>
        })}</tr>)}</tbody>
      </table>
    </Card>
    <Card title={formatDay(selected, { weekday: 'long', month: 'long', day: 'numeric' })} description={plural(chosen.length)}>
      {chosenClosure && <Badge tone={chosenClosure.allDay ? 'danger' : 'gold'}>{chosenClosure.name}: {chosenClosure.allDay ? 'closed' : `${timeLabel(chosenClosure.open)} – ${timeLabel(chosenClosure.close)}`}</Badge>}
      {!chosenClosure && dayOff(selected) && <Badge tone="muted">Not a working day</Badge>}
      {chosen.length === 0 ? <EmptyState icon="calendar" title="No bookings on this day" />
        : <ul className="rows rows--inset">{chosen.map(item => <li key={item.reference}>
          <div><strong>{time(item.startsAt)} · {item.name}</strong><span className="muted">{item.serviceTitle}</span>
            <a href={'mailto:' + item.email}>{item.email}</a></div>
        </li>)}</ul>}
    </Card>
  </div>
}

export function Calendar({ api }) {
  const resource = useResource(async signal => {
    const optional = load => load(signal).catch(error => { if ([403, 404, 405, 501].includes(error.status)) return null; throw error })
    const [bookings, holidays, schedule] = await Promise.all([api.bookings(signal), optional(api.holidays), optional(api.schedule)])
    return { bookings, holidays, schedule }
  })
  return <>
    <PageHeader title="Calendar" description={`Upcoming bookings by day. Times are in ${zoneLabel()}. Past bookings are not shown.`}
      actions={<Button variant="secondary" icon="refresh" onClick={resource.reload}>Refresh</Button>} />
    <ResourceState resource={resource}>{resource.data && <>
      {(!resource.data.holidays || !resource.data.schedule) && <Notice><p>Holidays and opening days aren’t available from the server, so only bookings are shown.</p></Notice>}
      <View data={resource.data} />
    </>}</ResourceState>
  </>
}
