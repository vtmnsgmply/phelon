import { useEffect, useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import { api } from './http'
import { hasService, sentence, services } from './catalog'
import { Button } from './components/Button'
import { Field } from './components/Field'
import { Icon } from './components/Icon'
import { Notice } from './components/Notice'
import { bookingRules } from './lib/bookingRules'

const zones = [...new Set(['UTC', ...(Intl.supportedValuesOf?.('timeZone') || ['America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles'])])]
const present = new Intl.DateTimeFormat().resolvedOptions().timeZone
const PAGE = 14
const OFFLINE = 'We couldn’t load available times. Check your connection and try again.'

// Calendar dates are plain days in the booking time zone, so they are formatted in UTC to avoid shifting.
const day = (date, options) => new Intl.DateTimeFormat(undefined, { timeZone: 'UTC', ...options }).format(new Date(date + 'T12:00:00Z'))

function dateRange(from, to) {
  const dates = []
  for (let time = Date.parse(from + 'T12:00:00Z'); dates.length < 400; time += 86400000) {
    const date = new Date(time).toISOString().slice(0, 10)
    if (date > to) break
    dates.push(date)
  }
  return dates
}

export function BookingPicker({ onInterest }) {
  const [query] = useSearchParams()
  const key = query.get('service')
  const subtype = query.get('subservice')
  const valid = key ? Boolean(hasService(key) && (!subtype || services[key].subtypes?.[subtype])) : !subtype
  const defaultInterest = valid && key ? [key, subtype].filter(Boolean).join('/') : ''
  const [date, setDate] = useState('')
  const [start, setStart] = useState('')
  const [zone, setZone] = useState(zones.includes(present) ? present : 'UTC')
  const [schedule, setSchedule] = useState(null)
  const [message, setMessage] = useState('')
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [retry, setRetry] = useState(0)
  const [confirmation, setConfirmation] = useState(null)
  const [page, setPage] = useState(0)
  const [errors, setErrors] = useState({})
  const [copied, setCopied] = useState('')
  const format = value => new Intl.DateTimeFormat(undefined, {
    timeZone: zone, weekday: 'short', month: 'short', day: 'numeric', hour: 'numeric',
    minute: '2-digit', timeZoneName: 'short',
  }).format(new Date(value))
  // Slot pills show the time; the weekday is added when the visitor's zone moves it to another day.
  const slotLabel = value => {
    const local = new Intl.DateTimeFormat('en-CA', { timeZone: zone, year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date(value))
    return new Intl.DateTimeFormat(undefined, { timeZone: zone, hour: 'numeric', minute: '2-digit', ...(local !== date && { weekday: 'short' }) }).format(new Date(value))
  }

  useEffect(() => {
    if (!valid || confirmation) return
    const controller = new AbortController()
    setLoading(true)
    setStart('')
    api('/availability' + (date ? '?date=' + encodeURIComponent(date) : ''), { signal: controller.signal })
      .then(data => {
        setSchedule(data)
        if (!date) setDate(data.minDate)
      })
      .catch(error => {
        if (error.name !== 'AbortError') {
          setSchedule(null)
          setMessage(error.status ? error.message : OFFLINE)
        }
      })
      .finally(() => { if (!controller.signal.aborted) setLoading(false) })
    return () => controller.abort()
  }, [date, retry, valid, confirmation])

  async function reserve(event) {
    event.preventDefault()
    if (!start || loading || saving) return
    const body = Object.fromEntries(new FormData(event.currentTarget))
    setSaving(true)
    setMessage('')
    try {
      setConfirmation(await api('/bookings', { method: 'POST', body }))
    } catch (error) {
      setMessage(error.status === 409 ? 'That time was just taken. Pick another.'
        : error.status ? error.message : 'We couldn’t save your booking. Check your connection and try again.')
      if (error.status === 409) setRetry(value => value + 1)
    } finally {
      setSaving(false)
    }
  }

  // Validity is set while typing so submission is blocked; the message appears after leaving the field.
  const checked = name => ({
    name,
    onInput: event => {
      const problem = bookingRules[name](event.target.value)
      event.target.setCustomValidity(problem)
      if (errors[name]) setErrors(previous => ({ ...previous, [name]: problem }))
    },
    onBlur: event => {
      const problem = event.target.value ? bookingRules[name](event.target.value) : ''
      event.target.setCustomValidity(problem)
      setErrors(previous => ({ ...previous, [name]: problem }))
    },
  })

  async function copy() {
    try { await navigator.clipboard.writeText(confirmation.reference); setCopied('Reference copied.') }
    catch { setCopied('Copying isn’t available. Select the reference and copy it.') }
  }

  if (!valid) return <div className="ph-card"><Notice tone="warning" role="alert"><p>That service selection is not available.</p><Link to="/book">Continue to general booking</Link></Notice></div>
  if (confirmation) return <div className="ph-card" role="status">
    <span className="ph-icon-tile"><Icon name="success" /></span>
    <h2>Your appointment is confirmed</h2>
    <p>{format(confirmation.startsAt)}</p>
    <div className="ph-reference"><span>Confirmation reference</span><code>{confirmation.reference}</code>
      <Button variant="secondary" icon="copy" onClick={copy}>Copy reference</Button></div>
    <p className="ph-hint" role="status">{copied}</p>
    <Button size="lg" to={'/intake?appointmentId=' + confirmation.reference}>Complete the optional intake</Button>
  </div>

  const dates = schedule ? dateRange(schedule.minDate, schedule.maxDate) : []
  const pages = Math.ceil(dates.length / PAGE)
  return <form className="ph-card ph-form ph-picker" onSubmit={reserve} id="booking">
    <div className="ph-stack"><h2>Choose a date and time</h2><p className="ph-muted">Complimentary · 15 minutes · No obligation</p></div>
    <fieldset disabled={saving}>
      <input type="hidden" name="date" value={date} />
      <input type="hidden" name="start" value={start} />
      {schedule && <div className="ph-field">
        <div className="ph-dates-head"><span className="ph-legend" id="date-label">Date{date && <span className="ph-optional"> · {day(date, { weekday: 'long', month: 'long', day: 'numeric' })}</span>}</span>
          {pages > 1 && <span className="ph-actions">
            <Button variant="secondary" disabled={page === 0} aria-label="Earlier dates" onClick={() => setPage(value => value - 1)}><Icon name="chevronLeft" size={18} /></Button>
            <Button variant="secondary" disabled={page >= pages - 1} aria-label="Later dates" onClick={() => setPage(value => value + 1)}><Icon name="chevronRight" size={18} /></Button>
          </span>}</div>
        <ul className="ph-dates" aria-labelledby="date-label">{dates.slice(page * PAGE, page * PAGE + PAGE).map(value => <li key={value}>
          <button type="button" className="ph-date" aria-pressed={value === date} onClick={() => { setMessage(''); setDate(value) }}>
            <small aria-hidden="true">{day(value, { weekday: 'short' })}</small><strong aria-hidden="true">{day(value, { day: 'numeric' })}</strong>
            <span className="sr-only">{day(value, { weekday: 'long', month: 'long', day: 'numeric' })}</span>
          </button></li>)}</ul>
      </div>}
      <div className="ph-field" aria-busy={loading}>
        <span className="ph-legend" id="time-label">Available times</span>
        {loading ? <><span className="sr-only" role="status">Checking availability…</span><ul className="ph-slots" aria-hidden="true">{[0, 1, 2, 3, 4, 5].map(item => <li key={item}><div className="ph-skeleton" /></li>)}</ul></>
          : schedule?.slots?.length ? <ul className="ph-slots" aria-labelledby="time-label">{schedule.slots.map(value => <li key={value}>
            <button type="button" className="ph-slot" aria-pressed={value === start} onClick={() => { setMessage(''); setStart(value) }}>{slotLabel(value)}</button></li>)}</ul>
            : <p className="ph-hint">{schedule ? 'There are no appointments on this date. Choose another date.' : 'No times to show yet.'}</p>}
      </div>
      <Field label="Your time zone" hint={schedule && `Dates use ${schedule.timezone}. Times are shown in your selected time zone.`}>{props =>
        <select {...props} name="timezone" value={zone} onChange={event => setZone(event.target.value)}>
          {zones.map(value => <option key={value}>{value}</option>)}
        </select>}</Field>
      <Field label="Service interest" optional>{props =>
        <select {...props} key={defaultInterest} name="interest" defaultValue={defaultInterest} onChange={event => onInterest?.(event.target.value)}>
          <option value="">General / not sure yet</option>
          {Object.entries(services).flatMap(([id, item]) => [
            <option value={id} key={id}>{sentence(item.title)}</option>,
            ...Object.entries(item.subtypes || {}).map(([type, sub]) => <option value={id + '/' + type} key={type}>{sentence(sub.title)}</option>),
          ])}
        </select>}</Field>
      <div className="ph-form-grid">
        <Field label="Full name">{props => <input {...props} name="name" autoComplete="name" required maxLength={100} />}</Field>
        <Field label="Email">{props => <input {...props} name="email" type="email" inputMode="email" autoComplete="email" required maxLength={254} />}</Field>
        <Field label="Phone" error={errors.phone}>{props => <input {...props} {...checked('phone')} type="tel" inputMode="tel" autoComplete="tel" required maxLength={32} />}</Field>
        <Field label="State or territory" error={errors.state} hint="Two letters, for example NY.">{props =>
          <input {...props} {...checked('state')} autoComplete="address-level1" autoCapitalize="characters" pattern="[A-Za-z]{2}" required maxLength={2} />}</Field>
      </div>
      <Field label="Main area of concern" hint="Keep this general. Don’t include case details or sensitive identifiers.">{props =>
        <textarea {...props} name="concern" required rows={3} maxLength={300} />}</Field>
      {message && <Notice tone="danger"><p>{message}</p>
        {!loading && !schedule && <Button variant="secondary" onClick={() => { setMessage(''); setRetry(value => value + 1) }}>Try again</Button>}</Notice>}
      <div className="ph-confirm" data-ready={start ? '' : undefined}>
        <Button type="submit" size="lg" loading={saving} disabled={!start || loading || saving}>{saving ? 'Reserving…' : 'Confirm appointment'}</Button>
      </div>
      <p className="ph-hint">{!start && 'Choose a time to confirm your appointment. '}Need help? <Link to="/contact">Contact our team</Link>.</p>
    </fieldset>
  </form>
}
