export function PageHeader({ title, description, actions }) {
  return <header className="page-head">
    <div><h1>{title}</h1>{description && <p className="muted">{description}</p>}</div>
    {actions && <div className="actions">{actions}</div>}
  </header>
}
