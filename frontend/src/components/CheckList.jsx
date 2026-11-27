import { Icon } from './Icon'

// A list with a decorative icon per item. Pass icon="close" for "is not" lists.
export function CheckList({ items, icon = 'check' }) {
  return <ul className="ph-checks">
    {items.map(item => <li key={item}><Icon name={icon} size={18} /><span>{item}</span></li>)}
  </ul>
}
