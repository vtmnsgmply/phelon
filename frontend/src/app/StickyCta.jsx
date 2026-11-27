import { useEffect, useRef, useState } from 'react'
import { bookLink, DISCOVERY } from '../catalog'
import { Button } from '../components/Button'

// Small screens only. Appears after the home hero, or after 400px on other pages.
export function StickyCta({ home, inert }) {
  const sentinel = useRef(null)
  const [visible, setVisible] = useState(false)
  useEffect(() => {
    const target = (home && document.getElementById('hero')) || sentinel.current
    const observer = new IntersectionObserver(([entry]) => setVisible(!entry.isIntersecting))
    observer.observe(target)
    return () => observer.disconnect()
  }, [home])
  return <>
    <div className="ph-sentinel ph-sentinel--deep" ref={sentinel} />
    <aside className="ph-sticky-cta" aria-label="Book a call" data-visible={visible ? '' : undefined} inert={inert}>
      <Button to={bookLink()} size="lg">{DISCOVERY}</Button>
    </aside>
  </>
}
