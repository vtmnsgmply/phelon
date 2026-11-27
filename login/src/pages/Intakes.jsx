import { useState } from 'react'
import { can } from '../auth/permissions'
import { Button } from '../components/Button'
import { Card } from '../components/Card'
import { EmptyState } from '../components/EmptyState'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { formatDateTime } from '../lib/dates'

const show = value => Array.isArray(value) ? value.join(', ') : typeof value === 'boolean' ? (value ? 'Yes' : 'No') : String(value ?? '')

export function Intakes({ api, user }) {
  const resource = useResource(api.intakes)
  const [detail, setDetail] = useState(null)
  const [opening, setOpening] = useState(null)
  const [error, setError] = useState('')
  async function open(item) {
    setError(''); setOpening(item.id)
    try { setDetail({ ...(await api.intake(item.id)), id: item.id, reference: item.appointment_ref }) }
    catch (failure) { if (failure.status !== 401) setError(failure.message) }
    finally { setOpening(null) }
  }
  return <>
    <PageHeader title="Readiness intakes" description="Confidential answers submitted before a call."
      actions={<Button variant="secondary" icon="refresh" onClick={() => { setDetail(null); resource.reload() }}>Refresh</Button>} />
    {error && <Notice tone="danger"><p>{error}</p></Notice>}
    <ResourceState resource={resource}>{resource.data && <div className="intake-layout">
      <Card flush className="intake-list">
        {resource.data.length === 0 ? <EmptyState icon="intakes" title="No intakes yet" />
          : <ul className="rows">{resource.data.map(item => <li key={item.id}>
            <div><strong>{formatDateTime(item.created_at)}</strong><code>{item.appointment_ref}</code></div>
            <Button variant={detail?.id === item.id ? 'primary' : 'secondary'} loading={opening === item.id} onClick={() => open(item)}>Review<span className="sr-only"> intake {item.appointment_ref}</span></Button>
          </li>)}</ul>}
      </Card>
      {detail ? <Card className="intake-print" title="Submitted answers" description={detail.reference}
        actions={<><Button variant="secondary" icon="print" onClick={() => window.print()}>Print or save as PDF</Button><Button variant="ghost" onClick={() => setDetail(null)}>Close</Button></>}>
        <dl className="answers">{Object.entries(detail.answers).map(([key, value]) => <div key={key}><dt>{detail.labels?.[key] || key}</dt><dd>{show(value)}</dd></div>)}</dl>
        {detail.attachments > 0 && (can(user, 'intakes.files')
          ? <ul className="attachments">{Array.from({ length: detail.attachments }, (_, index) => <li key={index}><a href={api.attachmentUrl(detail.id, index)}>Download attachment {index + 1}</a></li>)}</ul>
          : <Notice><p>{detail.attachments} attachment(s). Your access doesn’t include downloading files.</p></Notice>)}
      </Card> : <Card className="intake-empty"><EmptyState icon="intakes" title="Choose an intake to review" /></Card>}
    </div>}</ResourceState>
  </>
}
