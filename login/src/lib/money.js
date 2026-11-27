// US dollar amounts. An empty value means no price has been set.
export const money = value => value === '' || value == null ? '—' : new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value)
