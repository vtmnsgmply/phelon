import { can } from '../auth/permissions'
import { Card } from '../components/Card'
import { EmptyState } from '../components/EmptyState'
import { Icon } from '../components/Icon'
import { Notice } from '../components/Notice'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { useResource } from '../hooks/useResource'
import { businessDay, formatDateTime, formatDay, today } from '../lib/dates'
import { closuresFor } from '../lib/holidays'
import { defaultHolidays } from '../lib/schedule'

const PAGE = 50 // the server returns at most 50 records per list
const count = list => list.length >= PAGE ? PAGE + '+' : String(list.length)

function Stat({ icon, label, value, href }) {
  return <a className="stat" href={href}><span className="stat-icon"><Icon name={icon} /></span><span className="stat-value">{value}</span><span className="muted">{label}</span></a>
}

function Upcoming({ api }) {
  const resource = useResource(api.bookings)
  return <ResourceState resource={resource}>{resource.data && <>
    <div className="stats">
      <Stat icon="bookings" label="Upcoming bookings" value={count(resource.data)} href="#/bookings" />
      <Stat icon="check" label="Booked for today" value={resource.data.filter(item => businessDay(item.startsAt) === today()).length} href="#/bookings" />
    </div>
    <Card title="Next appointments" flush>
      {resource.data.length === 0 ? <EmptyState icon="bookings" title="No upcoming bookings">New appointments will appear here.</EmptyState>
        : <ul className="rows">{resource.data.slice(0, 5).map(item => <li key={item.reference}>
          <div><strong>{item.name}</strong><span className="muted">{item.serviceTitle}</span></div><time dateTime={item.startsAt}>{formatDateTime(item.startsAt)}</time></li>)}</ul>}
    </Card>
  </>}</ResourceState>
}

function NextClosure({ api }) {
  const resource = useResource(api.holidays, defaultHolidays)
  if (!resource.data) return null
  const now = today()
  const year = Number(now.slice(0, 4))
  const next = [...closuresFor(year, resource.data), ...closuresFor(year + 1, resource.data)].filter(([date]) => date >= now).sort(([a], [b]) => a.localeCompare(b))[0]
  return <Card title="Next closure" description={resource.status === 'unavailable' ? 'Example only: holiday settings are not connected yet.' : undefined}>
    {next ? <p className="next-closure"><strong>{next[1].name}</strong><span className="muted">{formatDay(next[0], { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })}</span></p> : <p className="muted">No closures are planned.</p>}
    <a href="#/holidays">Manage holidays</a>
  </Card>
}

export function Overview({ api, user }) {
  return <>
    <PageHeader title={`Welcome, ${user.displayName}`} description="What needs attention today." />
    {!user.rolesSupported && <Notice tone="info" title="Roles aren’t set up on the server yet"><p>{user.role === 'admin'
      ? 'You are shown as Admin because your name is listed in the app’s .env file. Admin settings can’t be saved until the server supports them.'
      : 'You are signed in as staff, with access to bookings and intakes.'}</p></Notice>}
    {can(user, 'bookings.view') ? <Upcoming api={api} /> : <Card><EmptyState icon="lock" title="Bookings aren’t part of your access">Ask an administrator if you need them.</EmptyState></Card>}
    {can(user, 'holidays.manage') && <NextClosure api={api} />}
  </>
}
