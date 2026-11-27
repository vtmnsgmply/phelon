import { Link } from 'react-router-dom'
import { sentence, serviceIcons, serviceImages, services } from '../catalog'
import { Card } from './Card'
import { Picture } from './Picture'
import { Reveal } from './Reveal'

// Three rows of three; the first card of each row spans half the width.
export function ServiceGrid({ level = 'h3', reveal = false }) {
  const cards = Object.entries(services).map(([key, service], index) => <Card as="li" key={key} icon={serviceIcons[key]}
    media={<Picture name={serviceImages[key]} alt="" sizes={index % 3 === 0
      ? '(min-width: 56em) 36rem, (min-width: 40em) 45vw, 240px' : '(min-width: 56em) 18rem, (min-width: 40em) 45vw, 240px'} />}
    title={sentence(service.title)} level={level} spotlight className={`ph-service-card ph-span-${index % 3 === 0 ? 6 : 3}`}>
    <p className="ph-muted ph-small">{service.audience}</p>
    <Link className="ph-link" to={'/services/' + key}>View service<span className="sr-only">: {sentence(service.title)}</span></Link>
  </Card>)
  return reveal ? <Reveal as="ul" stagger className="ph-bento">{cards}</Reveal> : <ul className="ph-bento">{cards}</ul>
}
