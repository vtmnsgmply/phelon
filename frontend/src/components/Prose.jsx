export function Prose({ children, className = '' }) {
  return <div className={`ph-prose ${className}`.trim()}>{children}</div>
}
