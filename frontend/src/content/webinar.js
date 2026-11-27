import { flags } from './flags'

const confirmed = flags.webinarDatesConfirmed
// OWNER: the 22 August and 5 September 2026 dates have passed. Update them before setting webinarDatesConfirmed.
export const masterclassDate = confirmed ? 'Saturday, August 22, 2026 at 7:00 PM Eastern' : 'Next dates to be announced'
const workshopDate = confirmed ? 'Saturday, September 5, 2026 from 10:00 AM to 2:00 PM Eastern.' : 'Next dates to be announced.'
export const seat = confirmed ? ['/webinar#registration', 'Reserve my free seat'] : ['/contact', 'Ask to be told about the next session']

export const facts = [['75-90 min', 'live education'], ['Snapshot', 'readiness gaps'], ['0', 'legal promises']]
export const formats = ['Free live training', 'Family-focused', 'Business-aware', 'Non-legal education', 'Practical readiness']
export const scopes = [
  ['Legal scope', 'Your attorney', 'Handles legal representation, legal strategy, court-related advice, legal rights, and legal filings.'],
  ['Life-stability scope', 'PHELONPRENEUR', 'Helps with life-readiness support, family stability, household preparation, business continuity, organization, productive-time planning, release/reentry preparation, and readiness implementation.'],
]
export const learn = [
  ['Family stability', 'Identify household responsibilities, communication needs, and immediate support gaps.'],
  ['Household readiness', 'Clarify who needs access to important information and responsibilities.'],
  ['Important documents', 'Identify what should be gathered, organized, or documented.'],
  ['Financial organization', 'Map responsibilities, obligations, and professional questions before decisions pile up.'],
  ['Business continuity', 'Clarify what happens if a business owner cannot operate normally.'],
  ['Prison readiness', 'Consider practical life areas before a major transition.'],
  ['Productive-time planning', 'Approach time with purpose, structure, and intention.'],
  ['Release + reentry', 'Begin considering what may help with rebuilding later.'],
  ['Reputation + employment', 'Identify areas that may need thoughtful preparation.'],
]
export const ratings = ['Green: I have this organized', 'Yellow: I started, but this needs attention', 'Red: I have not prepared for this yet', 'First action: what to do next']
export const outputs = [
  ['Readiness Snapshot™', 'Rate key life-readiness areas as green, yellow, or red.'],
  ['Top three gaps', 'Name the areas that need the most immediate attention.'],
  ['Top three priorities', 'Choose what should be handled first, second, and third.'],
  ['First immediate action', 'Leave with one practical next step to begin structure.'],
  ['Questions for professionals', 'Separate readiness questions from legal, tax, or financial advice.'],
]
export const attendees = ['Individuals', 'Spouses and partners', 'Parents', 'Adult children', 'Business owners', 'Trusted supporters']
export const offers = [
  // OWNER: "Readiness Blueprint" also names the guided option on the home page (content/home.js).
  { label: 'Prepare now', price: '$47', title: 'PHELONPRENEUR Readiness Blueprint', copy: 'Assessment, worksheets, checklists, priority mapping, action planning, and webinar notes to help you arrive prepared before the free live masterclass.', points: ['Readiness assessment', 'Family stability worksheet', 'Important document checklist', 'Personal action planner'], to: '/webinar/checkout/47', action: 'Yes, I want to get prepared' },
  { label: 'Implementation', price: '$497', title: 'Four-Hour Group Implementation Workshop', copy: `${workshopDate} Move from information into guided exercises, worksheets, implementation periods, templates, examples, non-legal Q&A, and action planning.`, points: ['Live group workshop', 'Workbook and material access', 'Calendar and workshop access details', 'Workshop reminder sequence'], to: confirmed ? '/webinar/checkout/497' : '/contact', action: confirmed ? 'Reserve my workshop seat' : 'Ask to be told about the next session' },
  { label: 'Focused', price: '$997', title: '30-Day Readiness Implementation Program', copy: 'Focused implementation support for clients who need help organizing and completing immediate readiness priorities after identifying what needs attention.', points: ['Personal 30-day roadmap', 'Weekly implementation check-ins', 'Priority tracking', 'Final next-step plan'], to: '/book?service=private-readiness-life-planning', action: 'Request next step' },
  { label: 'Fit-based', price: '$3,497', title: '90-Day Premium Readiness Program', copy: 'Comprehensive implementation support for more complex or multi-area readiness needs. This path is fit-based and should not be automatically sold to every visitor.', points: ['Comprehensive readiness review', 'Personalized 90-day roadmap', 'Regular implementation sessions', 'Client portal, resources, and progress tracking'], to: '/book?service=90-day-ongoing-support', action: 'Discuss 90-day support' },
]
// OWNER: questions recovered word for word from backend/legacy/webinar_index.php. Confirm them.
export const faq = [
  ['Is this legal advice?', 'No. This is education and readiness support, not legal advice, legal representation, or case strategy.'],
  ['Who should attend?', 'Individuals, families, business owners, and trusted supporters who need structure for the life-impact side of serious legal disruption.'],
  ['How much does the masterclass cost?', 'The live Structure Before Panic masterclass is free.'],
  ['Will there be a replay?', 'Event access and follow-up details will be sent after registration. Attend live if possible so you can stay current with the next steps.'],
  ['How long is the training?', confirmed ? `The masterclass is planned for approximately 90 minutes on ${masterclassDate}.` : 'The masterclass is planned for approximately 90 minutes. Next dates to be announced.'],
  ['Is my information private?', 'Your registration information is used to send event access and readiness information. Do not submit sensitive legal facts on this form.'],
  ['What happens after registration?', 'You will see confirmation messaging and receive event details. You may also be offered optional readiness resources before the masterclass.'],
]
