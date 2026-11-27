import assert from 'node:assert/strict';
import { spawn } from 'node:child_process';
import { once } from 'node:events';
import { mkdtemp, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { dirname, join } from 'node:path';
import { setTimeout as delay } from 'node:timers/promises';
import { withTestServer } from './services-smoke.mjs';

const chrome = process.env.PHELON_CHROME;
if (!chrome) throw new Error('Set PHELON_CHROME to a locally installed Chromium executable.');
const temp = tmpdir();
const profile = await mkdtemp(join(temp, 'phelon-services-browser-'));
const browser = spawn(chrome, [
  '--headless', '--remote-debugging-pipe', '--user-data-dir=' + profile,
  '--disable-gpu', '--disable-background-networking', '--disable-component-update', '--no-first-run',
  '--no-default-browser-check', '--no-proxy-server',
  '--host-resolver-rules=MAP * ~NOTFOUND, EXCLUDE 127.0.0.1', 'about:blank',
], { windowsHide: true, stdio: ['ignore', 'ignore', 'pipe', 'pipe', 'pipe'] });

let id = 0;
let buffer = '';
let browserError;
let browserLog = '';
const replies = new Map();
const events = new Map();
browser.on('error', error => { browserError = error; });
browser.stderr.on('data', output => { browserLog = (browserLog + output).slice(-2500); });
browser.stdio[3].on('error', error => { browserError = error; });
browser.stdio[4].setEncoding('utf8');
browser.stdio[4].on('error', error => { browserError = error; });
browser.stdio[4].on('data', chunk => {
  buffer += chunk;
  while (buffer.includes('\0')) {
    const end = buffer.indexOf('\0');
    const message = JSON.parse(buffer.slice(0, end));
    buffer = buffer.slice(end + 1);
    if (message.id) {
      replies.get(message.id)?.(message);
    } else {
      events.get(message.method)?.(message.params, message.sessionId);
    }
  }
});

function command(method, params = {}, sessionId) {
  const callId = ++id;
  return new Promise((resolve, reject) => {
    const timer = setTimeout(() => {
      replies.delete(callId);
      reject(browserError || new Error('Browser command did not respond: ' + method + '\n' + browserLog));
    }, 8000);
    replies.set(callId, message => {
      clearTimeout(timer);
      replies.delete(callId);
      if (message.error) reject(new Error(method + ': ' + message.error.message));
      else resolve(message.result);
    });
    browser.stdio[3].write(JSON.stringify({ id: callId, method, params, sessionId }) + '\0');
  });
}

try {
  await withTestServer('offline', async origin => {
    const { targetInfos } = await command('Target.getTargets');
    const { targetId } = targetInfos.find(target => target.type === 'page')
      || await command('Target.createTarget', { url: 'about:blank' });
    const { sessionId } = await command('Target.attachToTarget', { targetId, flatten: true });
    const cdp = (method, params) => command(method, params, sessionId);
    const issues = [];
    const loaded = new Set();
    events.set('Page.lifecycleEvent', event => {
      if (event.name === 'load') loaded.add(event.loaderId);
    });
    events.set('Fetch.requestPaused', ({ requestId, request, resourceType }, session) => {
      if (session !== sessionId) return;
      const responseHeaders = [{ name: 'Content-Type', value: resourceType === 'Script' ?
        'application/javascript' : resourceType === 'Stylesheet' ? 'text/css' : 'text/plain' }];
      const call = request.url.startsWith(origin + '/')
        ? cdp('Fetch.continueRequest', { requestId })
        : cdp('Fetch.fulfillRequest', { requestId, responseCode: 200, responseHeaders, body: '' });
      call.catch(error => issues.push(error.message));
    });
    events.set('Runtime.exceptionThrown', ({ exceptionDetails }) => {
      issues.push(exceptionDetails.exception?.description || exceptionDetails.text);
    });
    events.set('Network.responseReceived', ({ response }) => {
      if (response.url.startsWith(origin + '/') && response.status >= 400) issues.push(response.url + ' ' + response.status);
    });
    await cdp('Page.enable');
    await cdp('Page.setLifecycleEventsEnabled', { enabled: true });
    await cdp('Runtime.enable');
    await cdp('Network.enable');
    await cdp('Fetch.enable', { patterns: [{ urlPattern: '*' }] });
    const evaluate = async expression => {
      const result = await cdp('Runtime.evaluate', { expression, returnByValue: true });
      assert.ok(!result.exceptionDetails, result.exceptionDetails?.exception?.description);
      return result.result.value;
    };
    const waitFor = async expression => {
      for (let i = 0; i < 100; i++) {
        if (await evaluate(expression)) return;
        await delay(40);
      }
      throw new Error('Browser condition not met: ' + expression + ' (observed ' +
        await evaluate('document.readyState + " " + location.href') + ')');
    };
    const navigate = async path => {
      const url = origin + '/Phelon/' + path;
      const navigation = await cdp('Page.navigate', { url });
      assert.ok(!navigation.errorText, url + ': ' + navigation.errorText);
      if (navigation.loaderId) {
        for (let i = 0; i < 100 && !loaded.has(navigation.loaderId); i++) await delay(40);
        assert.ok(loaded.has(navigation.loaderId), 'Page did not finish loading: ' + url);
      }
      await waitFor('document.readyState === "complete" && location.href === ' + JSON.stringify(url));
    };
    const key = async (name, code) => {
      const base = { key: name, code: name, windowsVirtualKeyCode: code, nativeVirtualKeyCode: code };
      const down = name === 'Enter'
        ? { ...base, type: 'keyDown', text: '\r', unmodifiedText: '\r' }
        : { ...base, type: 'rawKeyDown' };
      await cdp('Input.dispatchKeyEvent', down);
      await cdp('Input.dispatchKeyEvent', { ...base, type: 'keyUp' });
    };
    const paths = [
      'Service.php?service=reputation-digital-presence',
      'Services.php',
      'Book.php?service=reputation-digital-presence&subservice=executive-reputation-business-continuity',
      'Login/',
    ];

    for (const width of [320, 375, 768, 1024, 1440]) {
      await cdp('Emulation.setDeviceMetricsOverride', { width, height: 850, deviceScaleFactor: 1, mobile: false });
      for (const path of paths) {
        await navigate(path);
        const overflow = await evaluate(`document.documentElement.scrollWidth > innerWidth
          ? [...document.querySelectorAll('body *')]
            .filter(el => el.getBoundingClientRect().right > innerWidth + 1)
            .slice(0, 12).map(el => el.tagName + ' ' + el.className)
          : null`);
        assert.equal(overflow, null, width + 'px: ' + path + ' ' + JSON.stringify(overflow));
        if (path === 'Login/') {
          await waitFor('document.querySelector(".portal form button").disabled === false');
          assert.ok(await evaluate('document.querySelector(".brand-link img").naturalWidth > 0'));
        } else {
          assert.ok(await evaluate('document.querySelector(".nav-brand").getBoundingClientRect().left >= 0'));
        }
        if (path.startsWith('Book.php')) {
          await waitFor('document.querySelector("select[name=start]").options.length > 1');
          assert.equal(await evaluate('document.querySelector("select[name=interest]").value'),
            'reputation-digital-presence/executive-reputation-business-continuity');
        }
      }
      await navigate(paths[0]);
      const mobile = width < 1120;
      const variant = mobile ? 'mobile' : 'desktop';
      const summary = 'document.querySelector(\'summary[aria-controls="services-menu-' + variant + '"]\')';
      const button = 'document.getElementById("mobileMenuBtn")';
      if (mobile) {
        await evaluate(button + '.focus()');
        assert.ok(await evaluate('document.activeElement === ' + button));
        await key('Enter', 13);
        await waitFor(button + '.getAttribute("aria-expanded") === "true"');
        assert.ok(await evaluate('document.activeElement === ' + summary), await evaluate(`
          JSON.stringify({
            focus: document.activeElement.outerHTML.slice(0, 180),
            menu: document.getElementById('mobileMenu').className,
            summary: ${summary}.outerHTML,
            summaryVisibility: getComputedStyle(${summary}).visibility,
            issue: ${JSON.stringify(issues)}
          })
        `));
      } else {
        await evaluate(summary + '.focus()');
      }
      await key('Enter', 13);
      await waitFor(summary + '.getAttribute("aria-expanded") === "true"');
      assert.equal(await evaluate('document.querySelectorAll("#services-menu-' + variant + ' a").length'), 9);
      assert.ok(await evaluate('document.querySelector("#services-menu-' + variant + '").getBoundingClientRect().right <= innerWidth'));
      await key('Tab', 9);
      assert.ok(await evaluate('document.activeElement === document.querySelector("#services-menu-' + variant + ' a")'));
      await key('Escape', 27);
      await waitFor(summary + '.getAttribute("aria-expanded") === "false"');
      assert.ok(await evaluate('document.activeElement === ' + summary));
      if (mobile) {
        await key('Escape', 27);
        await waitFor(button + '.getAttribute("aria-expanded") === "false"');
        assert.ok(await evaluate('document.activeElement === ' + button));
      }
      console.log('PASS ' + width + 'px service, booking and staff login layouts; ' + variant + ' keyboard menu');
    }
    assert.deepEqual(issues, []);
    console.log('PASS no first-party browser exceptions or failed asset requests');
  });
} finally {
  if (browser.pid && browser.exitCode === null) {
    const stopped = once(browser, 'exit');
    try { await command('Browser.close'); } catch { browser.kill(); }
    await stopped;
  }
  if (dirname(profile) === temp) await rm(profile, { recursive: true, force: true, maxRetries: 3 });
}
