import { useState } from 'react'
import { Button } from '../../components/Button'
import { Dialog } from '../../components/Dialog'
import { Field } from '../../components/Field'
import { Notice } from '../../components/Notice'
import { Badge } from '../../components/Badge'
import { CLIENT_STATUS } from '../../lib/clients'
import { formatDateTime } from '../../lib/dates'

const blank = { name: '', email: '', phone: '', status: 'new' }

// One dialog for adding a client, editing one, or reading one without edit access.
export function ClientDialog({ client, bookings, intakeRefs, canEdit, canSeeBookings, connected, onSubmit, onClose }) {
  const [form, setForm] = useState(() => ({ ...blank, ...client }))
  const [busy, setBusy] = useState(false)
  const [error, setError] = useState('')
  const set = key => event => setForm({ ...form, [key]: event.target.value })

  async function submit(event) {
    event.preventDefault()
    setBusy(true); setError('')
    try { await onSubmit({ name: form.name.trim(), email: form.email.trim().toLowerCase(), phone: form.phone.trim(), status: form.status }) }
    catch (failure) { setError(failure.message); setBusy(false) }
  }

  const history = client && canSeeBookings && <section className="client-history">
    <h3>Upcoming appointments</h3>
    {bookings.length === 0 ? <p className="muted">None scheduled.</p> : <ul className="rows rows--inset">{bookings.map(item => <li key={item.reference}>
      <div><strong>{formatDateTime(item.startsAt)}</strong><span className="muted">{item.serviceTitle}</span></div>
      {intakeRefs && <Badge tone={intakeRefs.has(item.reference) ? 'success' : 'muted'}>{intakeRefs.has(item.reference) ? 'Intake received' : 'No intake yet'}</Badge>}
    </li>)}</ul>}
  </section>

  return <Dialog title={!client ? 'Add client' : canEdit ? `Edit ${client.name}` : client.name} onClose={onClose}>
    {canEdit ? <form className="form dialog-body" onSubmit={submit}>
      {!connected && <Notice tone="warning"><p>The server can’t store client records yet, so saving is turned off.</p></Notice>}
      {error && <Notice tone="danger"><p>{error}</p></Notice>}
      <div className="form-grid">
        <Field label="Full name">{props => <input {...props} required maxLength={100} autoComplete="off" value={form.name} onChange={set('name')} />}</Field>
        <Field label="Email">{props => <input {...props} type="email" required maxLength={254} autoComplete="off" value={form.email} onChange={set('email')} />}</Field>
        <Field label="Phone">{props => <input {...props} type="tel" maxLength={32} autoComplete="off" value={form.phone} onChange={set('phone')} />}</Field>
        <Field label="Status">{props => <select {...props} value={form.status} onChange={set('status')}>{CLIENT_STATUS.map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select>}</Field>
      </div>
      <p className="hint">Keep records general. Don’t add case details or sensitive identifiers.</p>
      {history}
      <div className="dialog-foot"><Button variant="secondary" onClick={onClose}>Cancel</Button>
        <Button type="submit" loading={busy} disabled={!connected}>{client ? 'Save client' : 'Add client'}</Button></div>
    </form> : <div className="form dialog-body">
      <dl className="answers"><div><dt>Email</dt><dd><a href={'mailto:' + client.email}>{client.email}</a></dd></div><div><dt>Phone</dt><dd>{client.phone || '—'}</dd></div></dl>
      {history}
      <div className="dialog-foot"><Button variant="secondary" onClick={onClose}>Close</Button></div>
    </div>}
  </Dialog>
}
