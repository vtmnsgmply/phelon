// Starting points only; edit before sending. No legal advice, promises or sensitive details.
export const TEMPLATES = [
  ['blank', 'Blank message', '', ''],
  ['booking', 'Appointment reminder', 'Your discovery call', 'Hello,\n\nThis is a reminder about your upcoming discovery call. Please reply if you need to choose another time.\n\nThe PHELONPRENEUR Network'],
  ['intake', 'Intake reminder', 'Your readiness intake', 'Hello,\n\nWhen you have a moment, please complete your readiness intake using the link for your appointment. Share general information only.\n\nThe PHELONPRENEUR Network'],
  ['followup', 'Follow-up', 'Following up on our conversation', 'Hello,\n\nThank you for speaking with us. Please reply with any questions about next steps.\n\nThe PHELONPRENEUR Network'],
]
export const isEmail = value => /^[^\s@,;]+@[^\s@,;]+\.[^\s@,;]+$/.test(value)
export const mailto = ({ to, subject, body }) =>
  `mailto:${encodeURIComponent(to).replace('%40', '@')}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body.replace(/\r?\n/g, '\r\n'))}`
