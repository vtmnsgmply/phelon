import assert from 'node:assert/strict';
import { spawn } from 'node:child_process';
import { once } from 'node:events';
import net from 'node:net';
import { resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { setTimeout as delay } from 'node:timers/promises';

const catalog = [
  ['private-readiness-life-planning', 'Private Readiness &amp; Life Planning'],
  ['family-household-stability', 'Family &amp; Household Stability'],
  ['financial-stability', 'Financial Stability'],
  ['business-career-continuity', 'Business &amp; Career Continuity'],
  ['reputation-digital-presence', 'Reputation &amp; Digital Presence'],
  ['federal-prison-readiness', 'Federal &amp; Prison Readiness'],
  ['productive-time-planning', 'Productive-Time Planning'],
  ['release-reentry-preparation', 'Release &amp; Reentry Preparation'],
  ['90-day-ongoing-support', '90-Day &amp; Ongoing Support'],
];
const reputation = 'reputation-digital-presence';
const subtypes = ['personal-reputation-readiness', 'executive-reputation-business-continuity'];
const cta = 'Schedule a Free 15-Minute Discovery Call';
const disclaimer = 'The PHELONPRENEUR Network provides confidential, non-legal readiness and life-stability support. We do not provide legal advice, legal representation, financial advice, tax advice, therapy, medical advice, or guarantees of legal, financial, professional, or other outcomes.';
const testRoot = fileURLToPath(new URL('../', import.meta.url));

export async function withTestServer(mode, test, options = {}) {
  const listener = net.createServer();
  listener.listen(0, '127.0.0.1');
  await once(listener, 'listening');
  const { port } = listener.address();
  await new Promise((resolve, reject) => listener.close(error => error ? reject(error) : resolve()));
  const origin = 'http://127.0.0.1:' + port;
  const server = spawn(process.env.PHELON_PHP || 'php', ['-S', '127.0.0.1:' + port, 'tests/services-router.php'], {
    cwd: testRoot,
    env: { ...process.env, PHELON_TEST_NATIVE_API: '0', PHELON_SESSION_PATH: options.sessionPath || '', ...options.env },
    windowsHide: true,
  });
  let startupError;
  server.on('error', error => { startupError = error; });
  let serverIssues = '';
  server.stderr.on('data', output => {
    if (/warning|fatal|parse error|Phelon API failed/i.test(output.toString())) serverIssues += output;
  });
  try {
    for (let attempt = 0; ; attempt++) {
      if (startupError) throw startupError;
      if (server.exitCode !== null || attempt >= 50) throw new Error('PHP loopback server did not start.');
      try {
        if ((await fetch(origin + '/css/services.css', { signal: AbortSignal.timeout(500) })).ok) break;
      } catch {}
      await delay(50);
    }
    await test(origin);
    assert.equal(serverIssues, '', 'PHP emitted warnings or errors');
  } catch (error) {
    if (serverIssues) console.error(serverIssues);
    throw error;
  } finally {
    if (server.pid && server.exitCode === null) {
      const stopped = once(server, 'exit');
      server.kill();
      await stopped;
    }
  }
}

async function fetchPage(url, status = 200) {
  const response = await fetch(url, { signal: AbortSignal.timeout(3000) });
  assert.equal(response.status, status, url);
  return response.text();
}

function noPricing(markup) {
  assert.doesNotMatch(markup, /\$\s*\d|<a[^>]+href="[^"]*(?:checkout|\/payment)/i);
  assert.doesNotMatch(markup, /openBookingSwal|programs__price/);
}

function menuEntries(markup, menu) {
  const list = markup.match(new RegExp('<ul[^>]*id="' + menu + '"[^>]*>([\\s\\S]*?)</ul>'))?.[1] ?? '';
  return Array.from(list.matchAll(/<a href="Service\.php\?service=([^"]+)"[^>]*>([^<]+)</g), match => match.slice(1));
}

function assertSelected(markup, value) {
  const selected = Array.from(markup.matchAll(/<option value="([^"]*)" selected>/g), match => match[1]);
  assert.deepEqual(selected, [value]);
  assert.match(markup, /data-booking-picker/);
  assert.doesNotMatch(markup, /<iframe/i);
}

async function smoke() {
  await withTestServer('offline', async origin => {
    for (const prefix of ['', '/Phelon']) {
      const base = origin + prefix + '/';
      const titles = new Set();
      const descriptions = new Set();
      const home = await fetchPage(base + 'index');
      const overview = await fetchPage(base + 'Services.php');
      for (const page of [home, overview]) {
        for (const device of ['desktop', 'mobile']) {
          assert.deepEqual(menuEntries(page, 'services-menu-' + device), catalog);
        }
        const directory = page.match(/<ul class="service-directory__links">([\s\S]*?)<\/ul>/)?.[1] ?? '';
        for (const [slug] of catalog) assert.ok(directory.includes('Service.php?service=' + slug));
        noPricing(page);
      }

      for (const [slug, title] of catalog) {
        const detail = await fetchPage(base + 'Service.php?service=' + slug);
        assert.ok(detail.includes('<h1>' + title + '</h1>'));
        const main = detail.match(/<main class="service-detail">([\s\S]*?)<\/main>/)[1];
        assert.match(main, /How We Help/);
        assert.match(main, /Who This Service Is For/);
        assert.equal([...main.matchAll(/<li>/g)].length, 4);
        assert.match(main, /Start With a Private Conversation/);
        assert.ok(detail.match(/<footer[\s\S]+?<\/footer>/)[0].includes(disclaimer));
        const call = 'Book.php?service=' + slug + '#booking';
        assert.equal(main.split('href="' + call + '">' + cta + '</a>').length - 1, 2);
        assert.ok(detail.includes('rel="canonical" href="https://www.phelonpreneurnetwork.com/Service.php?service=' + slug + '"'));
        titles.add(detail.match(/<title>(.*?)<\/title>/)[1]);
        descriptions.add(detail.match(/name="description" content="([^"]+)"/)[1]);
        noPricing(detail);
        const booking = await fetchPage(new URL(call, base));
        assertSelected(booking, slug);
        noPricing(booking);
        if (slug === reputation) {
          for (const subtype of subtypes) {
            const link = 'Book.php?service=' + slug + '&subservice=' + subtype + '#booking';
            assert.ok(main.includes('href="' + link.replace('&', '&amp;') + '">' + cta));
            assertSelected(await fetchPage(new URL(link, base)), slug + '/' + subtype);
            assertSelected(await fetchPage(base + 'Book.php?interest=' + encodeURIComponent(slug + '/' + subtype)), slug + '/' + subtype);
          }
        }
      }
      assert.equal(titles.size, 9);
      assert.equal(descriptions.size, 9);
      assertSelected(await fetchPage(base + 'Book'), '');
      assertSelected(await fetchPage(base + 'Book.php?interest='), '');
      for (const invalid of ['missing', 'service[]=financial-stability', 'service=%3Cscript%3E']) {
        const query = invalid === 'missing' ? '?service=unknown' : '?' + invalid;
        const failure = await fetchPage(base + 'Service.php' + query, 404);
        assert.match(failure, /Service not found/);
      }
      assert.match(await fetchPage(base + 'Service.php', 404), /Service not found/);
      for (const query of [
        'service=unknown', 'service=financial-stability&subservice=' + subtypes[0], 'subservice=' + subtypes[1],
        'service[]=financial-stability', 'interest[]=financial-stability', 'interest=financial-stability/',
        'service=financial-stability&interest=financial-stability', 'service=%3Cscript%3E',
      ]) {
        const rejected = await fetchPage(base + 'Book.php?' + query, 400);
        assert.match(rejected, /recognize that service selection/);
        assert.doesNotMatch(rejected, /data-booking-picker|<iframe/i);
      }
      for (const url of ['Contact.php', 'Intake.php', 'webinar/index.php', 'webinar/Payment-497.php', 'Login/',
        'css/style.css', 'css/services.css', 'js/services-nav.js', 'images/logo.png']) {
        await fetchPage(base + url);
      }
      await fetchPage(base + '.env', 404);
      await fetchPage(base + 'webhook.php', 410);
      const webinar = await fetchPage(base + 'webinar/index.php');
      assert.match(webinar, /href="\.\.\/Contact.php"/);
      assert.doesNotMatch(webinar, /<iframe/i);
      const nextStep = await fetch(base + 'webinar/Payment-997.php', { redirect: 'manual' });
      assert.equal(nextStep.status, 302);
      assert.match(nextStep.headers.get('location'), /^\.\.\/Book\.php\?/);
      console.log('PASS ' + (prefix || '/') + ' 9 service routes, 11 booking contexts, titles, menus, prices, invalid routes and legacy GET pages');
    }
  });

}

if (process.argv[1] && fileURLToPath(import.meta.url) === resolve(process.argv[1])) {
  await smoke();
}
