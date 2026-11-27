import { useState } from 'react'
import { can } from '../auth/permissions'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { EmptyState } from '../components/EmptyState'
import { Field } from '../components/Field'
import { Icon } from '../components/Icon'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { useUnsaved } from '../hooks/useUnsaved'
import { confirmAction } from '../lib/alerts'
import { clientsFromBookings } from '../lib/clients'
import { isEmail, mailto, TEMPLATES } from '../lib/email'

const MISSING = [404, 405, 501]
const blank = { id: null, to: '', subject: '', body: '' }

function Editor({ resource, api }) {
  const { drafts, clients, connected } = resource.data
  const [saved, setSaved] = useState(blank)
  const [draft, setDraft] = useState(blank)
  const [list, setList] = useState(drafts)
  const [message, setMessage] = useState(null)
  const [busy, setBusy] = useState(false)
  const dirty = ['to', 'subject', 'body'].some(key => draft[key] !== saved[key])
  useUnsaved(dirty)
  const set = key => event => { setDraft({ ...draft, [key]: event.target.value }); setMessage(null) }
  async function load(next) {
    if (dirty && !await confirmAction({ title: 'Replace this message?', text: 'What you are writing isn’t saved.', confirmText: 'Replace' })) return
    setDraft(next); setSaved(next); setMessage(null)
  }
  async function applyTemplate(id) {
    const found = TEMPLATES.find(item => item[0] === id)
    if (!found || (draft.body && !await confirmAction({ title: 'Use this template?', text: 'It replaces the subject and message.', confirmText: 'Replace' }))) return
    setDraft(previous => ({ ...previous, subject: found[2], body: found[3] })); setMessage(null)
  }
  const sendable = isEmail(draft.to.trim()) && draft.subject.trim()

  async function save() {
    setBusy(true); setMessage(null)
    try {
      const stored = await api.saveDraft({ ...draft, to: draft.to.trim() })
      setDraft(stored); setSaved(stored)
      setList(await api.drafts())
      setMessage(['success', 'Draft saved.'])
    } catch (error) { setMessage(['danger', error.status ? `Not saved. ${error.message}` : 'Not saved. We couldn’t reach the server.']) }
    finally { setBusy(false) }
  }
  async function remove(item) {
    if (!await confirmAction({ title: 'Delete this draft?', text: item.subject || 'Untitled', confirmText: 'Delete draft' })) return
    try {
      await api.deleteDraft(item.id); setList(await api.drafts())
      if (draft.id === item.id) { setDraft(blank); setSaved(blank) }
    } catch (error) { setMessage(['danger', error.message]) }
  }
  async function copy() {
    try { await navigator.clipboard.writeText(`${draft.subject}\n\n${draft.body}`); setMessage(['success', 'Message copied.']) }
    catch { setMessage(['danger', 'Copying isn’t available. Select the text and copy it.']) }
  }

  return <div className="drafts">
    <Card flush title="Saved drafts" actions={<Button variant="secondary" icon="plus" onClick={() => load(blank)}>New</Button>}>
      {list.length === 0 ? <EmptyState icon="mail" title="No saved drafts" />
        : <ul className="rows">{list.map(item => <li key={item.id}>
          <div><strong>{item.subject || 'Untitled'}</strong><span className="muted">{item.to || 'No recipient'}</span></div>
          <div className="actions"><Button variant="secondary" icon="open" onClick={() => load(item)}>Open<span className="sr-only"> {item.subject}</span></Button>
            <button type="button" className="icon-btn" aria-label={'Delete ' + (item.subject || 'untitled draft')} onClick={() => remove(item)}><Icon name="trash" size={18} /></button></div>
        </li>)}</ul>}
    </Card>
    <Card title={draft.id ? 'Edit draft' : 'New message'}>
      <form className="form" onSubmit={event => { event.preventDefault(); save() }}>
        {message && <Notice tone={message[0]}><p>{message[1]}</p></Notice>}
        <Field label="Start from a template">{props => <select {...props} value="" onChange={event => applyTemplate(event.target.value)}><option value="">Choose a template</option>{TEMPLATES.map(([id, label]) => <option key={id} value={id}>{label}</option>)}</select>}</Field>
        <Field label="To" error={draft.to && !isEmail(draft.to.trim()) ? 'Enter one email address.' : ''}>{props => <>
          <input {...props} type="email" list="draft-clients" maxLength={254} autoComplete="off" value={draft.to} onChange={set('to')} />
          <datalist id="draft-clients">{clients.map(client => <option key={client.id} value={client.email}>{client.name}</option>)}</datalist></>}</Field>
        <Field label="Subject">{props => <input {...props} maxLength={150} value={draft.subject} onChange={set('subject')} />}</Field>
        <Field label="Message" hint="Keep it general. Don’t include case details or sensitive identifiers.">{props => <textarea {...props} className="control message" maxLength={5000} value={draft.body} onChange={set('body')} />}</Field>
        <div className="actions">
          {sendable ? <Button href={mailto({ ...draft, to: draft.to.trim() })} icon="mail">Open in email app</Button> : <Button icon="mail" disabled>Open in email app</Button>}
          <Button variant="secondary" icon="copy" disabled={!draft.body} onClick={copy}>Copy message</Button>
          <Button type="submit" variant="secondary" icon="save" loading={busy} disabled={!connected || !dirty}>Save draft</Button>
        </div>
        <p className="hint">Nothing is sent from here. “Open in email app” puts the message in your own email program, where you review and send it.</p>
      </form>
    </Card>
  </div>
}

export function EmailDrafts({ api, user }) {
  const resource = useResource(async signal => {
    let clients = []
    if (can(user, 'clients.view')) {
      clients = await api.clients(signal).catch(async error => {
        if (!MISSING.includes(error.status) || !can(user, 'bookings.view')) return []
        return clientsFromBookings(await api.bookings(signal))
      })
    }
    try { return { connected: true, drafts: await api.drafts(signal), clients } }
    catch (error) { if (!MISSING.includes(error.status)) throw error; return { connected: false, drafts: [], clients } }
  })
  return <>
    <PageHeader title="Email drafts" description="Write messages to clients and send them from your email app." />
    <ResourceState resource={resource}>{resource.data && <>
      {!resource.data.connected && <Notice tone="warning" title="Saving drafts isn’t connected yet"><p>You can still write a message, copy it or open it in your email app. It will be lost if you leave this page.</p></Notice>}
      <Editor resource={resource} api={api} />
    </>}</ResourceState>
  </>
}
