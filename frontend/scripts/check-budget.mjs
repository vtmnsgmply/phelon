// frontend/scripts/check-budget.mjs
// Run after a build: npm run build && npm run check:budget
// Fails (exit 1) when the production build is heavier than the budget in REDESIGN-BRIEF.md section 2.
import { existsSync, readFileSync, readdirSync, statSync } from 'node:fs';
import { extname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { gzipSync } from 'node:zlib';

const OUT = fileURLToPath(new URL('../../backend/public/site/', import.meta.url));
const KB = 1024;
const gz = (file) => gzipSync(readFileSync(file)).length / KB;
const raw = (file) => statSync(file).size / KB;
const walk = (d) =>
  readdirSync(d, { withFileTypes: true }).flatMap((e) => (e.isDirectory() ? walk(join(d, e.name)) : [join(d, e.name)]));

if (!existsSync(join(OUT, 'index.html'))) {
  console.error('No build found. Run: npm run build');
  process.exit(1);
}

const html = readFileSync(join(OUT, 'index.html'), 'utf8');
const initial = [
  ...new Set([...html.matchAll(/(?:src|href)="[^"]*?\/(assets\/[^"]+\.(?:js|css))"/g)].map((m) => join(OUT, m[1]))),
];
const initialJs = initial.filter((f) => extname(f) === '.js');
const initialCss = initial.filter((f) => extname(f) === '.css');
const all = walk(OUT);
const lazyJs = all.filter((f) => extname(f) === '.js' && !initialJs.includes(f));
const fonts = all.filter((f) => extname(f) === '.woff2');
const media = all.filter((f) => /^media[\\/]/.test(f.slice(OUT.length)));
const optimised = media.filter((f) => /\.(avif|webp)$/i.test(f));
const worstImage = optimised.reduce((a, f) => (a && raw(a) >= raw(f) ? a : f), null);

const checks = [
  { label: 'Initial JavaScript (gzip)', value: initialJs.reduce((s, f) => s + gz(f), 0), max: 110, hard: true },
  { label: 'Initial CSS (gzip)', value: initialCss.reduce((s, f) => s + gz(f), 0), max: 14, hard: true },
  { label: 'Fonts, all woff2 (raw)', value: fonts.reduce((s, f) => s + raw(f), 0), max: 70, hard: true },
  ...lazyJs.map((f) => ({ label: `Lazy chunk ${f.split(/[\\/]/).pop()} (gzip)`, value: gz(f), max: 35, hard: true })),
  ...(worstImage
    ? [{ label: `Largest optimised image ${worstImage.split(/[\\/]/).pop()} (raw)`, value: raw(worstImage), max: 250, hard: true }]
    : []),
  ...media
    .filter((f) => /\.(png|jpe?g)$/i.test(f) && raw(f) > 250)
    .map((f) => ({ label: `Legacy image ${f.split(/[\\/]/).pop()} (raw)`, value: raw(f), max: 250, hard: false })),
];

let failed = false;
for (const c of checks) {
  const ok = c.value <= c.max;
  if (!ok && c.hard) failed = true;
  console.log(`${ok ? 'PASS' : c.hard ? 'FAIL' : 'WARN'}  ${c.label}: ${c.value.toFixed(1)} kB (max ${c.max})`);
}
console.log(failed ? '\nBudget FAILED' : '\nBudget OK');
process.exit(failed ? 1 : 0);
