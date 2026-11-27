import { useId } from 'react'
import { Cover } from './Cover'

export function Section({ id, bg, surface, cover, title, level: Heading = 'h2', lede, className = '', children, ...rest }) {
  const titleId = useId()
  const dark = surface === 'dark' || bg === 'deep' || bg === 'band' || Boolean(cover)
  return <section id={id} className={`ph-section${cover ? ' ph-has-cover' : ''} ${className}`.trim()} data-bg={bg}
    data-surface={dark ? 'dark' : undefined} aria-labelledby={title ? titleId : undefined} {...rest}>
    {cover && <Cover name={cover} />}
    <div className="ph-container">
      {title && <div className="ph-heading"><Heading id={titleId}>{title}</Heading>{lede && <p className="ph-lede ph-muted">{lede}</p>}</div>}
      {children}
    </div>
  </section>
}
