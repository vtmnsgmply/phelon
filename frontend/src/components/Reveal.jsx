import { useEffect, useRef } from 'react'

export function Reveal({ as: Tag = 'div', stagger = false, className, children, ...rest }) {
  const ref = useRef(null)
  useEffect(() => {
    const element = ref.current
    if (!element) return
    if (!('IntersectionObserver' in window)) { element.setAttribute('data-in', ''); return }
    // A low threshold, so tall single-column grids on phones still reveal.
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) { element.setAttribute('data-in', ''); observer.disconnect() }
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.01 })
    observer.observe(element)
    return () => observer.disconnect()
  }, [])
  return <Tag ref={ref} data-reveal={stagger ? 'stagger' : ''} className={className} {...rest}>{children}</Tag>
}
