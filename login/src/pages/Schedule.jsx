import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Field } from '../components/Field'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { SaveBar } from '../components/SaveBar'
import { Switch } from '../components/Switch'
import { useResource } from '../hooks/useResource'
import { useSettingsForm } from '../hooks/useSettingsForm'
import { dayError, DAYS, defaultSchedule, scheduleError, slotCount, TIMES, timeLabel } from '../lib/schedule'
import { BUSINESS_ZONE, zoneLabel } from '../lib/timezone'

function TimeSelect({ label, value, optional, onChange, invalid }) {
  return <label className="time"><span>{label}</span>
    <select className="control" value={value} aria-invalid={invalid || undefined} onChange={event => onChange(event.target.value)}>
      {optional && <option value="">None</option>}
      {TIMES.map(time => <option key={time} value={time}>{timeLabel(time)}</option>)}
    </select></label>
}

function Editor({ resource, api }) {
  const form = useSettingsForm(resource.data, api.saveSchedule, scheduleError, 'Schedule saved.')
  const schedule = form.value
  const set = (key, value) => form.change({ ...schedule, [key]: value })
  const setDay = (key, patch) => set('days', { ...schedule.days, [key]: { ...schedule.days[key], ...patch } })
  const copyMonday = () => set('days', { ...schedule.days, ...Object.fromEntries(['tue', 'wed', 'thu', 'fri'].map(key => [key, { ...schedule.days.mon }])) })
  const weekly = DAYS.reduce((total, [key]) => total + slotCount(schedule.days[key], schedule.slotMinutes), 0)

  return <>
    <Card title="Time zone" description="Opening hours, bookings and the calendar all use this zone. Visitors see times converted to their own.">
      <p className="zone"><strong>{zoneLabel()}</strong><span className="muted">{BUSINESS_ZONE}, set by VITE_BOOKING_TIMEZONE in the .env file.</span></p>
      {schedule.timezone !== BUSINESS_ZONE && <Notice tone="warning"><p>The server is set to {schedule.timezone}. Make the server’s booking time zone and this .env value match.</p></Notice>}
    </Card>
    <Card title="Weekly hours" description={`${weekly} appointment times in a full week.`} actions={<Button variant="secondary" onClick={copyMonday}>Copy Monday to weekdays</Button>} flush>
      <ul className="week">{DAYS.map(([key, name]) => { const day = schedule.days[key]; const error = dayError(day)
        return <li key={key} data-closed={day.open ? undefined : ''}>
          <fieldset><legend className="sr-only">{name}</legend>
            <div className="week-day"><Switch label={name} help={day.open ? `${slotCount(day, schedule.slotMinutes)} times` : 'Closed'} checked={day.open} onChange={open => setDay(key, { open })} /></div>
            {day.open && <div className="week-times">
              <TimeSelect label="Opens" value={day.from} onChange={from => setDay(key, { from })} invalid={Boolean(error)} />
              <TimeSelect label="Closes" value={day.to} onChange={to => setDay(key, { to })} invalid={Boolean(error)} />
              <TimeSelect label="Break from" optional value={day.breakFrom} onChange={breakFrom => setDay(key, { breakFrom })} />
              <TimeSelect label="Break to" optional value={day.breakTo} onChange={breakTo => setDay(key, { breakTo })} />
            </div>}
          </fieldset>
          {error && <p className="error" role="alert">{name}: {error}</p>}
        </li> })}</ul>
    </Card>
    <Card title="Appointment rules">
      <div className="form-grid form-grid--3">
        <Field label="Appointment length">{props => <select {...props} value={schedule.slotMinutes} onChange={event => set('slotMinutes', Number(event.target.value))}>
          {[15, 30, 45, 60].map(value => <option key={value} value={value}>{value} minutes</option>)}</select>}</Field>
        <Field label="Minimum notice" hint="How soon someone can book.">{props => <select {...props} value={schedule.noticeHours} onChange={event => set('noticeHours', Number(event.target.value))}>
          {[1, 2, 4, 12, 24, 48, 72].map(value => <option key={value} value={value}>{value} hour{value > 1 ? 's' : ''}</option>)}</select>}</Field>
        <Field label="Booking window (days)" hint="How far ahead someone can book.">{props => <input {...props} type="number" min="1" max="365" value={Number.isNaN(schedule.windowDays) ? '' : schedule.windowDays} onChange={event => set('windowDays', event.target.valueAsNumber)} />}</Field>
      </div>
    </Card>
    <SaveBar dirty={form.dirty} saving={form.saving} disabled={resource.status === 'unavailable'} message={form.message} onSave={form.save} onReset={form.reset} />
  </>
}

export function Schedule({ api }) {
  const resource = useResource(api.schedule, defaultSchedule)
  return <>
    <PageHeader title="Schedule settings" description="Choose which days are open, Monday to Sunday, and the hours for each day." />
    <ResourceState resource={resource}>{resource.data && <Editor resource={resource} api={api} />}</ResourceState>
  </>
}
