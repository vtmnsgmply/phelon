// Response shapes copied from the backend services. No request leaves the browser.
export const slots = ['2030-01-07T14:00:00Z', '2030-01-07T14:15:00Z', '2030-01-07T15:00:00Z'];

export const responses = {
  '/api/context': { csrfToken: 'test-token' },
  '/api/availability': { minDate: '2030-01-07', maxDate: '2030-02-21', timezone: 'America/New_York', durationMinutes: 15, slots },
  '/api/intake/status': { submitted: false, legacy: false },
  '/api/checkout/47': { url: 'https://link.fastpaydirect.com/payment-link/test' },
};

export async function mockApi(page, overrides = {}) {
  await page.route((url) => url.pathname.startsWith('/api/'), (route) => {
    const { pathname } = new URL(route.request().url());
    const body = overrides[pathname] ?? responses[pathname] ?? {};
    return route.fulfill({ status: 200, contentType: 'application/json', body: JSON.stringify(body) });
  });
}
