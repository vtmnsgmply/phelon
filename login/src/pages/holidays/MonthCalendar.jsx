import { useState } from 'react'
import { Button } from '../../components/Button'
import { monthTitle, monthWeeks, WEEKDAYS } from '../../lib/calendar'
import { today } from '../../lib/dates'

// A read-only month view of closed days for the selected year.
export function MonthCalendar({ year, closures }) {
  const [month, setMonth] = useState(() => today().startsWith(String(year)) ? Number(today().slice(5, 7)) - 1 : 0)
  const title = monthTitle(year, month)
  return <div className="calendar">
    <div className="calendar-head">
      <Button variant="secondary" icon="previous" aria-label="Previous month" disabled={month === 0} onClick={() => setMonth(month - 1)} />
      <strong aria-live="polite">{title}</strong>
      <Button variant="secondary" icon="next" aria-label="Next month" disabled={month === 11} onClick={() => setMonth(month + 1)} />
    </div>
    <table><caption className="sr-only">Closures in {title}</caption>
      <thead><tr>{WEEKDAYS.map(day => <th key={day} scope="col">{day}</th>)}</tr></thead>
      <tbody>{monthWeeks(year, month).map((week, row) => <tr key={row}>{week.map((date, index) => {
        if (!date) return <td key={index} />
        const closure = closures.get(date)
        return <td key={index} data-closure={closure ? (closure.allDay ? 'closed' : 'special') : undefined} title={closure?.name}>
          {Number(date.slice(8))}{closure && <span className="sr-only">: {closure.name}{closure.allDay ? ', closed' : ', special hours'}</span>}</td>
      })}</tr>)}</tbody>
    </table>
    <p className="legend"><span data-closure="closed" />Closed <span data-closure="special" />Special hours</p>
  </div>
}
