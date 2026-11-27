import { Icon } from '../components/Icon'

export function NavLink({ item, route, onNavigate }) {
  return <a href={'#/' + item.route} aria-current={item.route === route ? 'page' : undefined} onClick={onNavigate}>
    <Icon name={item.icon} />{item.label}</a>
}
