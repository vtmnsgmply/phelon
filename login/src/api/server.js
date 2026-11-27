// The real backend. Endpoints marked "needed" are described in BACKEND-CONTRACT.md and do not exist yet:
// calling them returns 404, which the screens report as "not connected" instead of pretending to save.
import { request } from './client'

export const serverApi = {
  preview: false,
  // Existing endpoints
  session: () => request('/session', { ignore401: true }),
  login: body => request('/session', { method: 'POST', body, ignore401: true }),
  logout: () => request('/session', { method: 'DELETE' }),
  bookings: signal => request('/staff/bookings', { signal }).then(result => result.bookings),
  intakes: signal => request('/staff/intakes', { signal }).then(result => result.intakes),
  intake: id => request('/staff/intakes/' + id),
  attachmentUrl: (id, index) => `/api/staff/intakes/${id}/files/${index}`,
  migrations: signal => request('/admin/migrations', { signal }),
  runMigrations: password => request('/admin/migrations/run', { method: 'POST', body: { password } }),
  // Needed
  transactions: signal => request('/admin/finance/transactions', { signal }).then(result => result.transactions),
  auditLogs: signal => request('/admin/audit-logs', { signal }).then(result => result.entries),
  uploadAvatar: blob => {
    const body = new FormData()
    body.append('avatar', blob, 'avatar.jpg')
    return request('/account/avatar', { method: 'POST', body })
  },
  removeAvatar: () => request('/account/avatar', { method: 'DELETE' }),
  services: signal => request('/admin/services', { signal }).then(result => result.services),
  saveService: (id, body) => request('/admin/services/' + id, { method: 'PUT', body }),
  contracts: signal => request('/admin/contracts', { signal }).then(result => result.contracts),
  createContract: body => request('/admin/contracts', { method: 'POST', body }),
  updateContract: (id, body) => request('/admin/contracts/' + id, { method: 'PUT', body }),
  drafts: signal => request('/admin/email-drafts', { signal }).then(result => result.drafts),
  saveDraft: ({ id, ...body }) => request('/admin/email-drafts' + (id ? '/' + id : ''), { method: id ? 'PUT' : 'POST', body }).then(result => result.draft),
  deleteDraft: id => request('/admin/email-drafts/' + id, { method: 'DELETE' }),
  settings: signal => request('/admin/settings', { signal }).then(result => result.settings),
  saveSettings: settings => request('/admin/settings', { method: 'PUT', body: { settings } }),
  clients: signal => request('/admin/clients', { signal }).then(result => result.clients),
  createClient: body => request('/admin/clients', { method: 'POST', body }),
  updateClient: (id, body) => request('/admin/clients/' + id, { method: 'PUT', body }),
  updateProfile: body => request('/account', { method: 'PUT', body }),
  changePassword: body => request('/account/password', { method: 'PUT', body }),
  accounts: signal => request('/admin/accounts', { signal }).then(result => result.accounts),
  createAccount: body => request('/admin/accounts', { method: 'POST', body }),
  updateAccount: (id, body) => request('/admin/accounts/' + id, { method: 'PUT', body }),
  resetPassword: (id, body) => request(`/admin/accounts/${id}/password`, { method: 'PUT', body }),
  schedule: signal => request('/admin/schedule', { signal }).then(result => result.schedule),
  saveSchedule: schedule => request('/admin/schedule', { method: 'PUT', body: { schedule } }),
  holidays: signal => request('/admin/holidays', { signal }).then(result => result.holidays),
  saveHolidays: holidays => request('/admin/holidays', { method: 'PUT', body: { holidays } }),
}
