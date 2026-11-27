export const OFFERS = [['47', '$47 Readiness Blueprint'], ['497', '$497 Group Implementation Workshop'], ['3497', '$3,497 90-Day Premium Program']]
// The server only accepts links in this form (routes/api.php).
const CHECKOUT = /^https:\/\/link\.fastpaydirect\.com\/payment-link\/[A-Za-z0-9]+$/
const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

export const defaultSettings = () => ({
  organizationName: 'The PHELONPRENEUR Network', contactEmail: 'contact@phelonpreneurnetwork.com', responseTime: 'Within one business day',
  notifyEmail: '', notifyBooking: false, notifyIntake: false,
  checkout: { 47: '', 497: '', 3497: '' },
  resourceLibrary: false, legacyIntake: false,
})

export const checkoutError = value => !value || CHECKOUT.test(value) ? '' : 'Use a link like https://link.fastpaydirect.com/payment-link/abc123.'

export function settingsError(settings) {
  if (!settings.organizationName.trim()) return 'Enter the organization name.'
  if (!EMAIL.test(settings.contactEmail)) return 'Enter a valid contact email.'
  if ((settings.notifyBooking || settings.notifyIntake) && !EMAIL.test(settings.notifyEmail)) return 'Enter a valid email address for notifications.'
  if (OFFERS.some(([id]) => checkoutError(settings.checkout[id]))) return 'Fix the highlighted payment links.'
  return ''
}
