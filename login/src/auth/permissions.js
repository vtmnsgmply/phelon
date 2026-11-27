// Controls what the interface shows. The server must enforce the same rules: hiding a screen is not security.
export const PERMISSIONS = [
  { key: 'clients.view', label: 'View clients', help: 'See client names and contact details.' },
  { key: 'clients.manage', label: 'Add and edit clients', help: 'Create and change client records. Needs "View clients".', requires: 'clients.view' },
  { key: 'bookings.view', label: 'View bookings', help: 'See upcoming appointments and client contact details.' },
  { key: 'intakes.view', label: 'Review intakes', help: 'Open submitted readiness answers.' },
  { key: 'intakes.files', label: 'Download intake files', help: 'Download attachments. Needs "Review intakes".', requires: 'intakes.view' },
  { key: 'finance.view', label: 'View finance', help: 'See transactions, payments and what is still owed.' },
  { key: 'contracts.view', label: 'View contracts', help: 'See service agreements and their value.' },
  { key: 'contracts.manage', label: 'Add and edit contracts', help: 'Change contract records. Needs "View contracts".', requires: 'contracts.view' },
  { key: 'email.drafts', label: 'Write email drafts', help: 'Prepare messages to clients.' },
  { key: 'services.manage', label: 'Edit services', help: 'Change service descriptions. Everyone can read them.' },
  { key: 'schedule.manage', label: 'Manage schedule', help: 'Change opening hours and appointment settings.' },
  { key: 'holidays.manage', label: 'Manage holidays', help: 'Choose US holidays and add closures.' },
]

// What staff see while the server sends no role: what it already allows, plus tools built on that data.
const SERVER_DEFAULT = ['email.drafts', 'clients.view', 'bookings.view', 'intakes.view', 'intakes.files']

const listedAdmins = (import.meta.env.VITE_ADMIN_NAMES || '').split(',').map(name => name.trim().toLowerCase()).filter(Boolean)

export function normalizeUser(raw) {
  if (!raw) return null
  const rolesSupported = typeof raw.role === 'string'
  // The server's role always wins. The name list is only a stand-in while there is none.
  const isAdmin = rolesSupported ? raw.role === 'admin' : listedAdmins.includes(String(raw.displayName).trim().toLowerCase())
  const role = isAdmin ? 'admin' : 'staff'
  const permissions = role === 'admin' ? PERMISSIONS.map(item => item.key)
    : Array.isArray(raw.permissions) ? raw.permissions : SERVER_DEFAULT
  return { id: raw.id ?? null, displayName: raw.displayName, email: raw.email ?? '', avatarUrl: raw.avatarUrl ?? '', role, permissions, rolesSupported, canManageMigrations: raw.canManageMigrations === true }
}

// "admin" means administrators only; null means every signed-in person.
export const can = (user, need) => need === 'migrations.manage' ? user.canManageMigrations
  : !need || user.role === 'admin' || (need !== 'admin' && user.permissions.includes(need))
