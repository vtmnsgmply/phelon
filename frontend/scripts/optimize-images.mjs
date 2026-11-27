// frontend/scripts/optimize-images.mjs
// Run: npm run images
// Reads originals from frontend/media-src/, writes AVIF + WebP variants to frontend/public/media/
// and a manifest (intrinsic size + available widths) to frontend/src/media-manifest.json.
import { mkdir, readdir, writeFile } from 'node:fs/promises';
import { join, parse } from 'node:path';
import { fileURLToPath } from 'node:url';
import sharp from 'sharp';

const dir = (p) => fileURLToPath(new URL(p, import.meta.url));
const SRC = dir('../media-src/');
const OUT = dir('../public/media/');
const MANIFEST = dir('../src/media-manifest.json');
const WIDTHS = [480, 800, 1280, 1920];

const slug = (s) => s.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');

await mkdir(OUT, { recursive: true });
const manifest = {};

for (const file of await readdir(SRC)) {
  if (!/\.(png|jpe?g|webp)$/i.test(file)) continue;
  const name = slug(parse(file).name);
  const path = join(SRC, file);
  const meta = await sharp(path, { failOn: 'none' }).metadata();
  const [w0, h0] = meta.orientation >= 5 ? [meta.height, meta.width] : [meta.width, meta.height];
  const widths = [...new Set([...WIDTHS.filter((w) => w < w0), Math.min(w0, WIDTHS.at(-1))])];

  for (const w of widths) {
    const resized = sharp(path, { failOn: 'none' }).rotate().resize({ width: w, withoutEnlargement: true });
    await resized.clone().avif({ quality: 50, effort: 4 }).toFile(join(OUT, `${name}-${w}.avif`));
    await resized.clone().webp({ quality: 76 }).toFile(join(OUT, `${name}-${w}.webp`));
  }
  manifest[name] = { w: w0, h: h0, widths };
  console.log(`${file} -> ${name} [${widths.join(', ')}]`);
}

await writeFile(MANIFEST, JSON.stringify(manifest, null, 2) + '\n');
