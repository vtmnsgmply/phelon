import { useState } from 'react'
import { Badge } from '../components/Badge'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { EmptyState } from '../components/EmptyState'
import { Field } from '../components/Field'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { auditRows, CATEGORIES, categoryName } from '../lib/audit'
import { downloadCsv } from '../lib/csv'
import { businessDay, formatDateTime } from '../lib/dates'

const none = () => []

// Read only: entries are written by the server and can't be changed from here.
export function AuditLogs({ api }) {
  const resource = useResource(api.auditLogs, none)
  const [search, setSearch] = useState('')
  const [category, setCategory] = useState('')
  const [from, setFrom] = useState('')
  const [to, setTo] = useState('')
  const needle = search.trim().toLowerCase()
  const rows = (resource.data || []).filter(item => {
    const day = businessDay(item.at)
    return (!category || item.category === category) && (!from || day >= from) && (!to || day <= to)
      && (!needle || [item.actor, item.action, item.target, item.ip].join(' ').toLowerCase().includes(needle))
  })
  return <>
    <PageHeader title="Audit logs" description="Who did what, and when. Newest first."
      actions={<><Button variant="secondary" icon="refresh" onClick={resource.reload}>Refresh</Button>
        <Button variant="secondary" icon="download" disabled={!rows.length} onClick={() => downloadCsv('audit-log', auditRows(rows))}>Export CSV</Button></>} />
    <ResourceState resource={resource} unavailableText="The server doesn’t record an audit log yet, so there are no entries to show.">
      {resource.data && <Card flush>
        <div className="toolbar">
          <Field label="Search the log">{props => <input {...props} type="search" placeholder="Person, action, item or IP" value={search} onChange={event => setSearch(event.target.value)} />}</Field>
          <Field label="Category">{props => <select {...props} value={category} onChange={event => setCategory(event.target.value)}><option value="">All categories</option>{CATEGORIES.map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select>}</Field>
          <Field label="From">{props => <input {...props} type="date" value={from} max={to || undefined} onChange={event => setFrom(event.target.value)} />}</Field>
          <Field label="To">{props => <input {...props} type="date" value={to} min={from || undefined} onChange={event => setTo(event.target.value)} />}</Field>
          <p className="muted" role="status">{rows.length} of {resource.data.length} entries</p>
        </div>
        {rows.length === 0 ? <EmptyState icon="audit" title={resource.data.length ? 'No entries match these filters' : 'No audit entries'} />
          : <div className="table-wrap"><table>
            <thead><tr><th scope="col">When</th><th scope="col">Person</th><th scope="col">Category</th><th scope="col">Action</th><th scope="col">IP address</th></tr></thead>
            <tbody>{rows.map(item => <tr key={item.id}>
              <td data-label="When"><time dateTime={item.at}>{formatDateTime(item.at)}</time></td>
              <td data-label="Person"><strong>{item.actor}</strong></td>
              <td data-label="Category"><Badge>{categoryName(item.category)}</Badge></td>
              <td data-label="Action">{item.action}<small>{item.target}</small></td>
              <td data-label="IP"><code>{item.ip || '—'}</code></td>
            </tr>)}</tbody>
          </table></div>}
      </Card>}
    </ResourceState>
  </>
}
