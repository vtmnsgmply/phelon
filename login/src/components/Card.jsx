export function Card({ title, description, actions, flush = false, className = '', children }) {
  return <section className={`card${flush ? ' card--flush' : ''} ${className}`.trim()}>
    {(title || actions) && <header className="card-head">
      <div>{title && <h2>{title}</h2>}{description && <p className="muted">{description}</p>}</div>
      {actions && <div className="actions">{actions}</div>}
    </header>}
    {children}
  </section>
}
