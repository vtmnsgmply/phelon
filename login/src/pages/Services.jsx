import { useState } from 'react'
import { can } from '../auth/permissions'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Dialog } from '../components/Dialog'
import { Field } from '../components/Field'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { catalogServices } from '../lib/catalog'
import { money } from '../lib/money'

function ServiceDialog({ service, connected, onSubmit, onClose }) {
  const [form, setForm] = useState({ ...service, price: service.price ?? '', support: service.support.join('\n') })
  const [busy, setBusy] = useState(false)
  const [error, setError] = useState('')
  const set = key => event => setForm({ ...form, [key]: event.target.value })
  async function submit(event) {
    event.preventDefault()
    const support = form.support.split('\n').map(line => line.trim()).filter(Boolean)
    if (!support.length) { setError('Add at least one way you help.'); return }
    setBusy(true); setError('')
    try { await onSubmit({ title: form.title.trim(), description: form.description.trim(), audience: form.audience.trim(), support, price: form.price === '' ? null : Number(form.price) }) }
    catch (failure) { setError(failure.message); setBusy(false) }
  }
  return <Dialog title={`Edit ${service.title}`} onClose={onClose}>
    <form className="form dialog-body" onSubmit={submit}>
      {!connected && <Notice tone="warning"><p>The server can’t store service changes yet, so saving is turned off.</p></Notice>}
      {error && <Notice tone="danger"><p>{error}</p></Notice>}
      <div className="form-grid">
        <Field label="Title">{props => <input {...props} required maxLength={120} value={form.title} onChange={set('title')} />}</Field>
        <Field label="Price in US dollars" hint="Leave empty if no price is set.">{props => <input {...props} type="number" inputMode="decimal" min="0" max="1000000" step="0.01" value={form.price} onChange={set('price')} />}</Field>
      </div>
      <Field label="Description" hint="Describe non-legal support only. Don’t promise outcomes.">{props => <textarea {...props} required rows={4} maxLength={600} value={form.description} onChange={set('description')} />}</Field>
      <Field label="Who it is for">{props => <textarea {...props} required rows={2} maxLength={300} value={form.audience} onChange={set('audience')} />}</Field>
      <Field label="How we help" hint="One item per line.">{props => <textarea {...props} required rows={5} maxLength={600} value={form.support} onChange={set('support')} />}</Field>
      <div className="dialog-foot"><Button variant="secondary" onClick={onClose}>Cancel</Button><Button type="submit" loading={busy} disabled={!connected}>Save service</Button></div>
    </form>
  </Dialog>
}

export function Services({ api, user }) {
  const resource = useResource(api.services, catalogServices)
  const [editing, setEditing] = useState(null)
  const [message, setMessage] = useState('')
  const canEdit = can(user, 'services.manage')
  const connected = resource.status === 'ready'
  async function save(values) {
    await api.saveService(editing.id, values)
    setMessage(`${values.title} was saved.`); setEditing(null); resource.reload()
  }
  return <>
    <PageHeader title="Services" description="The services offered on the website." />
    {message && <Notice tone="success"><p>{message}</p></Notice>}
    <ResourceState resource={resource} unavailableText="This is the list the website is currently built from. Changes can’t be saved yet.">
      {resource.data && <ul className="service-list">{resource.data.map(service => <li key={service.id}>
        <Card title={service.title} actions={canEdit && <Button variant="secondary" onClick={() => { setMessage(''); setEditing(service) }}>Edit<span className="sr-only"> {service.title}</span></Button>}>
          <p className="price">{service.price == null || service.price === '' ? <span className="muted">Price not set</span> : money(service.price)}</p>
          <p>{service.description}</p>
          <p className="muted">For: {service.audience}</p>
          <ul className="chips">{service.support.map(item => <li key={item}>{item}</li>)}</ul>
          {service.options?.length > 0 && <p className="muted">Options: {service.options.join(', ')}</p>}
        </Card>
      </li>)}</ul>}
    </ResourceState>
    {editing && <ServiceDialog service={editing} connected={connected} onSubmit={save} onClose={() => setEditing(null)} />}
  </>
}
