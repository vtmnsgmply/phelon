import { useState } from 'react'
import { useUnsaved } from './useUnsaved'

// Edit, validate, save or discard one settings object. Nothing is marked saved unless the server confirms.
export function useSettingsForm(initial, persist, validate, savedText) {
  const [saved, setSaved] = useState(initial)
  const [value, setValue] = useState(initial)
  const [saving, setSaving] = useState(false)
  const [message, setMessage] = useState('')
  const dirty = JSON.stringify(value) !== JSON.stringify(saved)
  useUnsaved(dirty)
  return {
    value, dirty, saving, message,
    change: next => { setValue(next); setMessage('') },
    reset: () => { setValue(saved); setMessage('') },
    save: async () => {
      const problem = validate?.(value)
      if (problem) { setMessage(problem); return }
      setSaving(true); setMessage('')
      try { await persist(value); setSaved(value); setMessage(savedText) }
      catch (error) { setMessage(error.status ? `Not saved. ${error.message}` : 'Not saved. We couldn’t reach the server.') }
      finally { setSaving(false) }
    },
  }
}
