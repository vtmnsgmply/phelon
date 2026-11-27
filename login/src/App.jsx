import { useCallback, useEffect, useState } from 'react'
import { setUnauthorizedHandler } from './api/client'
import { can, normalizeUser } from './auth/permissions'
import { Button } from './components/Button'
import { Card } from './components/Card'
import { EmptyState } from './components/EmptyState'
import { Notice } from './components/Notice'
import { useHashRoute } from './hooks/useHashRoute'
import { clearUnsaved, confirmLeave } from './hooks/useUnsaved'
import { AppShell } from './layout/AppShell'
import { PAGES } from './layout/navigation'
import { Account } from './pages/Account'
import { AuditLogs } from './pages/AuditLogs'
import { Bookings } from './pages/Bookings'
import { Calendar } from './pages/Calendar'
import { Contracts } from './pages/contracts/Contracts'
import { EmailDrafts } from './pages/EmailDrafts'
import { Services } from './pages/Services'
import { Migrations } from './pages/Migrations'
import { Settings } from './pages/Settings'
import { Clients } from './pages/clients/Clients'
import { Payments, Pending, Transactions } from './pages/finance/FinancePage'
import { Holidays } from './pages/holidays/Holidays'
import { Intakes } from './pages/Intakes'
import { Overview } from './pages/Overview'
import { Schedule } from './pages/Schedule'
import { SignIn } from './pages/SignIn'
import { Team } from './pages/team/Team'

const pages = { overview: Overview, clients: Clients, bookings: Bookings, audit: AuditLogs, calendar: Calendar, transactions: Transactions, payments: Payments, pending: Pending, contracts: Contracts, email: EmailDrafts, services: Services, settings: Settings, migrations: Migrations, intakes: Intakes, schedule: Schedule, holidays: Holidays, team: Team, account: Account }

export function App({ api }) {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)
  const [notice, setNotice] = useState('')
  const [error, setError] = useState('')
  const route = useHashRoute()

  useEffect(() => {
    api.session().then(result => setUser(normalizeUser(result.user)))
      .catch(failure => { if (failure.status !== 401) setNotice('We couldn’t check your session. You can still try to sign in.') })
      .finally(() => setLoading(false))
  }, [api])
  useEffect(() => {
    setUnauthorizedHandler(() => { clearUnsaved(); setUser(null); setNotice('Your session ended. Sign in again.') })
  }, [])
  const signedIn = useCallback(raw => { setNotice(''); setError(''); history.replaceState(null, '', '#/overview'); window.dispatchEvent(new HashChangeEvent('hashchange')); setUser(normalizeUser(raw)) }, [])

  async function signOut() {
    if (!await confirmLeave()) return
    try { await api.logout(); clearUnsaved(); setUser(null); setNotice('') }
    catch (failure) { setError(`You are still signed in. ${failure.message}`) }
  }

  if (loading) return <p className="boot" role="status"><span className="spinner" aria-hidden="true" />Checking your session…</p>
  if (!user) return <SignIn api={api} notice={notice} onLogin={signedIn} />

  const entry = PAGES.find(item => item.route === route)
  const Page = entry && pages[route]
  const allowed = entry && can(user, entry.need)
  return <AppShell user={user} route={route} preview={api.preview} onSignOut={signOut}>
    {error && <Notice tone="danger"><p>{error}</p></Notice>}
    {!Page ? <Card><EmptyState title="That page doesn’t exist" /><Button href="#/overview">Go to overview</Button></Card>
      : !allowed ? <Card><h1 className="sr-only">No access</h1><EmptyState icon="lock" title="You don’t have access to this page">Ask an administrator if you need it.</EmptyState><Button href="#/overview">Go to overview</Button></Card>
        : <Page key={route} api={api} user={user} onUser={setUser} />}
  </AppShell>
}
