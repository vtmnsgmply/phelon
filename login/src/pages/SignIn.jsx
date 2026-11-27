import { useEffect, useState } from 'react'
import { Button } from '../components/Button'
import { Field } from '../components/Field'
import { Icon } from '../components/Icon'
import { Notice } from '../components/Notice'
import { PasswordField } from '../components/PasswordField'

const base = import.meta.env.BASE_URL

function explain(error) {
  if (error.status === 429) return 'Too many sign-in attempts. Wait a minute, then try again.'
  if (!error.status) return 'We couldn’t reach the server. Check your connection and try again.'
  return error.message
}

export function SignIn({ api, notice, onLogin }) {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [busy, setBusy] = useState(false)
  useEffect(() => { document.title = 'Sign in | Staff back office' }, [])

  async function submit(address, secret) {
    if (busy) return
    setBusy(true); setError('')
    setPassword('')
    try { onLogin((await api.login({ email: address, password: secret })).user) }
    catch (failure) { setError(explain(failure)) }
    finally { setBusy(false) }
  }

  return <div className="signin">
    <aside className="signin-aside">
      <img className="signin-photo" src={base + 'team.webp'} alt="" />
      <div className="signin-aside-body">
        <h2>A quiet space for the work behind the call.</h2>
        <ul>
          <li><Icon name="shield" />For authorized Phelonpreneur staff only</li>
          <li><Icon name="lock" />Sessions end automatically after 8 hours</li>
          <li><Icon name="info" />Client information is confidential</li>
        </ul>
      </div>
    </aside>
    <main className="signin-main">
      <div className="signin-box">
        <span className="brand"><img src={base + 'logo-mark.webp'} alt="" width="48" height="48" /><span>Phelonpreneur<small>Back office</small></span></span>
        <div><h1>Sign in</h1><p className="muted">Use your Phelonpreneur staff account.</p></div>
        {notice && !error && <Notice tone="warning"><p>{notice}</p></Notice>}
        {error && <Notice tone="danger"><p>{error}</p></Notice>}
        <form className="form" onSubmit={event => { event.preventDefault(); submit(email, password) }}>
          <Field label="Work email">{props => <input {...props} type="email" name="email" autoComplete="username" maxLength={254} required value={email} onChange={event => setEmail(event.target.value)} />}</Field>
          <PasswordField label="Password" name="password" autoComplete="current-password" maxLength={72} required value={password} onChange={event => setPassword(event.target.value)} />
          <Button type="submit" className="btn--block" loading={busy}>{busy ? 'Signing in…' : 'Sign in'}</Button>
        </form>
        <p className="hint">Forgot your password? Ask your administrator to reset it.</p>
        {import.meta.env.DEV && api.preview && <div className="preview-box">
          <p><strong>Preview mode.</strong> Sample data only; no password is checked.</p>
          <div className="actions">
            <Button variant="secondary" onClick={() => submit('admin@preview.test', '')}>Preview as admin</Button>
            <Button variant="secondary" onClick={() => submit('staff@preview.test', '')}>Preview as staff</Button>
          </div>
        </div>}
        <a className="back" href="/"><Icon name="previous" size={16} />Return to the website</a>
      </div>
    </main>
  </div>
}
