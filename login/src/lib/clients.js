export const CLIENT_STATUS = [['new', 'New'], ['active', 'Active'], ['closed', 'Closed']]
const key = email => String(email || '').trim().toLowerCase()

// Upcoming bookings belonging to one client, matched by email.
export const bookingsFor = (client, bookings) => bookings.filter(item => key(item.email) === key(client.email))

// Stand-in list while the server has no client records: one entry per email in the upcoming bookings.
export function clientsFromBookings(bookings) {
  const people = new Map()
  for (const item of bookings) {
    if (!people.has(key(item.email))) people.set(key(item.email), { id: key(item.email), name: item.name, email: item.email, phone: item.phone })
  }
  return [...people.values()]
}
