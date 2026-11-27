import { Picture } from './Picture'

// A background photo under a dark scrim (see .ph-cover). The text above stays real text on a controlled surface.
// Where the subject sits in each photo, so wide crops keep it in frame.
const subject = { team: '50% 22%', doorway: '50% 68%', corridor: '50% 55%', 'video-call': '50% 40%', family: '50% 30%' }

export function Cover({ name, priority = false }) {
  return <div className="ph-cover" aria-hidden="true" style={{ '--cover-at': subject[name] }}>
    <Picture name={name} alt="" sizes="(min-width: 56em) 100vw, 240px" priority={priority} />
  </div>
}
