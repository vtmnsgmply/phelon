import { useState } from 'react'
import { can } from '../../auth/permissions'
import { Badge } from '../../components/Badge'
import { Button } from '../../components/Button'
import { Card } from '../../components/Card'
import { EmptyState } from '../../components/EmptyState'
import { Field } from '../../components/Field'
import { Notice } from '../../components/Notice'
import { PageHeader } from '../../components/PageHeader'
import { ResourceState } from '../../components/ResourceState'
import { useResource } from '../../hooks/useResource'
import { catalogServices } from '../../lib/catalog'
import { CONTRACT_STATUS, statusOf } from '../../lib/contracts'
import { formatDay } from '../../lib/dates'
import { money } from '../../lib/money'
import { ContractDialog } from './ContractDialog'

const none = () => []

export function Contracts({ api, user }) {
  const resource = useResource(api.contracts, none)
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState('')
  const [open, setOpen] = useState(null) // null closed, 'new', or a contract
  const [message, setMessage] = useState('')
  const canEdit = can(user, 'contracts.manage')
  const connected = resource.status === 'ready'
  const services = Object.fromEntries(catalogServices().map(item => [item.id, item.title]))
  const needle = search.trim().toLowerCase()
  const rows = (resource.data || []).filter(item => (!status || item.status === status) && (!needle || [item.title, item.clientName, item.clientEmail].join(' ').toLowerCase().includes(needle)))

  async function save(values) {
    if (open === 'new') await api.createContract(values)
    else await api.updateContract(open.id, values)
    setMessage(open === 'new' ? `${values.title} was added.` : `${values.title} was saved.`)
    setOpen(null); resource.reload()
  }

  return <>
    <PageHeader title="Contracts" description="Service agreements and where each one stands."
      actions={canEdit && <Button icon="plus" disabled={!resource.data} onClick={() => { setMessage(''); setOpen('new') }}>Add contract</Button>} />
    {message && <Notice tone="success"><p>{message}</p></Notice>}
    <ResourceState resource={resource} unavailableText="The server can’t store contracts yet, so none are listed and saving is turned off.">
      {resource.data && <Card flush>
        <div className="toolbar">
          <Field label="Search contracts">{props => <input {...props} type="search" placeholder="Title or client" value={search} onChange={event => setSearch(event.target.value)} />}</Field>
          <Field label="Status">{props => <select {...props} value={status} onChange={event => setStatus(event.target.value)}><option value="">All statuses</option>{CONTRACT_STATUS.map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select>}</Field>
        </div>
        {rows.length === 0 ? <EmptyState icon="contracts" title={needle || status ? 'No contracts match' : 'No contracts yet'} />
          : <div className="table-wrap"><table>
            <thead><tr><th scope="col">Contract</th><th scope="col">Client</th><th scope="col">Value</th><th scope="col">Starts</th><th scope="col">Status</th>{canEdit && <th scope="col"><span className="sr-only">Actions</span></th>}</tr></thead>
            <tbody>{rows.map(item => <tr key={item.id}>
              <td data-label="Contract"><strong>{item.title}</strong><small>{services[item.serviceId] || 'No service set'}</small></td>
              <td data-label="Client">{item.clientName}<small>{item.clientEmail}</small></td>
              <td data-label="Value">{money(item.amount)}</td>
              <td data-label="Starts">{item.startsOn ? formatDay(item.startsOn) : '—'}</td>
              <td data-label="Status"><Badge tone={statusOf(item.status)[2]}>{statusOf(item.status)[1]}</Badge></td>
              {canEdit && <td className="cell-action"><Button variant="secondary" onClick={() => { setMessage(''); setOpen(item) }}>Edit<span className="sr-only"> {item.title}</span></Button></td>}
            </tr>)}</tbody>
          </table></div>}
      </Card>}
    </ResourceState>
    {open && <ContractDialog contract={open === 'new' ? null : open} connected={connected} onSubmit={save} onClose={() => setOpen(null)} />}
  </>
}
