import { useState } from 'react'
import { Button } from '../../components/Button'
import { Dialog } from '../../components/Dialog'
import { Field } from '../../components/Field'
import { Notice } from '../../components/Notice'
import { catalogServices } from '../../lib/catalog'
import { CONTRACT_STATUS } from '../../lib/contracts'

const blank = { title: '', clientName: '', clientEmail: '', serviceId: '', amount: '', status: 'draft', startsOn: '' }

export function ContractDialog({ contract, connected, onSubmit, onClose }) {
  const [form, setForm] = useState(() => ({ ...blank, ...contract }))
  const [busy, setBusy] = useState(false)
  const [error, setError] = useState('')
  const set = key => event => setForm({ ...form, [key]: event.target.value })
  async function submit(event) {
    event.preventDefault()
    setBusy(true); setError('')
    const { id: _id, ...values } = form
    try { await onSubmit({ ...values, title: values.title.trim(), clientName: values.clientName.trim(), clientEmail: values.clientEmail.trim().toLowerCase(), amount: values.amount === '' ? null : Number(values.amount) }) }
    catch (failure) { setError(failure.message); setBusy(false) }
  }
  return <Dialog title={contract ? `Edit ${contract.title}` : 'Add contract'} onClose={onClose}>
    <form className="form dialog-body" onSubmit={submit}>
      {!connected && <Notice tone="warning"><p>The server can’t store contracts yet, so saving is turned off.</p></Notice>}
      {error && <Notice tone="danger"><p>{error}</p></Notice>}
      <Field label="Contract title">{props => <input {...props} required maxLength={150} value={form.title} onChange={set('title')} />}</Field>
      <div className="form-grid">
        <Field label="Client name">{props => <input {...props} required maxLength={100} autoComplete="off" value={form.clientName} onChange={set('clientName')} />}</Field>
        <Field label="Client email">{props => <input {...props} type="email" required maxLength={254} autoComplete="off" value={form.clientEmail} onChange={set('clientEmail')} />}</Field>
        <Field label="Service">{props => <select {...props} value={form.serviceId} onChange={set('serviceId')}><option value="">Not specified</option>{catalogServices().map(item => <option key={item.id} value={item.id}>{item.title}</option>)}</select>}</Field>
        <Field label="Value in US dollars">{props => <input {...props} type="number" inputMode="decimal" min="0" max="1000000" step="0.01" value={form.amount ?? ''} onChange={set('amount')} />}</Field>
        <Field label="Status">{props => <select {...props} value={form.status} onChange={set('status')}>{CONTRACT_STATUS.map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select>}</Field>
        <Field label="Start date">{props => <input {...props} type="date" value={form.startsOn} onChange={set('startsOn')} />}</Field>
      </div>
      <p className="hint">This keeps a record of agreements. It doesn’t write or sign legal documents.</p>
      <div className="dialog-foot"><Button variant="secondary" onClick={onClose}>Cancel</Button><Button type="submit" loading={busy} disabled={!connected}>{contract ? 'Save contract' : 'Add contract'}</Button></div>
    </form>
  </Dialog>
}
