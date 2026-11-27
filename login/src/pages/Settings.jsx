import { Card } from '../components/Card'
import { Field } from '../components/Field'
import { PageHeader } from '../components/PageHeader'
import { ResourceState } from '../components/ResourceState'
import { SaveBar } from '../components/SaveBar'
import { Switch } from '../components/Switch'
import { useResource } from '../hooks/useResource'
import { useSettingsForm } from '../hooks/useSettingsForm'
import { checkoutError, defaultSettings, OFFERS, settingsError } from '../lib/settings'

function Editor({ resource, api }) {
  const form = useSettingsForm(resource.data, api.saveSettings, settingsError, 'Settings saved.')
  const value = form.value
  const set = (key, next) => form.change({ ...value, [key]: next })
  const text = (key, props) => ({ ...props, value: value[key], onChange: event => set(key, event.target.value) })
  return <>
    <Card title="Organization" description="Details shown to clients.">
      <div className="form-grid">
        <Field label="Organization name">{props => <input {...props} {...text('organizationName', { maxLength: 120 })} />}</Field>
        <Field label="Contact email">{props => <input {...props} {...text('contactEmail', { type: 'email', maxLength: 254 })} />}</Field>
        <Field label="Response time">{props => <input {...props} {...text('responseTime', { maxLength: 80 })} />}</Field>
      </div>
    </Card>
    <Card title="Notifications" description="Email the team when something arrives.">
      <Field label="Send notifications to">{props => <input {...props} {...text('notifyEmail', { type: 'email', maxLength: 254, placeholder: 'team@example.com' })} />}</Field>
      <Switch label="New bookings" checked={value.notifyBooking} onChange={next => set('notifyBooking', next)} />
      <Switch label="New intakes" help="The email says an intake arrived. It never includes the answers." checked={value.notifyIntake} onChange={next => set('notifyIntake', next)} />
    </Card>
    <Card title="Payment links" description="Leave a link empty to close online checkout for that offer.">
      {OFFERS.map(([id, label]) => <Field key={id} label={label} error={checkoutError(value.checkout[id])}>{props =>
        <input {...props} type="url" inputMode="url" maxLength={200} value={value.checkout[id]} onChange={event => set('checkout', { ...value.checkout, [id]: event.target.value.trim() })} />}</Field>)}
    </Card>
    <Card title="Features">
      <Switch label="Online resource library" help="Buyers can download their purchase with an emailed access code." checked={value.resourceLibrary} onChange={next => set('resourceLibrary', next)} />
      <Switch label="Old intake links" help="Accept intake links created by the previous website." checked={value.legacyIntake} onChange={next => set('legacyIntake', next)} />
    </Card>
    <SaveBar dirty={form.dirty} saving={form.saving} disabled={resource.status === 'unavailable'} message={form.message} onSave={form.save} onReset={form.reset} />
  </>
}

export function Settings({ api }) {
  const resource = useResource(api.settings, defaultSettings)
  return <>
    <PageHeader title="Settings" description="Organization, notifications, payments and features." />
    <ResourceState resource={resource}>{resource.data && <Editor resource={resource} api={api} />}</ResourceState>
  </>
}
