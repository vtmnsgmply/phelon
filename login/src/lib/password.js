// Mirrors the server's account rule (phelon:staff-create): 12 to 72 bytes, no null characters.
export const passwordChecks = (password, confirm, current) => {
  const bytes = new TextEncoder().encode(password).length
  return [
    ['Between 12 and 72 characters', bytes >= 12 && bytes <= 72 && !password.includes('\0')],
    ...(current === undefined ? [] : [['Different from your current password', Boolean(password) && password !== current]]),
    ['Both new passwords match', Boolean(password) && password === confirm],
  ]
}

export function generatePassword(length = 18) {
  const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%*?'
  const limit = 256 - (256 % alphabet.length)
  let out = ''
  while (out.length < length) for (const byte of crypto.getRandomValues(new Uint8Array(length))) {
    if (byte < limit && out.length < length) out += alphabet[byte % alphabet.length]
  }
  return out
}
