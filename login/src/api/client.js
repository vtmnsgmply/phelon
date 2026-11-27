let csrfToken = ''
let onUnauthorized = () => {}

export function setUnauthorizedHandler(handler) { onUnauthorized = handler }

export async function request(path, { method = 'GET', body, signal, ignore401 = false } = {}) {
  const writes = !['GET', 'HEAD'].includes(method)
  if (writes && !csrfToken) await request('/context', { signal })
  const upload = body instanceof FormData
  const response = await fetch('/api' + path, {
    method, signal, credentials: 'same-origin', cache: 'no-store',
    headers: {
      Accept: 'application/json',
      ...(writes && { 'X-CSRF-TOKEN': csrfToken }),
      ...(body !== undefined && !upload && { 'Content-Type': 'application/json' }),
    },
    body: body === undefined ? undefined : upload ? body : JSON.stringify(body),
  })
  const result = await response.json().catch(() => null)
  if (!response.ok) {
    if (response.status === 419) csrfToken = ''
    if (response.status === 401 && !ignore401) onUnauthorized()
    const error = new Error(response.status === 419 ? 'Your session expired. Please try again.'
      : Object.values(result?.errors || {}).flat()[0] || result?.message || 'The request could not be completed.')
    error.status = response.status
    throw error
  }
  // A success without JSON means the endpoint is not there (for example a web page was returned).
  if (result === null) {
    const error = new Error('The server did not return the expected data.')
    error.status = 501
    throw error
  }
  if (typeof result.csrfToken === 'string') csrfToken = result.csrfToken
  return result
}
