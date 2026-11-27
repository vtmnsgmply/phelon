export function Stepper({ steps, current, label }) {
  return <nav aria-label={label}><ol className="ph-stepper">
    {steps.map((title, index) => <li key={title} aria-current={index === current ? 'step' : undefined}
      data-done={index < current ? '' : undefined}><span>{title}{index < current && <span className="sr-only"> (completed)</span>}</span></li>)}
  </ol></nav>
}
