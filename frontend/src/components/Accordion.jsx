import { Icon } from './Icon'

export function Accordion({ items }) {
  return <div className="ph-accordion">
    {items.map(item => <details className="ph-details" key={item.title}>
      <summary>{item.title}<Icon name="chevronDown" size={20} /></summary>
      <div className="ph-details-body">{item.content}</div>
    </details>)}
  </div>
}
