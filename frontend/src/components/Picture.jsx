import manifest from '../media-manifest.json'

const BASE = `${import.meta.env.BASE_URL}media/`

export function Picture({ name, alt, sizes = '100vw', priority = false, className }) {
  const image = manifest[name]
  if (!image) {
    if (import.meta.env.DEV) console.warn(`[Picture] unknown image "${name}"`)
    return null
  }
  const set = extension => image.widths.map(width => `${BASE}${name}-${width}.${extension} ${width}w`).join(', ')
  return <picture>
    <source type="image/avif" srcSet={set('avif')} sizes={sizes} />
    <source type="image/webp" srcSet={set('webp')} sizes={sizes} />
    <img src={`${BASE}${name}-${image.widths.at(-1)}.webp`} width={image.w} height={image.h} alt={alt} className={className}
      loading={priority ? 'eager' : 'lazy'} decoding={priority ? undefined : 'async'} fetchPriority={priority ? 'high' : 'auto'} />
  </picture>
}
