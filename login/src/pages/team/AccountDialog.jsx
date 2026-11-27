import { useState } from 'react'
import { PERMISSIONS } from '../../auth/permissions'
import { Button } from '../../components/Button'
import { Dialog } from '../../components/Dialog'
import { Field } from '../../components/Field'
import { Notice } from '../../components/Notice'
import { PasswordField } from '../../components/PasswordField'
import { PasswordRules } from '../../components/PasswordRules'
import { Switch } from '../../components/Switch'
import { generatePassword, passwordChecks } from '../../lib/password'

// A new staff account starts with the least access.
const blank = { displayName: '', email: '', role: 'staff', permissions: ['bookings.view'], active: true }

export function AccountDialog({ account, self, lastAdmin, disabled, onSubmit, onClose }) {
  const creating = !account
  const [form, setForm] = useState(() => ({ ...blank, ...account }))
  const [password, setPassword] = useState({ next: '', confirm: '' })
  const [generated, setGenerated] = useState(false)
  const [busy, setBusy] = useState(false)
  const [error, setError] = useState('')
  const set = (key, value) => setForm(previous => ({ ...previous, [key]: value }))
  const checks = passwordChecks(password.next, password.confirm)
  const passwordReady = checks.every(([, met]) => met)
  const locked = self || lastAdmin

  function toggle(key, on) {
    setForm(previous => {
      const next = new Set(previous.permissions)
      if (on) { next.add(key); const needs = PERMISSIONS.find(item => item.key === key).requires; if (needs) next.add(needs) }
      else { next.delete(key); PERMISSIONS.filter(item => item.requires === key).forEach(item => next.delete(item.key)) }
      return { ...previous, permissions: [...next] }
    })
  }
  function suggest() { const value = generatePassword(); setPassword({ next: value, confirm: value }); setGenerated(true) }

  async function submit(event) {
    event.preventDefault()
    setError('')
    const wantsPassword = creating || password.next || password.confirm
    if (wantsPassword && !passwordReady) { setError('The password doesn’t meet the requirements yet.'); return }
    setBusy(true)
    const { id: _id, ...data } = form
    try { await onSubmit({ ...data, displayName: form.displayName.trim(), email: form.email.trim().toLowerCase(), permissions: form.role === 'admin' ? [] : form.permissions }, wantsPassword ? password.next : '') }
    catch (failure) { setError(failure.message); setBusy(false) }
  }

  return <Dialog title={creating ? 'Add account' : `Edit ${account.displayName}`} onClose={onClose}>
    <form className="form dialog-body" onSubmit={submit}>
      {error && <Notice tone="danger"><p>{error}</p></Notice>}
      <div className="form-grid">
        <Field label="Display name">{props => <input {...props} required maxLength={100} autoComplete="off" value={form.displayName} onChange={event => set('displayName', event.target.value)} />}</Field>
        <Field label="Work email">{props => <input {...props} type="email" required maxLength={254} autoComplete="off" value={form.email} onChange={event => set('email', event.target.value)} />}</Field>
      </div>
      <Field label="Role" hint={locked ? (self ? 'You can’t change your own role.' : 'This is the only active administrator.') : 'Administrators can use every function.'}>{props =>
        <select {...props} value={form.role} disabled={locked} onChange={event => set('role', event.target.value)}><option value="staff">Staff</option><option value="admin">Admin</option></select>}</Field>
      <fieldset className="permissions">
        <legend>Staff access</legend>
        {form.role === 'admin' ? <p className="muted">Administrators have full access.</p>
          : PERMISSIONS.map(item => <Switch key={item.key} label={item.label} help={item.help} checked={form.permissions.includes(item.key)} onChange={on => toggle(item.key, on)} />)}
      </fieldset>
      <Switch label="Account is active" help={locked ? (self ? 'You can’t deactivate your own account.' : 'At least one administrator must stay active.') : 'Inactive accounts can’t sign in.'} checked={form.active} disabled={locked} onChange={value => set('active', value)} />
      {!self && <fieldset className="permissions">
        <legend>{creating ? 'Temporary password' : 'Reset password (optional)'}</legend>
        <div className="form-grid">
          <PasswordField label="New password" autoComplete="new-password" maxLength={72} required={creating} value={password.next} onChange={event => { setGenerated(false); setPassword({ ...password, next: event.target.value }) }} />
          <PasswordField label="Confirm password" autoComplete="new-password" maxLength={72} required={creating} value={password.confirm} onChange={event => setPassword({ ...password, confirm: event.target.value })} />
        </div>
        {(creating || password.next || password.confirm) && <PasswordRules checks={checks} />}
        <div className="actions"><Button variant="secondary" icon="key" onClick={suggest}>Generate a strong password</Button></div>
        {generated && <p className="generated">Copy it now and share it securely: <code>{password.next}</code></p>}
      </fieldset>}
      <div className="dialog-foot"><Button variant="secondary" onClick={onClose}>Cancel</Button>
        <Button type="submit" loading={busy} disabled={disabled}>{creating ? 'Create account' : 'Save account'}</Button></div>
    </form>
  </Dialog>
}
