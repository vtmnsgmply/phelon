// A doorway: the outline is deliberately open at the bottom.
export function ArchFrame({ children, outline = true }) {
  return <div className="ph-arch-wrap">
    <div className="ph-arch">{children}</div>
    {outline && <svg className="ph-arch-outline" viewBox="0 0 400 500" preserveAspectRatio="none" aria-hidden="true" focusable="false">
      <path d="M2 498V200a198 198 0 0 1 396 0v298" pathLength="1" />
    </svg>}
  </div>
}
