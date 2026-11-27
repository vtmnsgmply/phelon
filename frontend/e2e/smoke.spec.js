// frontend/e2e/smoke.spec.js
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';
import { mockApi } from './mocks.js';

const SERVICE_KEY = 'reputation-digital-presence'; // a real key from services.json
const routes = [
  '/', '/services', `/services/${SERVICE_KEY}`, '/services/not-a-service', '/book', '/intake?reference=TEST',
  '/webinar', '/webinar/checkout/47', '/webinar/resources', '/about', '/start-here', '/contact',
  '/privacy-policy', '/terms-of-service', '/cookie-policy', '/disclaimer', '/thank-you', '/no-such-page',
];

test.beforeEach(async ({ page }) => {
  // The API may be offline. Keep the UI deterministic with the real response shapes.
  await mockApi(page);
});

for (const path of routes) {
  test(`${path} renders cleanly`, async ({ page }, info) => {
    const problems = [];
    page.on('pageerror', (e) => problems.push(`pageerror: ${e.message}`));
    page.on('console', (m) => m.type() === 'error' && problems.push(`console: ${m.text()}`));
    page.on('requestfailed', (r) => problems.push(`request failed: ${r.url()}`));

    await page.goto(path);
    await page.waitForLoadState('networkidle');

    if (process.env.SHOTS) {
      await page.emulateMedia({ reducedMotion: 'reduce' });
      const name = path.replace(/[^a-z0-9]+/gi, '_') || '_home';
      await page.screenshot({ path: `qa/${process.env.SHOTS}/${info.project.name}${name}.png`, fullPage: true });
    }

    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    expect(overflow, 'horizontal overflow').toBeLessThanOrEqual(0);
    await expect(page.locator('main h1')).toHaveCount(1);
    if (path !== '/') expect(await page.title()).toMatch(/\|/);   // every inner route has a specific title

    const axe = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa']).analyze();
    expect(axe.violations.filter((v) => ['serious', 'critical'].includes(v.impact))).toEqual([]);
    expect(problems).toEqual([]);
  });
}
