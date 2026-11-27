import { useState } from 'react'
import { Icon } from '../components/Icon'
import { NavLink } from './NavLink'

// A collapsible group of links. It opens again whenever one of its pages is shown.
export function NavSection({ section, route, onNavigate }) {
  const active = section.children.some(child => child.route === route)
  const [state, setState] = useState({ open: true, route })
  if (state.route !== route) setState({ open: active || state.open, route })
  const id = 'nav-' + section.id
  return <div className="nav-section">
    <button type="button" className="nav-parent" data-active={active ? '' : undefined} aria-expanded={state.open} aria-controls={id}
      onClick={() => setState({ open: !state.open, route })}>
      <Icon name={section.icon} />{section.label}<Icon name="chevronDown" size={16} />
    </button>
    <ul className="nav-sub" id={id} hidden={!state.open}>
      {section.children.map(child => <li key={child.route}><NavLink item={child} route={route} onNavigate={onNavigate} /></li>)}
    </ul>
  </div>
}
