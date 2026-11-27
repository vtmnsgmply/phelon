let csrfToken = ''

export function setCsrf(token) {
  if (typeof token === 'string') csrfToken = token
}

export async function api(path, options = {}) {
  const method = options.method || 'GET'
  const mutating = !['GET', 'HEAD'].includes(method)
  if (mutating && !csrfToken) setCsrf((await api('/context')).csrfToken)
  const multipart = options.body instanceof FormData
  const response = await fetch('/api' + path, {
    method,
    credentials: 'same-origin',
    cache: 'no-store',
    signal: options.signal,
    headers: {
      Accept: 'application/json',
      ...(mutating && { 'X-CSRF-TOKEN': csrfToken }),
      ...(options.body !== undefined && !multipart && { 'Content-Type': 'application/json' }),
    },
    body: options.body === undefined ? undefined : multipart ? options.body : JSON.stringify(options.body),
  })
  const payload = await response.json().catch(() => ({}))
  if (!response.ok) {
    if (response.status === 419) csrfToken = ''
    const firstField = Object.values(payload.errors || {}).flat()[0]
    const error = new Error(response.status === 419 ? 'Your session expired. Please try again.'
      : firstField || payload.message || 'The request could not be completed. Please try again.')
    error.status = response.status
    throw error
  }
  setCsrf(payload.csrfToken)
  return payload
}
