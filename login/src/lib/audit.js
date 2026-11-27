export const CATEGORIES = [['sign-in', 'Sign-in'], ['account', 'Accounts'], ['booking', 'Bookings'], ['client', 'Clients'], ['settings', 'Settings'], ['data', 'Data access']]
export const categoryName = value => CATEGORIES.find(([id]) => id === value)?.[1] ?? value

export const auditRows = entries => [['Time (UTC)', 'Person', 'Category', 'Action', 'Item', 'IP address'],
  ...entries.map(item => [item.at, item.actor, categoryName(item.category), item.action, item.target, item.ip])]
