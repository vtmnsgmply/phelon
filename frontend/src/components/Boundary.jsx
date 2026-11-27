import { bookLink, DISCLAIMER, DISCOVERY } from '../catalog'
import { Button } from './Button'

// The clearly bordered "what this is not" panel used on the home and About pages.
export function Boundary({ level: Heading = 'h3' }) {
  return <div className="ph-boundary">
    <Heading>What this is, and what it is not</Heading>
    <p>We offer private, non-legal planning and organization, with no pressure to join a program. We do not replace an attorney or other licensed professional, and we do not promise specific outcomes.</p>
    <p><strong>{DISCLAIMER}</strong></p>
    <div><Button to={bookLink()}>{DISCOVERY}</Button></div>
  </div>
}
