import { useState } from 'react'
import { Button } from '../../components/Button'
import { Field } from '../../components/Field'
import { Switch } from '../../components/Switch'
import { TIMES, timeLabel } from '../../lib/schedule'

const blank = { name: '', date: '', allDay: true, open: '09:00', close: '12:00' }

export function CustomClosure({ existing, onAdd }) {
  const [form, setForm] = useState(blank)
  const [error, setError] = useState('')
  const set = (key, value) => { setForm({ ...form, [key]: value }); setError('') }
  function add(event) {
    event.preventDefault()
    if (existing.some(item => item.date === form.date)) { setError('There is already a custom entry on that date.'); return }
    if (!form.allDay && form.close <= form.open) { setError('Closing time must be later than opening time.'); return }
    onAdd({ id: crypto.randomUUID(), name: form.name.trim(), date: form.date, allDay: form.allDay, ...(!form.allDay && { open: form.open, close: form.close }) })
    setForm(blank)
  }
  return <form className="form" onSubmit={add}>
    <div className="form-grid">
      <Field label="Name">{props => <input {...props} required maxLength={80} placeholder="e.g. Company retreat" value={form.name} onChange={event => set('name', event.target.value)} />}</Field>
      <Field label="Date" error={error}>{props => <input {...props} type="date" required value={form.date} onChange={event => set('date', event.target.value)} />}</Field>
    </div>
    <Switch label="Closed all day" help="Turn off to set special opening hours." checked={form.allDay} onChange={value => set('allDay', value)} />
    {!form.allDay && <div className="form-grid">{[['open', 'Opens'], ['close', 'Closes']].map(([key, label]) => <Field key={key} label={label}>{props =>
      <select {...props} value={form[key]} onChange={event => set(key, event.target.value)}>{TIMES.map(time => <option key={time} value={time}>{timeLabel(time)}</option>)}</select>}</Field>)}</div>}
    <div className="actions"><Button type="submit" variant="secondary" icon="plus" disabled={!form.name.trim() || !form.date}>Add closure</Button></div>
  </form>
}
