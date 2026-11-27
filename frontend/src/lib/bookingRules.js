// Mirrors backend/app/Http/Requests/BookDiscovery.php. Change both together.
const PHONE = /^(?:[+() .-]*\d){7,}[+() .-]*$/
const STATES = new Set('AL AK AZ AR CA CO CT DE DC FL GA HI ID IL IN IA KS KY LA ME MD MA MI MN MS MO MT NE NV NH NJ NM NY NC ND OH OK OR PA RI SC SD TN TX UT VT VA WA WV WI WY AS GU MP PR VI'.split(' '))

export const bookingRules = {
  phone: value => PHONE.test(value) ? '' : 'Enter a phone number with at least 7 digits, using only digits, spaces and + ( ) . - characters.',
  state: value => STATES.has(value.toUpperCase()) ? '' : 'Enter a two-letter US state or territory code, such as NY.',
}
