// A missing endpoint is reported plainly, so nothing looks saved when it isn't.
export const failureText = (error, feature) => [404, 405, 501].includes(error.status) ? `${feature} isn’t connected to the server yet. Nothing was changed.`
  : error.status ? error.message : 'We couldn’t reach the server. Nothing was changed.'
