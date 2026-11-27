export const CONTRACT_STATUS = [['draft', 'Draft', 'neutral'], ['sent', 'Sent', 'gold'], ['signed', 'Signed', 'success'], ['ended', 'Ended', 'muted']]
export const statusOf = value => CONTRACT_STATUS.find(([id]) => id === value) ?? CONTRACT_STATUS[0]
