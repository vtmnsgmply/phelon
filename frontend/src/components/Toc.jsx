import { Icon } from './Icon'

// A sticky list on wide screens, a disclosure on phones. Only one is displayed at a time.
export function Toc({ items, label = 'On this page' }) {
  const list = <ul>{items.map(item => <li key={item.id}><a href={'#' + item.id}>{item.label}</a></li>)}</ul>
  return <>
    <nav className="ph-toc ph-toc-desktop" aria-label={label}><p className="ph-label">{label}</p>{list}</nav>
    <details className="ph-toc ph-toc-mobile ph-details"><summary>{label}<Icon name="chevronDown" size={20} /></summary>
      <nav className="ph-details-body" aria-label={label}>{list}</nav></details>
  </>
}
