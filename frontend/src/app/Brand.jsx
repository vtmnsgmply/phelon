import { Link } from 'react-router-dom'

export function Brand() {
  return <Link className="ph-brand" to="/"><img src={`${import.meta.env.BASE_URL}media/logo-mark.webp`} alt="" width="40" height="40" />Phelonpreneur</Link>
}
