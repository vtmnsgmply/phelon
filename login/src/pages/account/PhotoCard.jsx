import { useState } from 'react'
import { Avatar } from '../../components/Avatar'
import { Button } from '../../components/Button'
import { Card } from '../../components/Card'
import { Icon } from '../../components/Icon'
import { Notice } from '../../components/Notice'
import { useUnsaved } from '../../hooks/useUnsaved'
import { confirmAction } from '../../lib/alerts'
import { AVATAR_TYPES, squarePhoto } from '../../lib/image'
import { failureText } from './failureText'

export function PhotoCard({ api, user, onUser }) {
  const [chosen, setChosen] = useState(null)
  const [state, setState] = useState({ busy: '', tone: '', text: '' })
  useUnsaved(Boolean(chosen))
  const say = (tone, text) => setState({ busy: '', tone, text })

  async function choose(event) {
    const file = event.target.files[0]
    event.target.value = ''
    if (!file) return
    try { setChosen(await squarePhoto(file)); say('', '') }
    catch (error) { setChosen(null); say('danger', error.message) }
  }
  async function save() {
    setState({ busy: 'save', tone: '', text: '' })
    try {
      const result = await api.uploadAvatar(chosen.blob, chosen.url)
      onUser({ ...user, avatarUrl: result.avatarUrl }); setChosen(null)
      say('success', 'Your photo was saved.')
    } catch (error) { say('danger', failureText(error, 'Uploading a photo')) }
  }
  async function remove() {
    if (!await confirmAction({ title: 'Remove your photo?', text: 'Your initials will be shown instead.', confirmText: 'Remove photo' })) return
    setState({ busy: 'remove', tone: '', text: '' })
    try { await api.removeAvatar(); onUser({ ...user, avatarUrl: '' }); say('success', 'Your photo was removed.') }
    catch (error) { say('danger', failureText(error, 'Removing your photo')) }
  }

  return <Card title="Photo" description="Shown beside your name. JPG, PNG or WebP, up to 5 MB. It is cropped to a square.">
    {state.text && <Notice tone={state.tone}><p>{state.text}</p></Notice>}
    <div className="photo-row">
      <Avatar name={user.displayName} src={chosen?.url || user.avatarUrl} large />
      <div className="photo-actions">
        <div className="actions">
          <label className="btn btn--secondary file-button"><Icon name="upload" size={18} />{user.avatarUrl || chosen ? 'Choose another image' : 'Upload image'}
            <input type="file" className="sr-only" accept={AVATAR_TYPES.join(',')} onChange={choose} /></label>
          {chosen ? <><Button loading={state.busy === 'save'} onClick={save}>Save photo</Button><Button variant="ghost" onClick={() => { setChosen(null); say('', '') }}>Cancel</Button></>
            : user.avatarUrl && <Button variant="ghost" icon="trash" loading={state.busy === 'remove'} onClick={remove}>Remove photo</Button>}
        </div>
        <p className="hint" role="status">{chosen ? 'Preview only until you save it.' : ''}</p>
      </div>
    </div>
  </Card>
}
