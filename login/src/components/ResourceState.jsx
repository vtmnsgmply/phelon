import { Button } from './Button'
import { Notice } from './Notice'

// Loading, failure and "not connected yet" states shared by every data screen.
export function ResourceState({ resource, children, unavailableText = 'The server doesn’t support this feature yet, so the values below are examples and saving is turned off.' }) {
  if (resource.status === 'loading') return <div className="skeletons" role="status"><span className="sr-only">Loading…</span><span /><span /><span /></div>
  if (resource.status === 'error') return <Notice tone="danger" title="This couldn’t be loaded."><p>{resource.error}</p><Button variant="secondary" icon="refresh" onClick={resource.reload}>Try again</Button></Notice>
  return <>
    {resource.status === 'unavailable' && <Notice tone="warning" title="Not connected to the server yet">
      <p>{unavailableText}</p></Notice>}
    {children}
  </>
}
