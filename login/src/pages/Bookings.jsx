import { useState } from 'react'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { EmptyState } from '../components/EmptyState'
import { Field } from '../components/Field'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { formatDateTime } from '../lib/dates'
import { zoneLabel } from '../lib/timezone'

export function Bookings({ api }) {
  const resource = useResource(api.bookings)
  const [search, setSearch] = useState('')
  const needle = search.trim().toLowerCase()
  const rows = (resource.data || []).filter(item => !needle || [item.name, item.email, item.phone, item.serviceTitle, item.reference].join(' ').toLowerCase().includes(needle))
  return <>
    <PageHeader title="Bookings" description={`Upcoming discovery calls, soonest first. Times are in ${zoneLabel()}.`}
      actions={<Button variant="secondary" icon="refresh" onClick={resource.reload}>Refresh</Button>} />
    <ResourceState resource={resource}>{resource.data && <Card flush>
      <div className="toolbar"><Field label="Search bookings">{props => <input {...props} type="search" placeholder="Name, email, phone, service or reference" value={search} onChange={event => setSearch(event.target.value)} />}</Field>
        <p className="muted" role="status">{rows.length} of {resource.data.length} shown{resource.data.length >= 50 ? ' (the first 50 upcoming)' : ''}</p></div>
      {rows.length === 0 ? <EmptyState icon="bookings" title={needle ? 'No bookings match your search' : 'No upcoming bookings'} />
        : <div className="table-wrap"><table>
          <thead><tr><th scope="col">When</th><th scope="col">Client</th><th scope="col">Service</th><th scope="col">Contact</th><th scope="col">Reference</th></tr></thead>
          <tbody>{rows.map(item => <tr key={item.reference}>
            <td data-label="When"><time dateTime={item.startsAt}>{formatDateTime(item.startsAt)}</time><small>{item.visitor_timezone}</small></td>
            <td data-label="Client"><strong>{item.name}</strong></td>
            <td data-label="Service">{item.serviceTitle}</td>
            <td data-label="Contact"><a href={'mailto:' + item.email}>{item.email}</a><a href={'tel:' + String(item.phone).replace(/[^+0-9]/g, '')}>{item.phone}</a></td>
            <td data-label="Reference"><code>{item.reference}</code></td>
          </tr>)}</tbody>
        </table></div>}
    </Card>}</ResourceState>
  </>
}
