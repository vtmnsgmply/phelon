export const AVATAR_TYPES = ['image/jpeg', 'image/png', 'image/webp']
export const AVATAR_LIMIT = 5 * 1024 * 1024
const SIZE = 256

// Centre-crops to a small square JPEG in the browser. Redrawing also drops location and camera metadata.
export async function squarePhoto(file) {
  if (!AVATAR_TYPES.includes(file.type)) throw new Error('Choose a JPG, PNG or WebP image.')
  if (file.size > AVATAR_LIMIT) throw new Error('That image is larger than 5 MB. Choose a smaller one.')
  const image = await createImageBitmap(file).catch(() => { throw new Error('This file couldn’t be read as an image.') })
  const side = Math.min(image.width, image.height)
  const canvas = Object.assign(document.createElement('canvas'), { width: SIZE, height: SIZE })
  const context = canvas.getContext('2d')
  context.fillStyle = '#fff'
  context.fillRect(0, 0, SIZE, SIZE)
  context.drawImage(image, (image.width - side) / 2, (image.height - side) / 2, side, side, 0, 0, SIZE, SIZE)
  image.close()
  const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9))
  return { blob, url: canvas.toDataURL('image/jpeg', 0.9) }
}
