import { useState } from 'react'
import { PERMISSIONS } from '../../auth/permissions'
import { Badge } from '../../components/Badge'
import { Button } from '../../components/Button'
import { Card } from '../../components/Card'
import { Notice } from '../../components/Notice'
import { PageHeader } from '../../components/PageHeader'
import { ResourceState } from '../../components/ResourceState'
import { useResource } from '../../hooks/useResource'
import { AccountDialog } from './AccountDialog'

const none = () => []
const summary = account => account.role === 'admin' ? 'Everything'
  : PERMISSIONS.filter(item => account.permissions.includes(item.key)).map(item => item.label).join(', ') || 'Own account only'

export function Team({ api, user }) {
  const resource = useResource(api.accounts, none)
  const [editing, setEditing] = useState(null) // null closed, 'new', or an account
  const [message, setMessage] = useState('')
  const unavailable = resource.status === 'unavailable'
  const accounts = resource.data || []
  const admins = accounts.filter(account => account.role === 'admin' && account.active)

  async function save(data, password) {
    if (editing === 'new') await api.createAccount({ ...data, password })
    else {
      await api.updateAccount(editing.id, data)
      if (password) await api.resetPassword(editing.id, { password })
    }
    setMessage(editing === 'new' ? `Account created for ${data.displayName}.` : `${data.displayName} was saved.`)
    setEditing(null)
    resource.reload()
  }

  return <>
    <PageHeader title="Accounts" description="Create and edit accounts, reset passwords, and choose what each staff member can do."
      actions={<Button icon="plus" onClick={() => { setMessage(''); setEditing('new') }}>Add account</Button>} />
    {message && <Notice tone="success"><p>{message}</p></Notice>}
    <ResourceState resource={resource}>{resource.data && <Card flush>
      {accounts.length === 0 ? <div className="empty"><strong>No accounts to show</strong><p className="muted">{unavailable ? 'Accounts will be listed here once the server supports it. You can still open the form to review it.' : 'Add the first account.'}</p></div>
        : <div className="table-wrap"><table>
          <thead><tr><th scope="col">Person</th><th scope="col">Role</th><th scope="col">Access</th><th scope="col">Status</th><th scope="col"><span className="sr-only">Actions</span></th></tr></thead>
          <tbody>{accounts.map(account => <tr key={account.id}>
            <td data-label="Person"><strong>{account.displayName}{account.id === user.id && ' (you)'}</strong><small>{account.email}</small></td>
            <td data-label="Role"><Badge tone={account.role === 'admin' ? 'gold' : 'neutral'}>{account.role === 'admin' ? 'Admin' : 'Staff'}</Badge></td>
            <td data-label="Access">{summary(account)}</td>
            <td data-label="Status"><Badge tone={account.active ? 'success' : 'muted'}>{account.active ? 'Active' : 'Inactive'}</Badge></td>
            <td className="cell-action"><Button variant="secondary" onClick={() => { setMessage(''); setEditing(account) }}>Edit<span className="sr-only"> {account.displayName}</span></Button></td>
          </tr>)}</tbody>
        </table></div>}
    </Card>}</ResourceState>
    {editing && <AccountDialog account={editing === 'new' ? null : editing} self={editing !== 'new' && editing.id === user.id}
      lastAdmin={editing !== 'new' && editing.role === 'admin' && editing.active && admins.length === 1}
      disabled={unavailable} onSubmit={save} onClose={() => setEditing(null)} />}
  </>
}
