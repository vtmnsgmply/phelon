import assert from 'node:assert/strict';
import net from 'node:net';
import { spawn } from 'node:child_process';
import { once } from 'node:events';
import { mkdir, mkdtemp, readFile, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { dirname, join } from 'node:path';
import { setTimeout as delay } from 'node:timers/promises';
import { withTestServer } from './services-smoke.mjs';

const mysqlBin = process.env.PHELON_MARIADB_BIN || 'C:/xampp/mysql/bin';
const temp = tmpdir();
const sandbox = await mkdtemp(join(temp, 'phelon-native-db-'));
const data = join(sandbox, 'data');
const sessionPath = join(sandbox, 'sessions');
const testEmail = 'staff@example.test';
const testPass = 'Fixture-only-phrase-1234';
let mysql;

async function run(exe, args, input = '', env = process.env) {
  const task = spawn(exe, args, { env, windowsHide: true });
  let output = '';
  task.stdout.on('data', bytes => { output += bytes; });
  task.stderr.on('data', bytes => { output += bytes; });
  task.stdin.on('error', () => {});
  task.stdin.end(input);
  const [code] = await once(task, 'close');
  if (code !== 0) throw new Error(exe + ' failed (' + code + '): ' + output.trim());
  return output.trim();
}

async function openPort() {
  const holder = net.createServer();
  holder.listen(0, '127.0.0.1');
  await once(holder, 'listening');
  const { port } = holder.address();
  await new Promise((resolve, reject) => holder.close(error => error ? reject(error) : resolve()));
  return port;
}

try {
  await mkdir(sessionPath);
  const port = await openPort();
  const bin = file => join(mysqlBin, file + '.exe');
  await run(bin('mysql_install_db'), ['--datadir=' + data, '--port=' + port, '--silent']);
  let serverLog = '';
  let serverError;
  mysql = spawn(bin('mysqld'), [
    '--no-defaults', '--standalone', '--console', '--datadir=' + data, '--port=' + port,
    '--bind-address=127.0.0.1', '--pid-file=' + join(sandbox, 'instance.pid'),
    '--innodb-buffer-pool-size=32M', '--skip-name-resolve',
  ], { windowsHide: true });
  mysql.on('error', error => { serverError = error; });
  for (const pipe of [mysql.stdout, mysql.stderr]) pipe.on('data', bytes => { serverLog = (serverLog + bytes).slice(-3000); });
  const sql = text => run(bin('mysql'), ['--no-defaults', '--host=127.0.0.1', '--protocol=tcp',
    '--port=' + port, '--user=root', '--batch', '--skip-column-names'], text);
  for (let i = 0; ; i++) {
    if (serverError) throw serverError;
    if (mysql.exitCode !== null || i >= 60) throw new Error('Isolated MariaDB did not start: ' + serverLog);
    try { if (await sql('SELECT 1;') === '1') break; } catch {}
    await delay(100);
  }
  await sql('CREATE DATABASE phelon_native_test; USE phelon_native_test;\n'
    + await readFile(new URL('../database/native-booking-and-login.sql', import.meta.url), 'utf8'));
  const env = {
    ...process.env, DB_HOST: '127.0.0.1', DB_PORT: String(port), DB_NAME: 'phelon_native_test',
    DB_USERNAME: 'root', DB_PASSWORD: '', PHELON_TEST_NATIVE_API: '1', PHELON_SECURE_COOKIES: '0',
    PHELON_BOOKING_TIMEZONE: 'UTC', PHELON_BOOKING_OPEN: '09:00', PHELON_BOOKING_CLOSE: '10:00',
    PHELON_BOOKING_DAYS: '1,2,3,4,5,6,7',
  };
  const php = env.PHELON_PHP || 'php';
  const inventory = await run(php, ['scripts/inspect-native-schema.php'], '', env);
  assert.match(inventory, /phelon_discovery_bookings\s+starts_at_utc\s+datetime\s+UNI/);
  assert.match(inventory, /phelon_staff_users\s+password_hash/);
  await run(php, ['Login/backend/bin/create-user.php', testEmail, 'Synthetic Staff'], testPass + '\n', env);
  await withTestServer('native', async origin => {
    const base = origin + '/Phelon/';
    let cookie = '';
    let token = '';
    async function request(path, status, { method = 'GET', body, csrf = true } = {}) {
      const headers = {};
      if (cookie) headers.Cookie = cookie;
      if (token && csrf) headers['X-CSRF-Token'] = token;
      if (body !== undefined) headers['Content-Type'] = 'application/json';
      const result = await fetch(base + path, { method, headers,
        body: body === undefined ? undefined : JSON.stringify(body), signal: AbortSignal.timeout(3000) });
      const payload = await result.json();
      assert.equal(result.status, status, path + ': ' + JSON.stringify(payload));
      const newCookie = result.headers.get('set-cookie');
      if (newCookie) {
        assert.match(newCookie, /HttpOnly/i);
        assert.match(newCookie, /SameSite=Lax/i);
        cookie = newCookie.split(';')[0];
      }
      if (payload.csrfToken) token = payload.csrfToken;
      return payload;
    }
    const session = 'Login/backend/api/session.php';
    assert.equal((await request(session, 200)).user, null);
    const oldCookie = cookie;
    await request(session, 403, { method: 'POST', body: { email: testEmail, password: testPass }, csrf: false });
    const failed = await request(session, 401, { method: 'POST', body: { email: testEmail, password: 'wrong' } });
    const unknown = await request(session, 401, { method: 'POST', body: { email: 'unknown@example.test', password: 'wrong' } });
    assert.deepEqual(failed, unknown);
    assert.deepEqual((await request(session, 200, { method: 'POST', body: { email: testEmail, password: testPass } })).user,
      { displayName: 'Synthetic Staff' });
    assert.notEqual(cookie, oldCookie);
    assert.equal((await request(session, 200)).user.displayName, 'Synthetic Staff');
    assert.equal((await request(session, 200, { method: 'DELETE' })).user, null);
    assert.equal((await request(session, 200)).user, null);
    console.log('PASS staff provisioning, CSRF, generic invalid login errors, session rotation, and logout');

    const booking = 'api/bookings.php';
    const { minDate } = await request(booking, 200);
    const tomorrow = new Date(minDate + 'T00:00:00Z');
    tomorrow.setUTCDate(tomorrow.getUTCDate() + 1);
    const date = tomorrow.toISOString().slice(0, 10);
    const availability = await request(booking + '?date=' + date, 200);
    assert.equal(availability.durationMinutes, 15);
    assert.equal(availability.slots.length, 4);
    const details = { date, start: availability.slots[0], name: 'Synthetic Booking',
      email: 'example@example.test', phone: '212-555-0100', state: 'NY', timezone: 'UTC', concern: 'General planning',
      interest: 'reputation-digital-presence/executive-reputation-business-continuity' };
    await request(booking, 403, { method: 'POST', body: details, csrf: false });
    await request(booking, 422, { method: 'POST', body: { ...details, start: availability.slots[0].replace('09:00', '07:00') } });
    const booked = await request(booking, 201, { method: 'POST', body: details });
    assert.match(booked.reference, /^[A-F0-9]{24}$/);
    assert.equal(booked.startsAt, details.start);
    await request(booking, 409, { method: 'POST', body: details });
    assert.deepEqual((await request(booking + '?date=' + date, 200)).slots, availability.slots.slice(1));
    const stored = await sql('USE phelon_native_test; SELECT service_key, subtype_key, TIMESTAMPDIFF(MINUTE, starts_at_utc, ends_at_utc) FROM phelon_discovery_bookings;');
    assert.equal(stored, 'reputation-digital-presence\texecutive-reputation-business-continuity\t15');
    console.log('PASS native availability, invalid slot rejection, 15-minute booking persistence, service subtype and duplicate-slot prevention');
  }, { env, sessionPath });
} finally {
  if (mysql?.pid && mysql.exitCode === null) {
    const stopped = once(mysql, 'exit');
    mysql.kill();
    await stopped;
  }
  if (dirname(sandbox) === temp) await rm(sandbox, { recursive: true, force: true, maxRetries: 4 });
}
