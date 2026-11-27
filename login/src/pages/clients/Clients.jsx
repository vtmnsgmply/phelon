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
import { bookingsFor, CLIENT_STATUS, clientsFromBookings } from '../../lib/clients'
import { formatDateTime } from '../../lib/dates'
import { ClientDialog } from './ClientDialog'

const MISSING = [404, 405, 501]
const statusName = Object.fromEntries(CLIENT_STATUS)

export function Clients({ api, user }) {
  const canSeeBookings = can(user, 'bookings.view')
  const canEdit = can(user, 'clients.manage')
  const resource = useResource(async signal => {
    // Bookings and intakes are only requested when this person may see them.
    const [bookings, intakes] = await Promise.all([
      canSeeBookings ? api.bookings(signal) : [],
      can(user, 'intakes.view') ? api.intakes(signal) : null,
    ])
    const intakeRefs = intakes && new Set(intakes.map(item => item.appointment_ref))
    try { return { connected: true, clients: await api.clients(signal), bookings, intakeRefs } }
    catch (error) {
      if (!MISSING.includes(error.status)) throw error
      return { connected: false, clients: clientsFromBookings(bookings), bookings, intakeRefs }
    }
  })
  const [search, setSearch] = useState('')
  const [open, setOpen] = useState(null) // null closed, 'new', or a client
  const [message, setMessage] = useState('')
  const data = resource.data
  const needle = search.trim().toLowerCase()
  const rows = (data?.clients || []).filter(client => !needle || [client.name, client.email, client.phone].join(' ').toLowerCase().includes(needle))

  async function save(values) {
    if (open === 'new') await api.createClient(values)
    else await api.updateClient(open.id, values)
    setMessage(open === 'new' ? `${values.name} was added.` : `${values.name} was saved.`)
    setOpen(null)
    resource.reload()
  }

  return <>
    <PageHeader title="Clients" description="People you are supporting and their upcoming appointments."
      actions={<><Button variant="secondary" icon="refresh" onClick={resource.reload}>Refresh</Button>
        {canEdit && <Button icon="plus" disabled={!data} onClick={() => { setMessage(''); setOpen('new') }}>Add client</Button>}</>} />
    {message && <Notice tone="success"><p>{message}</p></Notice>}
    <ResourceState resource={resource}>{data && <>
      {!data.connected && <Notice tone="warning" title="Client records aren’t connected yet">
        <p>{canSeeBookings ? 'This list is built from upcoming bookings only. Past clients are not shown, and changes can’t be saved.'
          : 'The server doesn’t provide client records yet, and your access doesn’t include bookings.'}</p></Notice>}
      <Card flush>
        <div className="toolbar"><Field label="Search clients">{props => <input {...props} type="search" placeholder="Name, email or phone" value={search} onChange={event => setSearch(event.target.value)} />}</Field>
          <p className="muted" role="status">{rows.length} of {data.clients.length} shown</p></div>
        {rows.length === 0 ? <EmptyState icon="clients" title={needle ? 'No clients match your search' : 'No clients yet'} />
          : <div className="table-wrap"><table>
            <thead><tr><th scope="col">Client</th><th scope="col">Contact</th>{canSeeBookings && <th scope="col">Next appointment</th>}<th scope="col">Status</th><th scope="col"><span className="sr-only">Actions</span></th></tr></thead>
            <tbody>{rows.map(client => { const next = bookingsFor(client, data.bookings)[0]
              return <tr key={client.id}>
                <td data-label="Client"><strong>{client.name}</strong></td>
                <td data-label="Contact"><a href={'mailto:' + client.email}>{client.email}</a>{client.phone && <a href={'tel:' + String(client.phone).replace(/[^+0-9]/g, '')}>{client.phone}</a>}</td>
                {canSeeBookings && <td data-label="Next">{next ? <time dateTime={next.startsAt}>{formatDateTime(next.startsAt)}</time> : <span className="muted">None</span>}</td>}
                <td data-label="Status">{client.status ? <Badge tone={client.status === 'active' ? 'success' : client.status === 'closed' ? 'muted' : 'gold'}>{statusName[client.status]}</Badge> : <span className="muted">Not set</span>}</td>
                <td className="cell-action"><Button variant="secondary" onClick={() => { setMessage(''); setOpen(client) }}>{canEdit ? 'Edit' : 'View'}<span className="sr-only"> {client.name}</span></Button></td>
              </tr> })}</tbody>
          </table></div>}
      </Card>
    </>}</ResourceState>
    {open && data && <ClientDialog client={open === 'new' ? null : open} bookings={open === 'new' ? [] : bookingsFor(open, data.bookings)}
      intakeRefs={data.intakeRefs} canEdit={canEdit} canSeeBookings={canSeeBookings} connected={data.connected} onSubmit={save} onClose={() => setOpen(null)} />}
  </>
}
