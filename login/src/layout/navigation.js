import { can } from '../auth/permissions'

// need: null = everyone, 'admin' = administrators, otherwise a permission key.
// An entry with children is a collapsible section; it shows when at least one child is allowed.
export const NAVIGATION = [
  { route: 'overview', label: 'Overview', icon: 'overview', need: null },
  { id: 'clients', label: 'Clients', icon: 'clients', children: [
    { route: 'clients', label: 'Client list', icon: 'team', need: 'clients.view' },
    { route: 'intakes', label: 'Intakes', icon: 'intakes', need: 'intakes.view' },
    { route: 'contracts', label: 'Contracts', icon: 'contracts', need: 'contracts.view' },
  ] },
  { id: 'calendar', label: 'Calendar', icon: 'calendar', children: [
    { route: 'calendar', label: 'Month view', icon: 'calendar', need: 'bookings.view' },
    { route: 'bookings', label: 'Bookings', icon: 'bookings', need: 'bookings.view' },
    { route: 'schedule', label: 'Schedule settings', icon: 'schedule', need: 'schedule.manage' },
    { route: 'holidays', label: 'Holidays', icon: 'holidays', need: 'holidays.manage' },
  ] },
  { id: 'finance', label: 'Finance', icon: 'finance', children: [
    { route: 'transactions', label: 'Transactions', icon: 'transactions', need: 'finance.view' },
    { route: 'payments', label: 'Payments', icon: 'payments', need: 'finance.view' },
    { route: 'pending', label: 'Pending', icon: 'pending', need: 'finance.view' },
  ] },
  { route: 'email', label: 'Email drafts', icon: 'mail', need: 'email.drafts' },
  { route: 'services', label: 'Services', icon: 'services', need: null },
  { route: 'team', label: 'Accounts', icon: 'team', need: 'admin', group: 'Administration' },
  { route: 'settings', label: 'Settings', icon: 'settings', need: 'admin', group: 'Administration' },
  { route: 'audit', label: 'Audit logs', icon: 'audit', need: 'admin', group: 'Administration' },
  { route: 'migrations', label: 'Migrations', icon: 'migrations', need: 'migrations.manage', group: 'Administration' },
  { route: 'account', label: 'My account', icon: 'account', need: null, group: 'You' },
]

// Every screen, with sections flattened.
export const PAGES = NAVIGATION.flatMap(item => item.children || [item])

export const navigationFor = user => NAVIGATION.flatMap(item => {
  if (!item.children) return can(user, item.need) ? [item] : []
  const children = item.children.filter(child => can(user, child.need))
  return children.length ? [{ ...item, children }] : []
})
