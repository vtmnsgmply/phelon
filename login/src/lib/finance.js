import { money } from './money'

export const STATUSES = { paid: ['Paid', 'success'], pending: ['Pending', 'gold'], failed: ['Failed', 'danger'], refunded: ['Refunded', 'muted'] }
export const statusName = value => STATUSES[value]?.[0] ?? value

// Added in cents, so rounding errors can't creep into totals.
export const total = list => list.reduce((sum, item) => sum + Math.round(Number(item.amount) * 100), 0) / 100
const only = (list, status) => list.filter(item => item.status === status)
const days = item => Math.max(0, Math.floor((Date.now() - Date.parse(item.createdAt)) / 86400000))

// The three finance screens share one table; each chooses its records and summary figures.
export const VIEWS = {
  transactions: {
    title: 'Transactions', description: 'Every payment record, whatever its status.', statuses: ['paid', 'pending', 'failed', 'refunded'],
    figures: list => [['Records', list.length], ['Paid', money(total(only(list, 'paid')))], ['Pending', money(total(only(list, 'pending')))], ['Refunded', money(total(only(list, 'refunded')))]],
  },
  payments: {
    title: 'Payments', description: 'Money received, including payments later refunded.', statuses: ['paid', 'refunded'],
    figures: list => [['Received', money(total(list))], ['Refunded', money(total(only(list, 'refunded')))], ['Net', money(total(only(list, 'paid')))]],
  },
  pending: {
    title: 'Pending', description: 'Payments that haven’t arrived yet, oldest first.', statuses: ['pending'], oldestFirst: true,
    figures: list => [['Waiting', list.length], ['Outstanding', money(total(list))], ['Oldest', list.length ? `${Math.max(...list.map(days))} days` : '—']],
  },
}
export const age = days

export const financeRows = entries => [['Date (UTC)', 'Reference', 'Client', 'Email', 'Description', 'Amount (USD)', 'Method', 'Status'],
  ...entries.map(item => [item.createdAt, item.reference, item.clientName, item.clientEmail, item.description, Number(item.amount), item.method, statusName(item.status)])]
