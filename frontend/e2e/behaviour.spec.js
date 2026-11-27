// frontend/e2e/behaviour.spec.js
import { test, expect } from '@playwright/test';
import { mockApi, slots } from './mocks.js';

const routes = [
  '/', '/services', '/services/reputation-digital-presence', '/services/not-a-service', '/book', '/intake', '/intake?reference=TEST',
  '/webinar', '/webinar/checkout/47', '/webinar/resources', '/about', '/start-here', '/contact',
  '/privacy-policy', '/terms-of-service', '/cookie-policy', '/disclaimer', '/thank-you', '/thanks', '/no-such-page',
];

test.beforeEach(async ({ page }) => { await mockApi(page); });

test.describe('shell', () => {
  test('mobile menu makes the page inert, closes on Escape and returns focus', async ({ page }, info) => {
    test.skip(info.project.name !== 'mobile');
    await page.goto('/');
    const button = page.getByRole('button', { name: 'Open menu' });
    await button.click();
    await expect(page.locator('main')).toHaveAttribute('inert', '');
    await expect(page.locator('footer')).toHaveAttribute('inert', '');
    await expect(page.getByRole('navigation', { name: 'Menu' })).toBeVisible();
    await page.keyboard.press('Escape');
    await expect(page.locator('main')).not.toHaveAttribute('inert', '');
    await expect(page.getByRole('button', { name: 'Open menu' })).toBeFocused();
  });

  test('the header booking button is a filled, readable button', async ({ page }, info) => {
    test.skip(info.project.name !== 'desktop');
    for (const path of ['/', '/about', '/privacy-policy']) {
      await page.goto(path);
      const button = page.getByRole('navigation', { name: 'Main' }).getByRole('link', { name: 'Book a free discovery call' });
      await expect(button).toHaveCSS('background-color', 'rgb(233, 201, 134)');
      await expect(button).toHaveCSS('color', 'rgb(27, 20, 28)');
    }
  });

  test('menu closes after navigating', async ({ page }, info) => {
    test.skip(info.project.name !== 'mobile');
    await page.goto('/');
    await page.getByRole('button', { name: 'Open menu' }).click();
    await page.getByRole('navigation', { name: 'Menu' }).getByRole('link', { name: 'Contact' }).click();
    await expect(page).toHaveURL(/\/contact$/);
    await expect(page.locator('#menu-sheet')).toBeHidden();
    await expect(page.locator('main')).toBeFocused();
  });

  test('services panel closes on Escape and on outside click', async ({ page }, info) => {
    test.skip(info.project.name !== 'desktop');
    await page.goto('/');
    const trigger = page.getByRole('button', { name: 'Services' });
    await trigger.click();
    await expect(trigger).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#services-panel li')).toHaveCount(9);
    await page.keyboard.press('Escape');
    await expect(page.locator('#services-panel')).toHaveCount(0);
    await expect(trigger).toBeFocused();
    await trigger.click();
    await page.mouse.click(8, 700);
    await expect(page.locator('#services-panel')).toHaveCount(0);
  });

  test('sticky call to action appears after the hero and is absent on booking', async ({ page }, info) => {
    test.skip(info.project.name !== 'mobile');
    await page.goto('/');
    const cta = page.locator('.ph-sticky-cta');
    await expect(cta).toBeHidden();
    await page.locator('#who').scrollIntoViewIfNeeded();
    await expect(cta).toBeVisible();
    await page.goto('/book');
    await expect(cta).toHaveCount(0);
  });
});

for (const [path, id] of [['/about#how-we-work', 'how-we-work'], ['/webinar#registration', 'registration'], ['/webinar#learn', 'learn'], ['/#programs', 'programs'], ['/#who', 'who']]) {
  test(`cold load scrolls to ${path}`, async ({ page }) => {
    await page.goto(path);
    await expect.poll(() => page.evaluate(target => Math.round(document.getElementById(target)?.getBoundingClientRect().top ?? 9999), id), { timeout: 6000 }).toBeLessThan(140);
    expect(await page.evaluate(() => scrollY)).toBeGreaterThan(0);
  });
}

test('unknown service keys render the 404 page', async ({ page }) => {
  for (const key of ['constructor', '__proto__', 'nope']) {
    await page.goto('/services/' + key);
    await expect(page.locator('h1')).toHaveText('We couldn’t find that page');
    await expect(page).toHaveTitle(/^Page not found \|/);
  }
});

test('a table of contents click keeps the policy title', async ({ page }, info) => {
  await page.goto('/privacy-policy');
  await page.locator('main h1').waitFor();
  const title = await page.title();
  expect(title).toBe('Privacy Policy | The PHELONPRENEUR Network');
  if (info.project.name === 'mobile') await page.locator('.ph-toc-mobile summary').click();
  await page.locator('a[href="#security"]:visible').click();
  await expect(page).toHaveURL(/#security$/);
  await page.waitForTimeout(300);
  expect(await page.title()).toBe(title);
});

test('every policy table of contents link has a target', async ({ page }) => {
  for (const slug of ['privacy-policy', 'terms-of-service', 'cookie-policy', 'disclaimer']) {
    await page.goto('/' + slug);
    await page.locator('main h1').waitFor();
    const missing = await page.evaluate(() => [...document.querySelectorAll('main a[href^="#"]')].map(a => a.getAttribute('href').slice(1)).filter(id => !document.getElementById(id)));
    expect(missing, slug).toEqual([]);
    expect(await page.locator('.ph-toc-desktop li').count(), slug).toBeGreaterThan(2);
  }
});

test('reduced motion shows everything immediately', async ({ page }) => {
  await page.emulateMedia({ reducedMotion: 'reduce' });
  await page.goto('/');
  await page.locator('main h1').waitFor();
  const hidden = await page.evaluate(() => [...document.querySelectorAll('[data-reveal] > *, .ph-hero-copy, .ph-hero-chip')].filter(e => getComputedStyle(e).opacity !== '1').length);
  expect(hidden).toBe(0);
  expect(await page.evaluate(() => getComputedStyle(document.querySelector('.ph-marquee-track')).animationName)).toBe('none');
});

test('unverified claims, passed dates and the legal-guidance image are not published', async ({ page }) => {
  for (const path of ['/', '/about', '/contact', '/webinar', '/thank-you']) {
    await page.goto(path);
    await page.locator('main h1').waitFor();
    const text = await page.locator('body').innerText();
    expect(text, path).not.toMatch(/98%|15\+|24\/7|M\.S\.|J\.K\.|August 22|September 5|Click to copy|Within an hour/i);
    expect(await page.locator('img[src*="guide"]').count()).toBe(0);
    await expect(page.locator('footer')).toContainText('We do not provide legal advice');
  }
  await page.goto('/webinar');
  await expect(page.locator('main details summary')).toHaveCount(7);
  await expect(page.getByRole('link', { name: 'Ask to be told about the next session' }).first()).toHaveAttribute('href', '/contact');
});

test('booking sends exactly the same request fields', async ({ page }) => {
  let sent;
  await page.route('**/api/bookings', route => {
    sent = route.request().postDataJSON();
    return route.fulfill({ status: 201, contentType: 'application/json', body: JSON.stringify({ reference: 'A'.repeat(24), startsAt: slots[1] }) });
  });
  await page.goto('/book?service=reputation-digital-presence&subservice=personal-reputation-readiness');
  await expect(page.locator('.ph-summary')).toContainText('Personal reputation readiness');
  await expect(page.getByRole('button', { name: 'Confirm appointment' })).toBeDisabled();
  await page.locator('.ph-slot').nth(1).click();
  await expect(page.locator('.ph-slot').nth(1)).toHaveAttribute('aria-pressed', 'true');
  await page.getByLabel('Full name').fill('Test Person');
  await page.getByLabel('Email', { exact: true }).fill('test@example.com');
  await page.getByLabel('Phone').fill('12345');
  await page.getByLabel('State or territory').fill('zz');
  await page.getByLabel('Main area of concern').fill('General planning');
  await expect(page.getByText('Enter a phone number with at least 7 digits')).toBeVisible();
  await expect(page.getByText('Enter a two-letter US state or territory code')).toBeVisible();
  await page.getByRole('button', { name: 'Confirm appointment' }).click();
  expect(sent).toBeUndefined();
  await page.getByLabel('Phone').fill('(555) 123-4567');
  await page.getByLabel('State or territory').fill('ny');
  await page.getByRole('button', { name: 'Confirm appointment' }).click();
  await expect(page.getByRole('heading', { name: 'Your appointment is confirmed' })).toBeVisible();
  expect(Object.keys(sent).sort()).toEqual(['concern', 'date', 'email', 'interest', 'name', 'phone', 'start', 'state', 'timezone']);
  expect(sent).toMatchObject({ date: '2030-01-07', start: slots[1], state: 'ny', interest: 'reputation-digital-presence/personal-reputation-readiness' });
  await expect(page.getByRole('link', { name: 'Complete the optional intake' })).toHaveAttribute('href', '/intake?appointmentId=' + 'A'.repeat(24));
});

test('booking handles a taken time and an unreachable service', async ({ page }) => {
  await page.route('**/api/bookings', route => route.fulfill({ status: 409, contentType: 'application/json', body: '{"message":"taken"}' }));
  await page.goto('/book');
  await page.locator('.ph-slot').first().click();
  await page.getByLabel('Full name').fill('Test Person');
  await page.getByLabel('Email', { exact: true }).fill('test@example.com');
  await page.getByLabel('Phone').fill('5551234567');
  await page.getByLabel('State or territory').fill('NY');
  await page.getByLabel('Main area of concern').fill('General planning');
  await page.getByRole('button', { name: 'Confirm appointment' }).click();
  await expect(page.getByRole('alert')).toContainText('That time was just taken. Pick another.');

  await page.route('**/api/availability*', route => route.abort());
  await page.goto('/book');
  await expect(page.getByRole('alert')).toContainText('We couldn’t load available times.');
  await expect(page.getByRole('button', { name: 'Try again' })).toBeVisible();
});

test('intake completes all steps, sends the same fields and stores nothing', async ({ page }) => {
  test.setTimeout(90_000);
  let body;
  await page.route(url => url.pathname === '/api/intake', route => {
    body = route.request().postDataBuffer().toString('latin1');
    return route.fulfill({ status: 201, contentType: 'application/json', body: '{"submitted":true}' });
  });
  await page.goto('/intake?appointmentId=TEST');
  await expect(page.getByText('Your answers are not saved if you leave this page.')).toBeVisible();
  for (let step = 1; step <= 11; step++) {
    await expect(page.getByText(`Step ${step} of 11`)).toBeVisible();
    const form = page.locator('.ph-step');
    for (const input of await form.locator('input[required]:not([type=checkbox]), textarea[required]').all()) {
      const type = await input.getAttribute('type');
      await input.fill(type === 'email' ? 'test@example.com' : 'Test');
    }
    for (const select of await form.locator('select[required]').all()) await select.selectOption({ index: 1 });
    for (const box of await form.locator('input[type=checkbox][required]').all()) await box.check();
    if (await form.locator('fieldset').count()) await form.locator('fieldset input').first().check();
    if (step === 10) {
      await form.locator('input[type=file]').setInputFiles({ name: 'note.pdf', mimeType: 'application/pdf', buffer: Buffer.from('%PDF-1.4 test') });
      await expect(form.getByRole('button', { name: 'Remove note.pdf' })).toBeVisible();
      await form.locator('input[type=file]').setInputFiles({ name: 'big.png', mimeType: 'image/png', buffer: Buffer.alloc(5 * 1024 * 1024 + 1) });
      await expect(page.getByRole('alert')).toContainText('big.png is larger than 5 MB.');
    }
    expect(await page.evaluate(() => localStorage.length + sessionStorage.length + document.cookie.length)).toBe(0);
    await page.getByRole('button', { name: step === 11 ? 'Submit intake' : 'Continue' }).click();
  }
  await expect(page.getByRole('heading', { name: 'Intake already received' })).toBeVisible();
  for (const field of ['name="reference"', 'name="answers"', 'name="files[]"; filename="note.pdf"']) expect(body).toContain(field);
  expect(body).not.toContain('big.png');
  expect(new URL(page.url()).search).toBe('?appointmentId=TEST');
});

test('intake warns before an in-app link discards answers', async ({ page }) => {
  await page.goto('/intake?appointmentId=TEST');
  await page.locator('.ph-step input').first().fill('Test');
  let asked = '';
  page.once('dialog', dialog => { asked = dialog.message(); dialog.dismiss(); });
  await page.locator('footer').getByRole('link', { name: 'About', exact: true }).click();
  expect(asked).toBe('Your answers are not saved. Leave this page?');
  await expect(page).toHaveURL(/\/intake/);
});

test('checkout and library use the same endpoints', async ({ page }) => {
  await page.goto('/webinar/checkout/47');
  await expect(page.getByRole('link', { name: 'Continue to secure checkout' })).toHaveAttribute('href', 'https://link.fastpaydirect.com/payment-link/test');
  const sent = [];
  await page.route('**/api/library/*', route => {
    sent.push([new URL(route.request().url()).pathname, route.request().postDataJSON()]);
    return route.fulfill({ status: 200, contentType: 'application/json', body: '{"message":"Sent if eligible.","document":"/api/library/document"}' });
  });
  await page.goto('/webinar/resources?transactionId=T1');
  await page.getByRole('button', { name: 'Send my access again' }).click();
  await expect(page.getByText('Sent if eligible.')).toBeVisible();
  await page.getByLabel('Access code').fill('123456');
  await page.getByRole('button', { name: 'Verify access' }).click();
  await expect(page.getByRole('link', { name: 'Download your resource' })).toHaveAttribute('href', '/api/library/document');
  expect(sent).toEqual([['/api/library/resend', { transaction: 'T1' }], ['/api/library/verify', { transaction: 'T1', code: '123456' }]]);
});

test('no horizontal overflow at seven viewports, one h1, no skipped headings, no other origins', async ({ page }, info) => {
  test.skip(info.project.name !== 'desktop');
  test.setTimeout(300_000);
  const foreign = new Set();
  page.on('request', request => { const url = new URL(request.url()); if (url.protocol.startsWith('http') && url.hostname !== '127.0.0.1') foreign.add(url.origin); });
  const problems = [];
  for (const [width, height] of [[320, 640], [360, 740], [390, 844], [768, 1024], [1024, 768], [1366, 768], [1920, 1080]]) {
    await page.setViewportSize({ width, height });
    for (const path of routes) {
      await page.goto(path);
      await page.waitForLoadState('networkidle');
      const result = await page.evaluate(() => {
        const levels = [...document.querySelectorAll('main h1, main h2, main h3, main h4')].filter(e => e.offsetParent || e.classList.contains('sr-only')).map(e => Number(e.tagName[1]));
        // Anything sticking out of the viewport, ignoring deliberately scrollable or clipped strips.
        const wide = [...document.querySelectorAll('body *')].filter(e => !e.closest('.ph-rail, .ph-marquee, .ph-dates, .ph-sheet, .ph-sticky-cta, .ph-skip, .ph-skeleton, .ph-button, .ph-glow > svg'))
          .filter(e => { const r = e.getBoundingClientRect(); return r.width && (r.right > innerWidth + 1 || r.left < -1); }).slice(0, 3).map(e => e.tagName + '.' + e.getAttribute('class'));
        return { overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth, h1: document.querySelectorAll('h1').length,
          skipped: levels.some((level, i) => i && level - levels[i - 1] > 1), wide };
      });
      if (result.overflow > 0 || result.h1 !== 1 || result.skipped || result.wide.length) problems.push({ width, path, ...result });
    }
  }
  expect(problems).toEqual([]);
  expect([...foreign]).toEqual([]);
});
