import { Icon } from './Icon'

export function Card({ as: Tag = 'div', icon, title, level: Heading = 'h3', spotlight = false, media, className = '', children, ...rest }) {
  return <Tag className={`ph-card ${className}`.trim()} data-spotlight={spotlight ? '' : undefined} {...rest}>
    {media && <div className="ph-card-media">{media}</div>}
    {icon && <span className="ph-icon-tile"><Icon name={icon} /></span>}
    {title && <Heading>{title}</Heading>}
    {children}
  </Tag>
}
