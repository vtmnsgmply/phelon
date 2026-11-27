import { useState } from 'react'
import { Badge } from '../../components/Badge'
import { Button } from '../../components/Button'
import { Card } from '../../components/Card'
import { EmptyState } from '../../components/EmptyState'
import { Field } from '../../components/Field'
import { PageHeader } from '../../components/PageHeader'
import { ResourceState } from '../../components/ResourceState'
import { useResource } from '../../hooks/useResource'
import { downloadCsv } from '../../lib/csv'
import { businessDay, formatDateTime } from '../../lib/dates'
import { mailto } from '../../lib/email'
import { age, financeRows, statusName, STATUSES, VIEWS } from '../../lib/finance'
import { money } from '../../lib/money'

const none = () => []
const reminder = item => mailto({ to: item.clientEmail, subject: `Payment reminder: ${item.description}`,
  body: `Hello ${item.clientName},\n\nOur records show a payment of ${money(item.amount)} for ${item.description} (reference ${item.reference}) is still pending. If you have already paid, please reply so we can check.\n\nThe PHELONPRENEUR Network` })

// Read only: payment records come from the server and aren't changed here.
export function FinancePage({ api, view }) {
  const config = VIEWS[view]
  const resource = useResource(api.transactions, none)
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState('')
  const [from, setFrom] = useState('')
  const [to, setTo] = useState('')
  const needle = search.trim().toLowerCase()
  const rows = (resource.data || []).filter(item => {
    const day = businessDay(item.createdAt)
    return config.statuses.includes(item.status) && (!status || item.status === status) && (!from || day >= from) && (!to || day <= to)
      && (!needle || [item.reference, item.clientName, item.clientEmail, item.description].join(' ').toLowerCase().includes(needle))
  }).sort((a, b) => (config.oldestFirst ? 1 : -1) * a.createdAt.localeCompare(b.createdAt))
  const connected = resource.status === 'ready'

  return <>
    <PageHeader title={config.title} description={config.description}
      actions={<><Button variant="secondary" icon="refresh" onClick={resource.reload}>Refresh</Button>
        <Button variant="secondary" icon="download" disabled={!rows.length} onClick={() => downloadCsv(view, financeRows(rows))}>Export CSV</Button></>} />
    <ResourceState resource={resource} unavailableText="The server doesn’t provide payment records yet, so there are no figures to show.">
      {resource.data && <>
        {/* Figures follow the filters. Without a connection they are unknown, not zero. */}
        <dl className="figures">{config.figures(rows).map(([label, value]) => <div className="stat" key={label}><dt className="muted">{label}</dt><dd className="stat-value">{connected ? value : '—'}</dd></div>)}</dl>
        <Card flush>
          <div className="toolbar">
            <Field label="Search records">{props => <input {...props} type="search" placeholder="Reference, client or description" value={search} onChange={event => setSearch(event.target.value)} />}</Field>
            {config.statuses.length > 1 && <Field label="Status">{props => <select {...props} value={status} onChange={event => setStatus(event.target.value)}><option value="">All</option>{config.statuses.map(value => <option key={value} value={value}>{statusName(value)}</option>)}</select>}</Field>}
            <Field label="From">{props => <input {...props} type="date" value={from} max={to || undefined} onChange={event => setFrom(event.target.value)} />}</Field>
            <Field label="To">{props => <input {...props} type="date" value={to} min={from || undefined} onChange={event => setTo(event.target.value)} />}</Field>
          </div>
          {rows.length === 0 ? <EmptyState icon={view} title={resource.data.length ? 'No records match' : 'No payment records'} />
            : <div className="table-wrap"><table>
              <thead><tr><th scope="col">Date</th><th scope="col">Client</th><th scope="col">Description</th><th scope="col">Amount</th><th scope="col">Status</th>{view === 'pending' && <th scope="col"><span className="sr-only">Actions</span></th>}</tr></thead>
              <tbody>{rows.map(item => <tr key={item.id}>
                <td data-label="Date"><time dateTime={item.createdAt}>{formatDateTime(item.createdAt)}</time>{view === 'pending' && <small>{age(item)} days waiting</small>}</td>
                <td data-label="Client"><strong>{item.clientName}</strong><small>{item.clientEmail}</small></td>
                <td data-label="Description">{item.description}<small>{item.reference}{item.method ? ` · ${item.method}` : ''}</small></td>
                <td data-label="Amount"><strong>{money(item.amount)}</strong></td>
                <td data-label="Status"><Badge tone={STATUSES[item.status]?.[1]}>{statusName(item.status)}</Badge></td>
                {view === 'pending' && <td className="cell-action"><Button variant="secondary" icon="mail" href={reminder(item)}>Email reminder<span className="sr-only"> to {item.clientName}</span></Button></td>}
              </tr>)}</tbody>
            </table></div>}
        </Card>
      </>}
    </ResourceState>
  </>
}

export const Transactions = props => <FinancePage {...props} view="transactions" />
export const Payments = props => <FinancePage {...props} view="payments" />
export const Pending = props => <FinancePage {...props} view="pending" />
