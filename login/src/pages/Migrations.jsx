import { useState } from 'react'
import { Badge } from '../components/Badge'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { Dialog } from '../components/Dialog'
import { EmptyState } from '../components/EmptyState'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { PasswordField } from '../components/PasswordField'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'

export function Migrations({ api }) {
  const resource = useResource(api.migrations)
  const [confirming, setConfirming] = useState(false)
  const [password, setPassword] = useState('')
  const [busy, setBusy] = useState(false)
  const [passwordError, setPasswordError] = useState('')
  const [notice, setNotice] = useState(null)
  const { database, environment, hasHistory, migrations: list = [] } = resource.data || {}
  const pending = list.filter(item => item.status === 'pending').length
  const target = `${database} (${environment})`

  function close() {
    if (busy) return
    setConfirming(false)
    setPassword('')
    setPasswordError('')
  }

  async function run(event) {
    event.preventDefault()
    if (busy || !password) return
    setBusy(true)
    setPasswordError('')
    setNotice(null)
    setPassword('')
    try {
      const { applied } = await api.runMigrations(password)
      setConfirming(false)
      setNotice({ tone: 'success', text: applied.length === 0 ? 'No migrations were pending.'
        : `Applied ${applied.length} migration${applied.length === 1 ? '' : 's'}.` })
      resource.reload()
    } catch (error) {
      if (error.status === 422 || error.status === 429) {
        setPasswordError(error.status === 429 ? 'Too many attempts. Wait a minute before trying again.' : error.message)
      } else {
        setConfirming(false)
        setNotice({ tone: 'danger', text: error.status ? error.message : 'Could not confirm the result. Check the migration list before trying again.' })
        resource.reload()
      }
    } finally {
      setBusy(false)
    }
  }

  return <>
    <PageHeader title="Migrations" description="Review and apply database changes shipped with Phelon."
      actions={<>
        <Button variant="secondary" icon="refresh" disabled={busy || resource.status === 'loading'} onClick={resource.reload}>Refresh</Button>
        {!api.preview && <Button icon="migrations" loading={busy} disabled={!hasHistory || pending === 0} onClick={() => setConfirming(true)}>Run pending migrations</Button>}
      </>} />
    {notice && <Notice tone={notice.tone}><p>{notice.text}</p></Notice>}
    {api.preview && <Notice tone="info"><p>Sample data only. Migrations can only run on the server.</p></Notice>}
    <ResourceState resource={resource}>
      {resource.data && <>
        {!hasHistory && <Notice tone="warning"><p>This database has no migration history. Complete the initial database setup before running migrations here.</p></Notice>}
        <Card flush title="Database migrations" description={`${target} · ${list.length - pending} applied, ${pending} pending`}>
          {list.length === 0 ? <EmptyState icon="migrations" title="No migration files found" />
            : <div className="table-wrap"><table>
              <thead><tr><th scope="col">Migration</th><th scope="col">Batch</th><th scope="col">Status</th></tr></thead>
              <tbody>{list.map(item => <tr key={item.name}>
                <td data-label="Migration"><code>{item.name}</code></td>
                <td data-label="Batch">{item.batch ?? '—'}</td>
                <td data-label="Status"><Badge tone={item.status === 'pending' ? 'gold' : 'success'}>{item.status === 'pending' ? 'Pending' : 'Applied'}</Badge></td>
              </tr>)}</tbody>
            </table></div>}
        </Card>
      </>}
    </ResourceState>
    {confirming && <Dialog title="Run pending migrations" onClose={close}>
      <form className="form dialog-body" onSubmit={run}>
        <p>Apply {pending} pending migration{pending === 1 ? '' : 's'} to <strong>{target}</strong>? Confirm you have a current backup before continuing.</p>
        <PasswordField label="Current password" autoComplete="current-password" name="password" maxLength={72} required disabled={busy}
          value={password} error={passwordError} onChange={event => setPassword(event.target.value)} />
        <div className="dialog-foot">
          <Button variant="secondary" disabled={busy} onClick={close}>Cancel</Button>
          <Button type="submit" loading={busy} disabled={!password}>{busy ? 'Running migrations…' : 'Confirm and run'}</Button>
        </div>
      </form>
    </Dialog>}
  </>
}
