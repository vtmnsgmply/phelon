import { Button } from './Button'

export function SaveBar({ dirty, saving, disabled, message, onSave, onReset }) {
  return <div className="savebar" data-active={dirty ? '' : undefined}>
    <p role="status">{message || (dirty ? 'You have unsaved changes.' : 'No unsaved changes.')}</p>
    <div className="actions">
      <Button variant="secondary" disabled={!dirty || saving} onClick={onReset}>Discard</Button>
      <Button loading={saving} disabled={!dirty || disabled} onClick={onSave}>Save changes</Button>
    </div>
  </div>
}
