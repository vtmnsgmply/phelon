import { useEffect, useRef, useState } from 'react'
import { useSearchParams } from 'react-router-dom'
import schema from '../../backend/resources/catalog/intake.json'
import { api } from './http'
import { bookLink, DISCOVERY } from './catalog'
import { Button } from './components/Button'
import { Field } from './components/Field'
import { Icon } from './components/Icon'
import { Notice } from './components/Notice'
import { Stepper } from './components/Stepper'
import { usePageMeta } from './hooks/usePageMeta'

const visible = (field, data, legacy) => (legacy || field.name !== 'consent_7')
  && (!field.when || data[field.when[0]] === field.when[1])
  && (!field.concerns || field.concerns.some(value => data.concerns?.includes(value)))

const MAX_FILES = 4
const MAX_BYTES = 5 * 1024 * 1024
const ALLOWED = /\.(pdf|jpe?g|png)$/i
const titles = schema.map(item => item.title)

// Mirrors the server rules: required, email format and date format.
function problem(element) {
  const { validity, type } = element
  if (validity.valueMissing) return type === 'checkbox' ? 'Tick this box to continue.' : 'Complete this field.'
  if (type === 'email' && validity.typeMismatch) return 'Enter an email address like name@example.com.'
  if (validity.badInput) return 'Enter a complete, valid value.'
  return ''
}

function Control({ field, data, change, error, check }) {
  const value = data[field.name] ?? ''
  const set = next => change(field.name, next)
  if (field.type === 'checkbox' && field.options) return <fieldset className="ph-choices">
    <legend className="ph-legend">{field.label}{!field.required && <span className="ph-optional"> (optional)</span>}</legend>
    {field.options.map(([id, label]) => <label className="ph-check" key={id}>
      <input type="checkbox" checked={value.includes(id)} onChange={event => set(event.target.checked
        ? [...(Array.isArray(value) ? value : []), id] : value.filter(item => item !== id))} /><span>{label}</span>
    </label>)}
  </fieldset>
  if (field.type === 'checkbox') return <div className="ph-field">
    <label className="ph-check"><input type="checkbox" required={field.required} checked={Boolean(value)}
      aria-invalid={error ? true : undefined} onChange={event => { set(event.target.checked); check(field.name, '') }}
      onBlur={event => check(field.name, problem(event.target))} /><span>{field.label}</span></label>
    {error && <p className="ph-error" role="alert"><Icon name="alert" size={18} /><span>{error}</span></p>}
  </div>
  const common = {
    value, required: field.required,
    onChange: event => { set(event.target.value); if (error) check(field.name, problem(event.target)) },
    onBlur: event => check(field.name, problem(event.target)),
  }
  const type = ['text', 'date', 'email', 'tel', 'number'].includes(field.type) ? field.type : 'text'
  return <Field label={field.label} error={error} optional={!field.required}>{props =>
    ['select', 'radio'].includes(field.type) ? <select {...props} {...common}>
      <option value="">Select one</option>
      {(field.options || []).map(([id, label]) => <option value={id} key={id}>{label}</option>)}
    </select>
      : field.type === 'textarea' ? <textarea {...props} {...common} rows={3} maxLength={1500} />
        : <input {...props} {...common} type={/phone/.test(field.name) ? 'tel' : type}
          inputMode={/phone/.test(field.name) ? 'tel' : type === 'email' ? 'email' : undefined}
          autoComplete={{ first_name: 'given-name', last_name: 'family-name', email: 'email', phone: 'tel', city: 'address-level2' }[field.name]}
          maxLength={1500} />}</Field>
}

function Uploads({ files, setFiles, setMessage }) {
  const [over, setOver] = useState(false)
  function add(event) {
    const next = [...files]
    const refused = []
    for (const file of event.target.files) {
      if (!ALLOWED.test(file.name)) refused.push(`${file.name} is not a PDF, JPG or PNG.`)
      else if (file.size > MAX_BYTES) refused.push(`${file.name} is larger than 5 MB.`)
      else if (next.length >= MAX_FILES) refused.push(`${file.name} was not added. You can attach up to four files.`)
      else next.push(file)
    }
    event.target.value = ''
    setOver(false)
    setFiles(next)
    setMessage(refused.join(' '))
  }
  return <div className="ph-stack">
    <label className="ph-drop" data-over={over ? '' : undefined}>
      <Icon name="upload" size={28} /><strong>Attach up to four PDFs or images (5 MB each)</strong>
      <span className="ph-muted">Choose files or drop them here. Attachments are optional.</span>
      <input type="file" multiple accept=".pdf,.jpeg,.jpg,.png" onChange={add} onDragEnter={() => setOver(true)} onDragLeave={() => setOver(false)} />
    </label>
    <p role="status" className="ph-hint">{files.length > 0 && `${files.length} file(s) ready to upload.`}</p>
    {files.length > 0 && <ul className="ph-files">{files.map((file, index) => <li className="ph-file" key={file.name + index}>
      <span>{file.name} <span className="ph-muted">({(file.size / 1024 / 1024).toFixed(1)} MB)</span></span>
      <button type="button" aria-label={'Remove ' + file.name} onClick={() => setFiles(files.filter((_, item) => item !== index))}><Icon name="close" size={18} /></button>
    </li>)}</ul>}
  </div>
}

// Answers are sensitive: they are never stored, so the visitor is warned before leaving.
function useLeaveWarning(dirty) {
  useEffect(() => {
    if (!dirty) return
    const unload = event => { event.preventDefault(); event.returnValue = '' }
    const click = event => {
      const link = event.target.closest?.('a[href]')
      if (!link || link.target || link.hasAttribute('download') || link.getAttribute('href').startsWith('#')) return
      if (!window.confirm('Your answers are not saved. Leave this page?')) { event.preventDefault(); event.stopPropagation() }
    }
    window.addEventListener('beforeunload', unload)
    document.addEventListener('click', click, true)
    return () => { window.removeEventListener('beforeunload', unload); document.removeEventListener('click', click, true) }
  }, [dirty])
}

export function Intake() {
  usePageMeta('Readiness intake')
  const [params] = useSearchParams()
  const reference = params.get('appointmentId') || params.get('reference')
  const [eligibility, setEligibility] = useState(null)
  const [data, setData] = useState({})
  const [files, setFiles] = useState([])
  const [step, setStep] = useState(0)
  const [message, setMessage] = useState('')
  const [saving, setSaving] = useState(false)
  const [errors, setErrors] = useState({})
  const [attempt, setAttempt] = useState(0)
  const heading = useRef(null)
  const shown = useRef(0)
  useEffect(() => {
    if (!reference) return
    const controller = new AbortController()
    setMessage('')
    api('/intake/status?reference=' + encodeURIComponent(reference), { signal: controller.signal })
      .then(setEligibility).catch(error => {
        if (error.name !== 'AbortError') setMessage(error.status ? error.message : 'We couldn’t check your appointment. Check your connection and try again.')
      })
    return () => controller.abort()
  }, [reference, attempt])
  useEffect(() => {
    if (shown.current === step) return
    shown.current = step
    heading.current?.focus()
  }, [step])
  useLeaveWarning(!eligibility?.submitted && (Object.keys(data).length > 0 || files.length > 0))
  const fields = schema[step].fields.filter(field => visible(field, data, eligibility?.legacy))
  const check = (name, text) => setErrors(previous => ({ ...previous, [name]: text }))
  async function advance(event) {
    event.preventDefault()
    const requiredGroup = fields.find(field => field.required && field.type === 'checkbox'
      && field.options && !data[field.name]?.length)
    if (requiredGroup) { setMessage('Choose at least one option for ' + requiredGroup.label + '.'); return }
    setMessage('')
    if (step < schema.length - 1) { setStep(step + 1); return }
    const body = new FormData()
    body.set('reference', reference)
    body.set('answers', JSON.stringify(data))
    files.forEach(file => body.append('files[]', file))
    setSaving(true)
    try {
      await api('/intake', { method: 'POST', body })
      setEligibility({ ...eligibility, submitted: true }); setData({}); setFiles([])
    } catch (error) {
      setMessage(error.status ? error.message : 'We couldn’t send your intake. Check your connection and try again.')
    } finally { setSaving(false) }
  }
  const open = eligibility && !eligibility.submitted
  return <div className="ph-section ph-section--compact"><div className="ph-container ph-stack-lg">
    <div className="ph-stack">
      <h1>Readiness intake</h1>
      <p className="ph-lede">Share only the general information that will help us prepare. Do not send privileged communications, case strategy, or sensitive identifiers.</p>
    </div>
    {!reference && <div className="ph-card ph-narrow">
      <h2>Use your appointment link</h2>
      <p>Use the intake link associated with your appointment. If you haven’t booked yet, start with a discovery call.</p>
      <div className="ph-actions"><Button to={bookLink()}>{DISCOVERY}</Button><Button to="/contact" variant="secondary">Contact us</Button></div>
    </div>}
    {reference && !eligibility && (message ? <div className="ph-narrow"><Notice tone="danger"><p>{message}</p>
      <div className="ph-actions"><Button variant="secondary" onClick={() => setAttempt(value => value + 1)}>Try again</Button><Button to="/contact" variant="text">Contact us</Button></div></Notice></div>
      : <p role="status">Checking your appointment…</p>)}
    {eligibility?.submitted && <div className="ph-card ph-narrow" role="status"><span className="ph-icon-tile"><Icon name="success" /></span><h2>Intake already received</h2><p>You don’t need to submit it again.</p><Button to="/" variant="secondary">Back to home</Button></div>}
    {open && <>
      <Notice role="note"><p>Your answers are not saved if you leave this page.</p></Notice>
      <div className="ph-intake">
        <div className="ph-intake-steps"><Stepper steps={titles} current={step} label="Intake steps" /></div>
        <form className="ph-form ph-narrow" onSubmit={advance}>
          <div className="ph-stack">
            <p className="ph-label">Step {step + 1} of {schema.length} · {titles[step]}</p>
            <progress className="ph-progress" aria-label="Intake progress" max={schema.length} value={step + 1} />
          </div>
          <div className="ph-step" key={step}>
            <h2 ref={heading} tabIndex={-1}>{titles[step]}</h2>
            {step === 6 && !fields.length && <p>No additional impact questions apply to your selections.</p>}
            {fields.map(field => <div key={field.name} className={field.when || field.concerns ? 'ph-conditional' : undefined}>
              <Control field={field} data={data} error={errors[field.name]} check={check}
                change={(fieldName, value) => setData(previous => ({ ...previous, [fieldName]: value }))} />
            </div>)}
            {step === 9 && <Uploads files={files} setFiles={setFiles} setMessage={setMessage} />}
          </div>
          {message && <Notice tone="danger"><p>{message}</p></Notice>}
          <div className="ph-bar">
            {step > 0 && <Button variant="secondary" size="lg" onClick={() => { setMessage(''); setStep(step - 1) }}>Back</Button>}
            <Button type="submit" size="lg" loading={saving} disabled={saving}>{saving ? 'Saving…' : step === schema.length - 1 ? 'Submit intake' : 'Continue'}</Button>
          </div>
        </form>
      </div>
    </>}
  </div></div>
}
