// The service list the public website is built from. Used until the server provides its own.
import services from '../../../backend/resources/catalog/services.json'

export const catalogServices = () => Object.entries(services).map(([id, item]) => ({
  id, title: item.title, description: item.description, audience: item.audience, support: item.support, price: null,
  options: Object.values(item.subtypes || {}).map(option => option.title),
}))
