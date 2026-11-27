import assert from 'node:assert/strict'
import { spawn } from 'node:child_process'
import { once } from 'node:events'
import { randomBytes } from 'node:crypto'
import { mkdtemp, rm, writeFile } from 'node:fs/promises'
import net from 'node:net'
import { tmpdir } from 'node:os'
import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'
import { setTimeout as delay } from 'node:timers/promises'

const backend = fileURLToPath(new URL('../', import.meta.url))
const php = process.env.PHELON_PHP || 'php'
const temp = tmpdir()
const fixture = await mkdtemp(join(temp, 'phelon-react-test-'))
const dbPath = join(fixture, 'fixture.sqlite')
await writeFile(dbPath, '')
const env = {
  ...process.env, PHELON_TEST_MODE: '1', APP_ENV: 'local', APP_DEBUG: 'false',
  APP_KEY: 'base64:' + randomBytes(32).toString('base64'),
  DB_CONNECTION: 'sqlite', DB_DATABASE: dbPath, DB_URL: '', SESSION_DRIVER: 'file',
  SESSION_SECURE_COOKIE: 'false', SESSION_DOMAIN: '', CACHE_STORE: 'array', MAIL_MAILER: 'array',
  LOG_CHANNEL: 'stderr', LOG_LEVEL: 'error', PHELON_BOOKING_TIMEZONE: 'UTC',
  PHELON_BOOKING_OPEN: '09:00', PHELON_BOOKING_CLOSE: '10:00', PHELON_BOOKING_DAYS: '1,2,3,4,5,6,7',
  PHELON_MIGRATION_ADMINS: 'staff@example.test',
  PHELON_LIBRARY_ENABLED: 'false', PHELON_LEGACY_INTAKE_ENABLED: 'false',
}

async function exec(program, args, options = {}) {
  const child = spawn(program, args, { cwd: backend, env, windowsHide: true, ...options })
  let stdout = '', stderr = ''
  child.stdout.on('data', data => { stdout += data })
  child.stderr.on('data', data => { stderr += data })
  const [status] = await once(child, 'close')
  if (status !== 0) throw new Error(program + ' exited ' + status + '\n' + stdout + stderr)
  return stdout
}

let server
try {
  await exec(php, ['artisan', 'migrate', '--force', '--no-interaction'])
  await exec(php, ['tests/Fixtures/browser-staff.php'])
  const tempListener = net.createServer()
  tempListener.listen(0, '127.0.0.1')
  await once(tempListener, 'listening')
  const { port } = tempListener.address()
  const origin = 'http://127.0.0.1:' + port
  await new Promise((resolve, reject) => tempListener.close(error => error ? reject(error) : resolve()))
  server = spawn(php, ['-S', '127.0.0.1:' + port,
    join(backend, 'vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php')],
  { cwd: join(backend, 'public'), env: { ...env, APP_URL: origin }, windowsHide: true })
  let serverLog = '', startupError
  server.on('error', error => { startupError = error })
  server.stderr.on('data', output => { serverLog = (serverLog + output).slice(-2500) })
  for (let attempt = 0; ; attempt++) {
    if (startupError) throw startupError
    if (attempt >= 50 || server.exitCode !== null) throw new Error('Loopback server did not start: ' + serverLog)
    try { if ((await fetch(origin + '/up', { signal: AbortSignal.timeout(500) })).ok) break } catch {}
    await delay(100)
  }
  for (const [route, status] of [
    ['/',200],['/services',200],['/services/financial-stability',200],['/services/unknown',404],
    ['/book',200],['/book?service=unknown',400],['/login/',200],['/about',200],['/contact',200],['/privacy-policy',200],
    ['/webinar/resources',200],['/intake',200],['/Book.php?service=financial-stability',301],
    ['/webhook.php',410],['/.env',404],['/legacy/Book.php',404],
  ]) {
    const response = await fetch(origin + route, { redirect: 'manual' })
    assert.equal(response.status, status, route)
    if (status === 200) {
      const markup = await response.text()
      const bundle = markup.match(/src="(\/(?:site|staff)\/assets\/[^"]+\.js)"/)?.[1]
      assert.ok(bundle, 'React script not found on ' + route)
      assert.ok((await fetch(origin + bundle)).ok, bundle)
      if (route.startsWith('/services/')) assert.match(markup, /<title>Financial Stability \| The PHELONPRENEUR Network<\/title>/)
    }
  }
  assert.ok((await fetch(origin + '/staff/logo-mark.webp')).ok, 'Back office logo is served with the staff app')
  console.log('PASS React routes, assets, PHP redirects, retired integrations, and private paths')

  let token = ''
  const jar = new Map()
  async function request(path, status, method = 'GET', body = null, csrf = true) {
    const headers = { Accept: 'application/json', Cookie: Array.from(jar.values()).join('; ') }
    if (csrf && token) headers['X-CSRF-TOKEN'] = token
    if (body !== null) headers['Content-Type'] = 'application/json'
    const response = await fetch(origin + '/api' + path, {
      method, headers, body: body === null ? undefined : JSON.stringify(body), signal: AbortSignal.timeout(6000),
    })
    for (const cookie of response.headers.getSetCookie()) jar.set(cookie.split('=')[0], cookie.split(';')[0])
    const data = await response.json()
    assert.equal(response.status, status, method + ' ' + path + ' ' + JSON.stringify(data))
    if (data.csrfToken) token = data.csrfToken
    return data
  }
  const credentials = { email: 'staff@example.test', password: 'Fixture-staff-secret' }
  await request('/session', 419, 'POST', credentials, false)
  assert.equal((await request('/session', 200)).user, null)
  await request('/staff/bookings', 401)
  await request('/admin/migrations', 401)
  await request('/session', 401, 'POST', { ...credentials, password: 'wrong' })
  const signedIn = (await request('/session', 200, 'POST', credentials)).user
  assert.equal(signedIn.displayName, 'Fixture Staff')
  assert.equal(signedIn.canManageMigrations, true)
  await request('/staff/bookings', 200)
  const { hasHistory, migrations } = await request('/admin/migrations', 200)
  assert.equal(hasHistory, true)
  assert.ok(migrations.length > 0 && migrations.every(migration => migration.status === 'ran'))
  await request('/admin/migrations/run', 419, 'POST', { password: credentials.password }, false)
  await request('/admin/migrations/run', 422, 'POST', { password: 'wrong' })
  assert.deepEqual((await request('/admin/migrations/run', 200, 'POST', { password: credentials.password })).applied, [])
  await request('/session', 200, 'DELETE')
  await request('/staff/bookings', 401)

  const { minDate } = await request('/availability', 200)
  const next = new Date(minDate + 'T00:00:00Z')
  next.setUTCDate(next.getUTCDate() + 1)
  const date = next.toISOString().slice(0, 10)
  const availability = await request('/availability?date=' + date, 200)
  assert.equal(availability.slots.length, 4)
  const booking = { date, start: availability.slots[0], name: 'Fixture Visitor', email: 'visitor@example.test',
    phone: '212-555-0170', timezone: 'UTC', state: 'NY', concern: 'Synthetic answer',
    interest: 'reputation-digital-presence/executive-reputation-business-continuity' }
  const result = await request('/bookings', 201, 'POST', booking)
  assert.match(result.reference, /^[A-F0-9]{24}$/)
  await request('/bookings', 409, 'POST', booking)
  assert.equal((await request('/intake/status?reference=' + result.reference, 200)).submitted, false)
  await request('/session', 200, 'POST', credentials)
  const { bookings } = await request('/staff/bookings', 200)
  assert.equal(bookings[0].reference, result.reference)
  assert.equal(bookings[0].serviceTitle, 'Executive Reputation & Business Continuity')
  await request('/session', 200, 'DELETE')
  console.log('PASS real HTTP CSRF, staff sign-in and sign-out, and persisted 15-minute booking')
  console.log('PASS real HTTP migration admin access, status, CSRF and password confirmation')

  const chrome = process.env.PHELON_CHROME
  if (chrome) {
    for (const [width, path, expected] of [
      [375, '/services/reputation-digital-presence', /Executive Reputation &amp; Business Continuity/],
      [375, '/login/', /Use your Phelonpreneur staff account/],
      [768, '/privacy-policy', /Privacy Policy/],
      [1440, '/', /When Felony or Federal Charges Hit/],
      [375, '/services', /Readiness Blueprint/],
      [1440, '/book?service=financial-stability', /Choose a date and time/],
    ]) {
      const profile = await mkdtemp(join(fixture, 'browser-'))
      const markup = await exec(chrome, ['--headless', '--disable-gpu', '--disable-background-networking', '--disable-component-update',
        '--no-first-run', '--no-proxy-server', '--host-resolver-rules=MAP * ~NOTFOUND, EXCLUDE 127.0.0.1',
        '--user-data-dir=' + profile, '--window-size=' + width + ',850', '--virtual-time-budget=5000',
        '--dump-dom', origin + path])
      assert.match(markup, expected, path)
      if (path === '/') {
        for (const title of ['Who We Support', 'The Family Gets Hit Too', 'How the Process Works', 'Readiness Blueprint', 'Confidential by Design']) {
          assert.ok(markup.includes(title), 'Missing public homepage content: ' + title)
        }
        assert.doesNotMatch(markup, /href="\/login\/?"/, 'Public page should not contain staff controls')
      }
    }
    console.log('PASS Chromium renders the public, service, booking, policy, and staff screens')
  }
} finally {
  if (server?.pid && server.exitCode === null) {
    const stopped = once(server, 'exit')
    server.kill()
    await stopped
  }
  if (dirname(fixture) === temp) await rm(fixture, { recursive: true, force: true, maxRetries: 4 })
}
