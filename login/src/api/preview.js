// DEVELOPMENT PREVIEW ONLY. Loaded when the dev server URL contains ?preview, and left out of production builds.
// Sample data lives in memory. Passwords are never stored or checked.
import { catalogServices } from '../lib/catalog'
import { businessDay, zonedInstant } from '../lib/dates'
import { defaultHolidays, defaultSchedule } from '../lib/schedule'
import { defaultSettings } from '../lib/settings'

const wait = value => new Promise(resolve => setTimeout(() => resolve(structuredClone(value)), 250))
const fail = (status, message) => Promise.reject(Object.assign(new Error(message), { status }))
const inDays = (days, hour) => zonedInstant(businessDay(Date.now() + days * 86400000), hour)

export function createPreviewApi() {
  let current = null
  let schedule = defaultSchedule()
  let holidays = defaultHolidays()
  let settings = defaultSettings()
  const accounts = [
    { id: 1, displayName: 'Preview Admin', email: 'admin@preview.test', role: 'admin', permissions: [], active: true, canManageMigrations: true },
    { id: 2, displayName: 'Preview Staff', email: 'staff@preview.test', role: 'staff', permissions: ['bookings.view'], active: true },
  ]
  const bookings = [
    [1, 10, 'Sample Client A', 'Family & Household Stability'], [1, 14, 'Sample Client B', 'Financial Stability'],
    [3, 9, 'Sample Client C', 'General / not sure yet'], [6, 11, 'Sample Client D', 'Release & Reentry Preparation'],
  ].map(([days, hour, name, serviceTitle], index) => ({ reference: 'SAMPLE' + String(index + 1).padStart(18, '0'), startsAt: inDays(days, hour), name, serviceTitle,
    email: `client${index + 1}@example.com`, phone: '(555) 010-010' + index, visitor_timezone: 'America/New_York' }))
  const intakes = [{ id: 1, appointment_ref: bookings[0].reference, created_at: inDays(-1, 16) }, { id: 2, appointment_ref: bookings[1].reference, created_at: inDays(-2, 12) }]
  const clients = [
    ...bookings.map((item, index) => ({ id: index + 1, name: item.name, email: item.email, phone: item.phone, status: index ? 'new' : 'active' })),
    { id: 5, name: 'Sample Client E', email: 'client5@example.com', phone: '', status: 'closed' },
  ]
  const clientEmailTaken = (email, id) => clients.some(client => client.id !== id && client.email.toLowerCase() === email.toLowerCase())
  const services = catalogServices()
  let drafts = [{ id: 1, to: 'client1@example.com', subject: 'Your discovery call', body: 'Sample draft.' }]
  const contracts = [
    { id: 1, title: 'Sample readiness agreement', clientName: 'Sample Client A', clientEmail: 'client1@example.com', serviceId: 'private-readiness-life-planning', amount: 100, status: 'signed', startsOn: '2026-11-02' },
    { id: 2, title: 'Sample support proposal', clientName: 'Sample Client B', clientEmail: 'client2@example.com', serviceId: 'financial-stability', amount: null, status: 'draft', startsOn: '' },
  ]
  const nextId = list => Math.max(0, ...list.map(item => item.id)) + 1
  const find = id => accounts.find(account => account.id === id)
  const emailTaken = (email, id) => accounts.some(account => account.id !== id && account.email.toLowerCase() === email.toLowerCase())

  return {
    preview: true,
    session: () => wait({ user: current && find(current) }),
    login: ({ email }) => {
      const account = accounts.find(item => item.email === email.toLowerCase() && item.active)
      if (!account) return fail(401, 'Email or password was incorrect.')
      current = account.id
      return wait({ user: account })
    },
    logout: () => { current = null; return wait({ user: null }) },
    bookings: () => wait(bookings),
    intakes: () => wait(intakes),
    transactions: () => wait([
      [1, 1, 'Readiness Blueprint', 47, 'paid', 'Card'], [2, 2, 'Group workshop', 497, 'pending', ''], [3, 3, 'Support program', 997, 'paid', 'Card'],
      [4, 9, 'Readiness consultation', 397, 'pending', ''], [5, 12, 'Readiness Blueprint', 47, 'refunded', 'Card'], [6, 15, 'Group workshop', 497, 'failed', 'Card'], [7, 20, 'Readiness Blueprint', 0.1, 'paid', 'Card'],
    ].map(([id, daysAgo, description, amount, status, method]) => ({ id, reference: 'SAMPLE-TX-' + id, clientName: 'Sample Client ' + 'ABCDEFG'[id - 1], clientEmail: `client${id}@example.com`,
      description, amount, status, method, createdAt: new Date(Date.now() - daysAgo * 86400000 - 3600000).toISOString() }))),
    auditLogs: () => wait([
      ['sign-in', 'Signed in', 'Preview Admin', 'Preview Admin', 0],
      ['account', 'Changed access', 'Preview Staff', 'Preview Admin', 1],
      ['settings', 'Updated the schedule', 'Weekly hours', 'Preview Admin', 2],
      ['data', 'Opened an intake', bookings[0].reference, 'Preview Staff', 3],
      ['sign-in', 'Failed sign-in', '=HYPERLINK("x")', 'Unknown', 9],
    ].map(([category, action, target, actor, daysAgo], index) => ({ id: index + 1, category, action, target, actor, ip: '203.0.113.' + (index + 10), at: new Date(Date.now() - daysAgo * 86400000 - 3600000).toISOString() }))),
    services: () => wait(services),
    saveService: (id, data) => { Object.assign(services.find(item => item.id === id), data); return wait({ saved: true }) },
    contracts: () => wait(contracts),
    createContract: data => { contracts.push({ ...data, id: nextId(contracts) }); return wait({ created: true }) },
    updateContract: (id, data) => { Object.assign(contracts.find(item => item.id === id), data); return wait({ saved: true }) },
    drafts: () => wait(drafts),
    saveDraft: draft => {
      const stored = { ...draft, id: draft.id || nextId(drafts) }
      drafts = [stored, ...drafts.filter(item => item.id !== stored.id)]
      return wait(stored)
    },
    deleteDraft: id => { drafts = drafts.filter(item => item.id !== id); return wait({ deleted: true }) },
    clients: () => wait(clients),
    createClient: data => {
      if (clientEmailTaken(data.email)) return fail(422, 'Another client already uses that email.')
      clients.push({ ...data, id: Math.max(...clients.map(client => client.id)) + 1 })
      return wait({ created: true })
    },
    updateClient: (id, data) => {
      if (clientEmailTaken(data.email, id)) return fail(422, 'Another client already uses that email.')
      Object.assign(clients.find(client => client.id === id), data)
      return wait({ saved: true })
    },
    intake: () => wait({ answers: { first_name: 'Sample', concerns: ['Family stability', 'Business continuity'], consent_1: true },
      labels: { first_name: 'First name', concerns: 'Areas of concern', consent_1: 'Consent' }, attachments: 1 }),
    attachmentUrl: () => '#preview-attachment',
    uploadAvatar: (_blob, url) => { find(current).avatarUrl = url; return wait({ avatarUrl: url }) },
    removeAvatar: () => { find(current).avatarUrl = ''; return wait({ removed: true }) },
    updateProfile: ({ displayName, email }) => {
      if (emailTaken(email, current)) return fail(422, 'Another account already uses that email.')
      Object.assign(find(current), { displayName, email })
      return wait({ user: find(current) })
    },
    changePassword: () => wait({ changed: true }),
    accounts: () => wait(accounts),
    createAccount: ({ password: _password, ...data }) => {
      if (emailTaken(data.email)) return fail(422, 'Another account already uses that email.')
      accounts.push({ ...data, id: Math.max(...accounts.map(item => item.id)) + 1 })
      return wait({ created: true })
    },
    updateAccount: (id, data) => {
      if (emailTaken(data.email, id)) return fail(422, 'Another account already uses that email.')
      Object.assign(find(id), data)
      return wait({ saved: true })
    },
    resetPassword: () => wait({ changed: true }),
    settings: () => wait(settings),
    saveSettings: next => { settings = structuredClone(next); return wait({ saved: true }) },
    migrations: () => wait({ database: 'Sample database', environment: 'Preview', hasHistory: true, migrations: [
      { name: '2026_11_28_000001_create_phelon_booking_and_staff_tables', batch: 1, status: 'ran' },
      { name: 'sample_pending_migration_for_preview', batch: null, status: 'pending' },
    ] }),
    schedule: () => wait(schedule),
    saveSchedule: next => { schedule = structuredClone(next); return wait({ saved: true }) },
    holidays: () => wait(holidays),
    saveHolidays: next => { holidays = structuredClone(next); return wait({ saved: true }) },
  }
}
