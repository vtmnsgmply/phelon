import { useState } from 'react'
import { useLocation } from 'react-router-dom'
import { BookingPicker } from '../Booking'
import { hasService, serviceLabel } from '../catalog'
import { Accordion } from '../components/Accordion'
import { Cover } from '../components/Cover'
import { usePageMeta } from '../hooks/usePageMeta'
import { CheckList } from '../components/CheckList'

const expectations = ['A confidential first conversation', 'A chance to explain your most immediate priorities', 'An on-screen reference once your appointment is saved']
const details = [
  ['Who the call is for', 'Individuals facing serious charges, family members looking for a continuity plan, and professionals helping someone prepare for change.'],
  ['What to prepare', 'Think about the responsibilities that feel most urgent and what you want to ask. Keep the conversation general. Do not send confidential case materials or sensitive identifiers in the booking form.'],
  ['What happens after you book', 'Keep the appointment time and on-screen reference. You can complete an optional readiness intake to help the team prepare.'],
  ['What the call does not cover', 'It is not legal advice, legal representation, or emergency response. Speak to your attorney or the appropriate professional for those needs.'],
]

export function Book() {
  usePageMeta('Schedule a discovery call')
  const { search } = useLocation()
  const [picked, setPicked] = useState(null)
  const query = new URLSearchParams(search)
  const interest = picked?.search === search ? picked.value : [query.get('service'), query.get('subservice')].filter(Boolean).join('/')
  const [key, subtype] = interest.split('/')

  return <div className="ph-section ph-section--compact"><div className="ph-container ph-stack-lg">
    <div className="ph-book">
      <div className="ph-card ph-summary ph-has-cover" data-surface="dark">
        <Cover name="video-call" priority />
        <h1>Let’s find a time to talk</h1>
        <p className="ph-muted">In a complimentary 15-minute conversation, we’ll discuss what matters now and whether our non-legal readiness services could help.</p>
        <dl>
          <div><dt>Service</dt><dd>{hasService(key) ? serviceLabel(key, subtype) : 'General / not sure yet'}</dd></div>
          <div><dt>Call length</dt><dd>15 minutes, complimentary</dd></div>
        </dl>
        <h2 className="ph-label">What to expect</h2>
        <CheckList items={expectations} />
      </div>
      <BookingPicker onInterest={value => setPicked({ search, value })} />
    </div>
    <section className="ph-stack ph-narrow" aria-labelledby="before-title">
      <h2 id="before-title">Before your discovery call</h2>
      <Accordion items={details.map(([title, text]) => ({ title, content: <p>{text}</p> }))} />
    </section>
  </div></div>
}
