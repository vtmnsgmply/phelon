// One pointer listener for the card spotlight. Does nothing on touch devices.
export function initSpotlight() {
  if (!matchMedia('(hover: hover) and (pointer: fine)').matches) return
  document.addEventListener('pointermove', event => {
    const element = event.target.closest?.('[data-spotlight]')
    if (!element) return
    const box = element.getBoundingClientRect()
    element.style.setProperty('--mx', `${event.clientX - box.left}px`)
    element.style.setProperty('--my', `${event.clientY - box.top}px`)
  }, { passive: true })
}
