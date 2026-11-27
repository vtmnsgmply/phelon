import { Cover } from './Cover'

export function PageHero({ title, lede, children, before, cover }) {
  return <div className={`ph-pagehero ph-glow${cover ? ' ph-has-cover' : ''}`} data-surface="dark">
    {cover && <Cover name={cover} priority />}
    <div className="ph-container ph-stack">
      {before}
      <h1>{title}</h1>
      {lede && <p className="ph-lede ph-muted">{lede}</p>}
      {children}
    </div>
  </div>
}
