import { today } from './dates'

// Quoted for CSV. A leading apostrophe stops spreadsheet programs running a cell as a formula.
const cell = value => {
  const text = String(value ?? '')
  return `"${(typeof value === 'string' && /^[=+\-@\t\r]/.test(text) ? "'" + text : text).replace(/"/g, '""')}"`
}
export const toCsv = rows => rows.map(row => row.map(cell).join(',')).join('\r\n')

export function downloadCsv(name, rows) {
  const url = URL.createObjectURL(new Blob(['﻿' + toCsv(rows)], { type: 'text/csv;charset=utf-8' }))
  Object.assign(document.createElement('a'), { href: url, download: `${name}-${today()}.csv` }).click()
  URL.revokeObjectURL(url)
}
