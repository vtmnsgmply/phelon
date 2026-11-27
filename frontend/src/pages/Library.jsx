import { useState } from 'react'
import { useSearchParams } from 'react-router-dom'
import { api } from '../http'
import { Button } from '../components/Button'
import { Field } from '../components/Field'
import { Notice } from '../components/Notice'
import { usePageMeta } from '../hooks/usePageMeta'

export function Library() {
  usePageMeta('Access your purchase')
  const [params] = useSearchParams()
  const [transaction, setTransaction] = useState(params.get('transactionId') || '')
  const [code, setCode] = useState('')
  const [message, setMessage] = useState(null)
  const [documentUrl, setDocumentUrl] = useState('')
  const [busy, setBusy] = useState('')
  async function act(resend) {
    setMessage(null); setBusy(resend ? 'resend' : 'verify')
    try {
      const result = await api('/library/' + (resend ? 'resend' : 'verify'), {
        method: 'POST', body: resend ? { transaction } : { transaction, code },
      })
      if (resend) setMessage(['info', result.message])
      else { setDocumentUrl(result.document); setCode('') }
    } catch (error) {
      setMessage(['danger', error.status ? error.message : 'We couldn’t reach the library. Check your connection and try again.'])
    } finally { setBusy('') }
  }
  return <div className="ph-section ph-section--compact"><div className="ph-container ph-stack-lg ph-narrow">
    <div className="ph-stack"><h1>Access your purchase</h1><p className="ph-lede">Enter your purchase reference and access code to download your resource.</p></div>
    <form className="ph-card ph-form" onSubmit={event => { event.preventDefault(); act(false) }}>
      <Field label="Purchase reference" className="ph-picker">{props => <input {...props} required value={transaction} maxLength={120} autoComplete="off" onChange={event => {
        setDocumentUrl(''); setTransaction(event.target.value)
      }} />}</Field>
      <Field label="Access code" className="ph-picker">{props => <input {...props} required={!documentUrl} value={code} maxLength={40} autoComplete="one-time-code" onChange={event => setCode(event.target.value)} />}</Field>
      {documentUrl && <Notice tone="success"><p>Access verified. Your resource is ready.</p></Notice>}
      <div className="ph-actions">
        {documentUrl ? <Button href={documentUrl} size="lg">Download your resource</Button>
          : <Button type="submit" size="lg" loading={busy === 'verify'} disabled={Boolean(busy)}>Verify access</Button>}
        <Button variant="secondary" size="lg" loading={busy === 'resend'} disabled={Boolean(busy) || !transaction.trim()} onClick={() => act(true)}>Send my access again</Button>
      </div>
      {message && <div className="ph-picker"><Notice tone={message[0]}><p>{message[1]}</p></Notice></div>}
    </form>
  </div></div>
}
