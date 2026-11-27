import { useState } from 'react'
import { PERMISSIONS } from '../auth/permissions'
import { Badge } from '../components/Badge'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Field } from '../components/Field'
import { Icon } from '../components/Icon'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { PasswordField } from '../components/PasswordField'
import { PasswordRules } from '../components/PasswordRules'
import { useUnsaved } from '../hooks/useUnsaved'
import { passwordChecks } from '../lib/password'
import { failureText } from './account/failureText'
import { PhotoCard } from './account/PhotoCard'

function Profile({ api, user, onUser }) {
  const [form, setForm] = useState({ displayName: user.displayName, email: user.email })
  const [state, setState] = useState({ busy: false, tone: '', text: '' })
  const dirty = form.displayName !== user.displayName || form.email !== user.email
  useUnsaved(dirty)
  async function save(event) {
    event.preventDefault()
    setState({ busy: true, tone: '', text: '' })
    try {
      await api.updateProfile({ displayName: form.displayName.trim(), email: form.email.trim() })
      onUser({ ...user, displayName: form.displayName.trim(), email: form.email.trim() })
      setState({ busy: false, tone: 'success', text: 'Your profile was saved.' })
    } catch (error) { setState({ busy: false, tone: 'danger', text: failureText(error, 'Editing your profile') }) }
  }
  return <Card title="Profile" description="Your name and sign-in email.">
    <form className="form" onSubmit={save}>
      {state.text && <Notice tone={state.tone}><p>{state.text}</p></Notice>}
      <div className="form-grid">
        <Field label="Display name">{props => <input {...props} required maxLength={100} autoComplete="name" value={form.displayName} onChange={event => setForm({ ...form, displayName: event.target.value })} />}</Field>
        <Field label="Work email" hint={!user.email ? 'The server doesn’t send your email yet.' : undefined}>{props => <input {...props} type="email" required maxLength={254} autoComplete="email" value={form.email} onChange={event => setForm({ ...form, email: event.target.value })} />}</Field>
      </div>
      <div className="actions"><Button type="submit" loading={state.busy} disabled={!dirty}>Save profile</Button></div>
    </form>
  </Card>
}

function Password({ api }) {
  const empty = { current: '', next: '', confirm: '' }
  const [form, setForm] = useState(empty)
  const [state, setState] = useState({ busy: false, tone: '', text: '' })
  const checks = passwordChecks(form.next, form.confirm, form.current)
  const ready = form.current && checks.every(([, met]) => met)
  const field = key => ({ value: form[key], required: true, maxLength: 72, onChange: event => setForm({ ...form, [key]: event.target.value }) })
  async function save(event) {
    event.preventDefault()
    if (!ready) return
    setState({ busy: true, tone: '', text: '' })
    try {
      await api.changePassword({ currentPassword: form.current, newPassword: form.next })
      setState({ busy: false, tone: 'success', text: api.preview ? 'Preview only: no password was changed.' : 'Your password was changed.' })
    } catch (error) { setState({ busy: false, tone: 'danger', text: failureText(error, 'Changing your password') }) }
    setForm(empty)
  }
  return <Card title="Password" description="Use a password you don’t use anywhere else.">
    <form className="form" onSubmit={save}>
      {state.text && <Notice tone={state.tone}><p>{state.text}</p></Notice>}
      <PasswordField label="Current password" autoComplete="current-password" {...field('current')} />
      <div className="form-grid">
        <PasswordField label="New password" autoComplete="new-password" {...field('next')} />
        <PasswordField label="Confirm new password" autoComplete="new-password" {...field('confirm')} />
      </div>
      <PasswordRules checks={checks} />
      <div className="actions"><Button type="submit" icon="key" loading={state.busy} disabled={!ready}>Change password</Button></div>
    </form>
  </Card>
}

export function Account({ api, user, onUser }) {
  return <>
    <PageHeader title="My account" description="Manage your profile and password." />
    <PhotoCard api={api} user={user} onUser={onUser} />
    <Profile api={api} user={user} onUser={onUser} />
    <Password api={api} />
    <Card title="Your access" description="Set by an administrator.">
      <p><Badge tone={user.role === 'admin' ? 'gold' : 'neutral'}>{user.role === 'admin' ? 'Admin' : 'Staff'}</Badge></p>
      <ul className="access">{PERMISSIONS.map(item => { const allowed = user.role === 'admin' || user.permissions.includes(item.key)
        return <li key={item.key} data-allowed={allowed ? '' : undefined}><Icon name={allowed ? 'check' : 'lock'} size={16} />{item.label}<span className="sr-only">{allowed ? ': allowed' : ': not allowed'}</span></li> })}</ul>
    </Card>
  </>
}
