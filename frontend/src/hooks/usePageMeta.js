import { useEffect } from 'react'

// Same format the Laravel server uses for service pages, so the two do not fight.
const SITE = 'The PHELONPRENEUR Network'
const DESCRIPTION = 'Confidential, non-legal readiness and life-stability support from the PHELONPRENEUR Network.'

// Depends on strings only, so a #hash change never resets the title.
export function usePageMeta(title, description = DESCRIPTION) {
  useEffect(() => {
    document.title = title ? `${title} | ${SITE}` : SITE
    let meta = document.querySelector('meta[name="description"]')
    if (!meta) { meta = document.createElement('meta'); meta.name = 'description'; document.head.append(meta) }
    meta.content = description
  }, [title, description])
}
