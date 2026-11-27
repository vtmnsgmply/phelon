import { useState } from 'react'
import { Badge } from '../../components/Badge'
import { Button } from '../../components/Button'
import { Card } from '../../components/Card'
import { Icon } from '../../components/Icon'
import { PageHeader } from '../../components/PageHeader'
import { ResourceState } from '../../components/ResourceState'
import { SaveBar } from '../../components/SaveBar'
import { Switch } from '../../components/Switch'
import { useResource } from '../../hooks/useResource'
import { useSettingsForm } from '../../hooks/useSettingsForm'
import { formatDay, today } from '../../lib/dates'
import { closuresFor, FEDERAL, holidaysFor, OBSERVANCES } from '../../lib/holidays'
import { defaultHolidays, timeLabel } from '../../lib/schedule'
import { CustomClosure } from './CustomClosure'
import { MonthCalendar } from './MonthCalendar'

function HolidayList({ year, list, selected, onChange }) {
  return <ul className="rows">{holidaysFor(year, list).map(item => <li key={item.id}>
    <div><strong>{item.name}</strong><span className="muted">{formatDay(item.date)}{item.date !== item.actual && ` · observed (actual date ${formatDay(item.actual, { month: 'short', day: 'numeric' })})`}</span></div>
    <Switch label={`Closed on ${item.name}`} hideLabel checked={selected.includes(item.id)} onChange={on => onChange(on ? [...selected, item.id] : selected.filter(id => id !== item.id))} />
  </li>)}</ul>
}

function Editor({ resource, api, year }) {
  const form = useSettingsForm(resource.data, api.saveHolidays, null, 'Holiday settings saved.')
  const value = form.value
  const set = (key, next) => form.change({ ...value, [key]: next })
  const closures = closuresFor(year, value)
  const custom = [...value.custom].filter(item => item.date.startsWith(String(year))).sort((a, b) => a.date.localeCompare(b.date))
  const all = FEDERAL.every(([id]) => value.federal.includes(id))
  return <>
    <div className="holiday-layout">
      <div className="stack">
        <Card flush title="US federal holidays" description="A selected holiday is closed every year, on its observed date."
          actions={<Button variant="secondary" onClick={() => set('federal', all ? [] : FEDERAL.map(([id]) => id))}>{all ? 'Clear all' : 'Select all'}</Button>}>
          <HolidayList year={year} list={FEDERAL} selected={value.federal} onChange={next => set('federal', next)} />
        </Card>
        <Card flush title="Other common US observances" description="Not federal holidays. Off unless you choose them.">
          <HolidayList year={year} list={OBSERVANCES} selected={value.observances} onChange={next => set('observances', next)} />
        </Card>
      </div>
      <div className="stack">
        <Card title="Calendar" description={`${closures.size} closure${closures.size === 1 ? '' : 's'} and special days in ${year}.`}>
          <MonthCalendar key={year} year={year} closures={closures} />
        </Card>
        <Card title="Your own closures" description="One-off dates, or days with shorter hours.">
          {custom.length > 0 && <ul className="rows rows--inset">{custom.map(item => <li key={item.id}>
            <div><strong>{item.name}</strong><span className="muted">{formatDay(item.date)}</span></div>
            <div className="actions"><Badge tone={item.allDay ? 'danger' : 'gold'}>{item.allDay ? 'Closed' : `${timeLabel(item.open)} – ${timeLabel(item.close)}`}</Badge>
              <button type="button" className="icon-btn" aria-label={'Remove ' + item.name} onClick={() => set('custom', value.custom.filter(other => other.id !== item.id))}><Icon name="trash" size={18} /></button></div>
          </li>)}</ul>}
          <CustomClosure existing={value.custom} onAdd={item => set('custom', [...value.custom, item])} />
        </Card>
      </div>
    </div>
    <SaveBar dirty={form.dirty} saving={form.saving} disabled={resource.status === 'unavailable'} message={form.message} onSave={form.save} onReset={form.reset} />
  </>
}

export function Holidays({ api }) {
  const resource = useResource(api.holidays, defaultHolidays)
  const thisYear = Number(today().slice(0, 4))
  const [year, setYear] = useState(thisYear)
  return <>
    <PageHeader title="Holidays" description="Days when calls can’t be booked."
      actions={<div className="segmented" role="group" aria-label="Year">{[thisYear, thisYear + 1, thisYear + 2].map(item =>
        <button type="button" key={item} aria-pressed={item === year} onClick={() => setYear(item)}>{item}</button>)}</div>} />
    <ResourceState resource={resource}>{resource.data && <Editor resource={resource} api={api} year={year} />}</ResourceState>
  </>
}
