import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { api } from '../http'
import { Button } from '../components/Button'
import { Icon } from '../components/Icon'
import { Notice } from '../components/Notice'
import { Picture } from '../components/Picture'
import { usePageMeta } from '../hooks/usePageMeta'
import { NotFound } from './NotFound'

const offers = {
  '47': ['PHELONPRENEUR Readiness Blueprint', '$47', 'A starter kit for sorting priorities, documents, and practical next steps.'],
  '497': ['Four-Hour Group Implementation Workshop', '$497', 'Guided exercises, worksheets, and non-legal Q&A. Contact the team for the next session.'],
  '3497': ['90-Day Premium Readiness Program', '$3,497', 'Extended implementation support. Check availability with the team before purchasing.'],
}

function Offer({ offer }) {
  const [title, price, copy] = offers[offer]
  usePageMeta(title)
  const [url, setUrl] = useState('')
  const [message, setMessage] = useState('')
  const [attempt, setAttempt] = useState(0)
  useEffect(() => {
    const controller = new AbortController()
    setUrl(''); setMessage('')
    api('/checkout/' + offer, { signal: controller.signal })
      .then(result => setUrl(result.url))
      .catch(error => {
        if (error.name !== 'AbortError') setMessage(error.status ? error.message : 'We couldn’t check online checkout. Check your connection and try again.')
      })
    return () => controller.abort()
  }, [offer, attempt])
  return <div className="ph-section ph-section--compact"><div className="ph-container ph-stack-lg">
    <div><Link className="ph-back" to="/webinar"><Icon name="chevronLeft" size={18} />Webinar resources</Link></div>
    <div className="ph-split ph-split--center">
      <div className="ph-card">
        <h1>{title}</h1><p className="ph-price ph-flush">{price}</p><p className="ph-lede">{copy}</p>
        {url ? <Button href={url} rel="noreferrer" size="lg">Continue to secure checkout</Button>
          : message ? <Notice tone="warning"><p>{message}</p><div className="ph-actions"><Button variant="secondary" onClick={() => setAttempt(value => value + 1)}>Try again</Button><Button to="/contact" variant="text">Contact the team</Button></div></Notice>
            : <p role="status">Checking checkout availability…</p>}
        <Link className="ph-link" to="/webinar/resources">Already purchased?</Link>
      </div>
      <div className="ph-photo ph-product"><Picture name={'product-' + offer} alt={`Product artwork for the ${price} offer`} sizes="(min-width: 56em) 24rem, 90vw" priority /></div>
    </div>
  </div></div>
}

export function Checkout() {
  const { offer } = useParams()
  return Object.hasOwn(offers, offer) ? <Offer key={offer} offer={offer} /> : <NotFound />
}
