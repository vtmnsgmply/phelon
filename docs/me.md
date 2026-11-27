# Phelonpreneur frontend redesign: brief for Claude Code

Written 28 November 2026 from the status snapshot of the same date. The snapshot was taken while the code was still changing, and the backend API was not running. **Read the real code before you edit it.** Where this brief and the code disagree about *behaviour*, the code wins. Where they disagree about *design*, this brief wins.

---

## 0. Read this first

### Your job

Redesign the public site in `frontend/` so it looks like an enterprise-grade product site: the polish of a premium Wix template combined with the dark, luminous, glass-and-bento look of a modern AI-builder site such as Emergent. Keep it light. It must load fast on a mid-range phone on 4G.

"Emergent plus Wix" is read here as a set of **qualities** (section 1.1), not as a copy of either product. Do not copy their logos, text, screenshots, layouts or assets.

### Done means

1. Every route in section 8 is redesigned, mobile first, with no horizontal scroll from 320px to 1920px.
2. `npm run verify` and `npm run test:e2e` pass, and the performance budget in section 2 is met.
3. The six high-priority trust issues from the status document are resolved or safely hidden (section 9), and every owner decision is listed in the report.
4. The same API calls, the same routes and the same copy as before, unless section 9 says otherwise.
5. A short report exists at `frontend/REDESIGN-REPORT.md` (template in section 14).

### Hard rules

1. **Do not change the backend**: no API changes, validation changes, `.env` changes or migrations. Request fields the frontend sends must stay exactly as they are.
2. **Keep every route and URL** listed in section 8.
3. **Keep the copy.** Change layout, not messaging. You may fix capitalisation (sentence case) and rewrite *interface microcopy* (buttons, error and empty states, per section 1.5). Never invent claims, statistics, testimonials, client logos, dates, prices or guarantees. The text of the four policy pages (privacy, terms, cookie, disclaimer) stays exactly as it is.
4. **The business gives no legal advice.** Nothing in the design may imply it does: no scales, gavels or courthouse imagery, and no wording such as "legal guidance".
5. **Never run `backend/tools/export-legacy-content.php`.** It overwrites hand edits to `pages.json`, `services.json` and `intake.json`.
6. **Do not upgrade** React 19.3.1, React Router 7.18.7, Vite 7.3.8 or `@vitejs/plugin-react` 5.2.0. Newer majors exist (Vite 8, React Router 8, plugin-react 6). Upgrading is out of scope.
7. **No third-party origins**: no CDN fonts, analytics, chat widgets, trackers or embeds.
8. **No animation or 3D libraries** (see the "do not install" list in section 3).
9. **Intake answers must not be written to localStorage, sessionStorage, cookies or the URL.** They are sensitive and discretion is the product. Warn on page leave instead (section 8.5).
10. **Back up before you touch anything** (Phase 0). **Do not commit or push** unless the owner asks.
11. `npm run build` must keep writing to `../backend/public/site/` and stay green after every phase.

### How to work

- Follow the phases in section 12 in order. After each phase run `npm run verify`, take screenshots at 390px and 1366px, and add a line to `frontend/REDESIGN-REPORT.md`.
- Where this brief names a file path, create it there. Where it says "replace", the old code is removed in the same phase, not left behind.
- Do not stop to ask questions. If a decision belongs to the owner (claims, naming, dates, deleting files), take the **safest option**, mark it in code with a comment starting `OWNER:`, and list it in the report.
- Prefer deleting code to adding it. Every new dependency, component and effect must justify its weight against the budget.

### Out of scope

The backend, the staff app in `login/`, the catalog JSON schema, pricing, policy text, payment providers, new features (search, blog, chat, newsletter, contact form), analytics, cookie banners, and server configuration. Keep `src/styles/tokens.css` self-contained so the staff app can adopt it later.

---

## 1. Design direction

### 1.1 The look, as qualities

| Quality | What it means here | How to build it cheaply |
|---|---|---|
| Luminous dark hero (Emergent-like) | Deep plum with a soft rose and gold glow and a faint hairline grid | Layered CSS gradients and a masked grid. No images, no blur filters |
| Glass panels (Emergent-like) | 1px light border, 3 to 7% white fill, inner top highlight | Plain CSS. `backdrop-filter` only on the sticky header |
| Bento grids (Emergent-like) | Mixed-size cards on a 12-column grid, each card a clear unit | CSS grid with span classes |
| Confident editorial type (Wix-like) | Large serif headings, calm sans body, generous whitespace | Two variable fonts, about 64 kB total |
| Shaped, image-led sections (Wix-like) | Photos inside an arch, not rectangles | CSS `border-radius` plus one inline SVG |
| Alternating strips (Wix-like) | Sections alternate dark and warm light surfaces | `data-surface` tokens |
| Tactile interaction | Hover spotlight, press feedback, smooth route cross-fade | CSS plus one 10-line pointer listener |

### 1.2 The signature: the arch

An arch is a doorway: shelter, threshold, readiness. It is the one memorable motif, tied to the subject. Use it in five places only:

1. The hero portrait frame, with a gold outline that draws itself on load.
2. The About page portrait.
3. The family section image on the home page.
4. A faint watermark in the closing call-to-action band (6% opacity).
5. The 404 page graphic.

Everything else stays quiet. Spend boldness in one place.

```jsx
// src/components/ArchFrame.jsx
export function ArchFrame({ children, outline = true }) {
  return (
    <div className="ph-arch-wrap">
      <div className="ph-arch">{children}</div>
      {outline && (
        <svg className="ph-arch-outline" viewBox="0 0 400 500" preserveAspectRatio="none" aria-hidden="true" focusable="false">
          <path d="M2 498V200a198 198 0 0 1 396 0v298" pathLength="1" />
        </svg>
      )}
    </div>
  );
}
```

```css
/* src/styles/components.css (inside @layer components) */
.ph-arch-wrap { position: relative; inline-size: min(100%, 26rem); margin-inline: auto; }
.ph-arch {
  aspect-ratio: 4 / 5; overflow: hidden; background: var(--plum-800);
  border-radius: 50% 50% var(--r-lg) var(--r-lg) / 40% 40% var(--r-lg) var(--r-lg);
}
.ph-arch :is(picture, img) { display: block; inline-size: 100%; block-size: 100%; object-fit: cover; }
.ph-arch-outline {
  position: absolute; top: -0.9rem; left: -0.9rem;
  inline-size: calc(100% + 1.8rem); block-size: calc(100% + 1.8rem);
  overflow: visible; pointer-events: none;
}
.ph-arch-outline path { fill: none; stroke: var(--gold-400); stroke-width: 1.5; stroke-opacity: .7; stroke-linecap: round; }
```

The outline is open at the bottom on purpose: a doorway. The aspect ratio is fixed at 4:5 so the percentage radii give a true semicircle top. Do not set `border-radius: 999px`: the browser scales every corner by the same factor and the bottom corners would collapse.

### 1.3 What must not appear

These are the marks of a generic generated template. Avoid all of them.

- Uppercase, letter-spaced eyebrow text above every heading. The current `.eyebrow` style is removed. A small sentence-case label is allowed only where it names something real, such as a category on a service card.
- A single word of a heading in italic, gold or another colour. Gradient text.
- Numbered markers (01, 02, 03) anywhere except the three-step process and the 11-step intake.
- Arrow glyphs appended to button or link text.
- The same fade-up reveal on every section, and a hover lift on every card. Follow the motion tiers in section 5.
- One radius and one grey shadow on everything. Use the radius and elevation scales in 4.3. Shadows are plum-tinted.
- Fake social proof: logos, counters, star ratings, testimonials (see section 9).
- Emoji used as icons, lorem ipsum, stock "handshake" photography.

### 1.4 Brand carried forward

Keep the existing identity: dark plum and gold, warm cream and paper, serif headings over a sans body, a calm and discreet tone. The redesign makes it sharper and more systematic. It does not change the brand.

### 1.5 Interface copy

- Sentence case for every heading, label and button. Fix capitalisation only; keep the words.
- A button says what happens: "Book a free discovery call", "Check availability". The same action keeps the same name through a whole flow.
- Errors say what went wrong and how to fix it. They do not apologise and they never rely on colour alone. Example: "We couldn't load available times. Check your connection and try again." with a **Try again** button.
- Empty and offline states always offer a next action.

---

## 2. Performance budget (the "not heavy" part)

| Measure | Snapshot | Hard budget | Checked by |
|---|---|---|---|
| Initial JavaScript, gzip (entry plus preloaded chunks) | 92.8 kB | **≤ 110 kB** | `check:budget` |
| Initial CSS, gzip | 3.2 kB | **≤ 14 kB** | `check:budget` |
| Any lazy JavaScript chunk, gzip | 23.8 kB | **≤ 35 kB** | `check:budget` |
| Fonts, woff2 total | none loaded | **≤ 70 kB** (planned about 64 kB) | `check:budget` |
| Any one optimised image | up to 2.9 MB | **≤ 250 kB** | `check:budget` |
| Images on the home page, transferred | not measured | ≤ 450 kB | DevTools Network |
| Images on the webinar page | about 8 MB | ≤ 500 kB | DevTools Network |
| Cold home load, all bytes, mobile | not measured | ≤ 700 kB | Lighthouse |
| Lighthouse mobile (default simulated 4G) | not measured | Performance ≥ 90, Accessibility ≥ 95, Best practices ≥ 95, SEO ≥ 95 | Lighthouse |
| LCP, CLS, TBT | not measured | LCP ≤ 2.5 s (aim 2.0), CLS ≤ 0.05 (aim 0.02), TBT ≤ 200 ms | Lighthouse |

### Rules for staying light

1. **Effects are CSS first.** JavaScript is allowed only for: the scroll reveal observer (under 1 kB), one pointer listener for the card spotlight, menu state, and the sticky call-to-action.
2. **Animate only `transform` and `opacity`.** Two exceptions: SVG `stroke-dashoffset` for the arch outline, and `block-size` on `<details>` inside an `@supports` block. Never animate `box-shadow`, `filter`, `backdrop-filter`, `width`, `height`, `top` or `left`.
3. **No scroll event listeners.** Use IntersectionObserver or CSS scroll-driven animation.
4. **`backdrop-filter` only on the sticky header.** Everywhere else use solid or semi-opaque fills.
5. **No `filter: blur()` on large elements.** Build glows from radial gradients.
6. **At most about 12 elements animating at once.** Never put `will-change` in static CSS.
7. **Do not fade or clip the LCP element.** The home headline and the hero portrait animate with `transform` only. An opacity or clip-path start state delays LCP.
8. **Images**: AVIF and WebP, `srcset`, explicit `width` and `height`, lazy-loaded below the fold, `fetchpriority="high"` on exactly one image per page.
9. **Fonts**: two self-hosted variable woff2 files, latin subset, `font-display: swap`, both preloaded, with a metric-adjusted fallback so the swap does not shift layout.
10. **Below-the-fold sections** get `content-visibility: auto; contain-intrinsic-size: auto 700px` via a `.ph-defer` class. Do not use it on a section that contains an anchor target (for example `#how-we-work`), because hash scrolling can land in the wrong place.
11. **Nothing from another origin.**
12. **Keep route-level code splitting.** Do not pull page code into the entry chunk. Import icons by name only.

---

## 3. Dependencies

Run in `frontend/`:

```bash
npm install lucide-react
npm install -D @fontsource-variable/fraunces @fontsource-variable/plus-jakarta-sans \
  sharp rollup-plugin-visualizer \
  eslint@^9 @eslint/js@^9 globals eslint-plugin-react-hooks eslint-plugin-react-refresh eslint-plugin-jsx-a11y \
  @playwright/test @axe-core/playwright
npx playwright install chromium    # about 150 MB, dev machine only. If Chrome is installed, use channel: 'chrome' instead
```

| Package | Kind | Why | Reaches visitors? |
|---|---|---|---|
| `lucide-react` | runtime | One consistent icon family, tree-shaken by named import. Keep to 24 icons or fewer. If the icon cost passes 8 kB gzip, replace it with a local SVG sprite | yes, a few kB |
| `@fontsource-variable/fraunces`, `@fontsource-variable/plus-jakarta-sans` | dev | Source of two woff2 files that are **copied** into `src/assets/fonts/` | no, files are copied |
| `sharp` | dev | Image pipeline (Appendix A) | no |
| `rollup-plugin-visualizer` | dev | `npm run analyze` bundle report | no |
| `eslint@^9` and plugins | dev | Linting. ESLint 9, not 10, because `eslint-plugin-jsx-a11y` supports up to 9 | no |
| `@playwright/test`, `@axe-core/playwright` | dev | Browser tests, screenshots, accessibility checks | no |

Versions checked on 28 Nov 2026: `lucide-react` 1.82, both Fontsource packages 5.3, `sharp` 0.35, `@playwright/test` 1.65, `@axe-core/playwright` 4.14, `eslint-plugin-react-hooks` 7.1, `rollup-plugin-visualizer` 7.3. Node must be 20.19 or newer, or 22.12 or newer (Vite 7).

### Do not install

`framer-motion` or `motion`, GSAP, Lenis or any smooth-scroll library (it hijacks scrolling and breaks anchors and accessibility), Three.js, OGL or any WebGL, Lottie, Swiper or Slick, AOS, particle libraries, Tailwind, CSS-in-JS, UI kits, any Google Fonts or CDN link, any analytics. Page transitions use React Router's `viewTransition` prop and the platform View Transitions API instead.

### Fonts

Display: **Fraunces** (headings, 500). Body and UI: **Plus Jakarta Sans** (400, 600, 700). Measured latin woff2 sizes: Fraunces 36.6 kB, Plus Jakarta Sans 27.3 kB. Inter is dropped from the font stack.

```bash
mkdir -p src/assets/fonts
cp node_modules/@fontsource-variable/fraunces/files/fraunces-latin-wght-normal.woff2 src/assets/fonts/
cp node_modules/@fontsource-variable/plus-jakarta-sans/files/plus-jakarta-sans-latin-wght-normal.woff2 src/assets/fonts/
cp node_modules/@fontsource-variable/fraunces/LICENSE src/assets/fonts/LICENSE-fraunces.txt
cp node_modules/@fontsource-variable/plus-jakarta-sans/LICENSE src/assets/fonts/LICENSE-plus-jakarta-sans.txt
```

```css
/* src/styles/fonts.css (not layered: @font-face is global) */
@font-face {
  font-family: "Fraunces"; font-style: normal; font-weight: 100 900; font-display: swap;
  src: url("../assets/fonts/fraunces-latin-wght-normal.woff2") format("woff2");
  unicode-range: U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;
}
@font-face {
  font-family: "Plus Jakarta Sans"; font-style: normal; font-weight: 200 800; font-display: swap;
  src: url("../assets/fonts/plus-jakarta-sans-latin-wght-normal.woff2") format("woff2");
  unicode-range: U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;
}
/* Add two metric-adjusted fallback faces ("Fraunces Fallback" from Georgia, "Plus Jakarta Fallback" from Arial)
   using size-adjust, ascent-override and descent-override. Tune until Lighthouse CLS from font swap is about 0. */
```

---

## 4. Design system

### 4.1 Colour

All values are brand colours already in use, promoted to tokens, plus a few additions. Every pair below was measured against WCAG 2.2.

| Token | Value | Use | Measured contrast |
|---|---|---|---|
| `--ink` | `#201821` | Body text on light | 16.07 on cream |
| `--muted` | `#67595f` | Secondary text on light | 6.15 on cream, 5.34 on cream-200, 4.83 on cream-300 |
| `--burgundy-700` | `#672a43` | Links and buttons on light | 9.81 on cream, white text on it 10.56 |
| `--gold-700` | `#8a6a2a` | Gold **text** on light (use sparingly) | 4.67 on cream |
| `--gold-400` | `#e9c986` | Accent on dark, gold button fill | 11.31 on plum-900, plum text on it 11.31 |
| `--gold-300`, `--gold-200` | `#f3dca8`, `#f6e7c4` | Links on dark, highlights | 13.42 (plum text on gold-300) |
| `--cream-100` | `#fbf6ed` | Page background | n/a |
| `--cream-50` | `#fffdfa` | Cards, inputs | n/a |
| `--cream-200`, `--cream-300` | `#f1e5d8`, `#e9d9d3` | Tinted sections | n/a |
| `--plum-900` | `#1b141c` | Header, hero, footer | cream text 16.77 |
| `--plum-950`, `--plum-800` | `#140e15`, `#2a1c2b` | Deep and raised dark surfaces | cream on plum-800 15.01 |
| `--plum-600` | `#4d2437` | Closing band | cream text 12.02 |
| `--rose-300` | `#d9a0b6` | Highlights on dark | 8.30 on plum-900 |
| `--rose-500` | `#a9506f` | Glow fills only, never text | 3.49 on plum-900 |
| `--line-strong` | `#8a766b` | **Input and control borders** (replaces `#aa9387`, which failed at 2.86) | 4.23 on paper, 3.99 on cream |
| `--line` | `#ddd0c3` | Decorative hairlines only | decorative |
| `--focus` | `#ae6544` | Focus ring on light | 4.11 on cream, 4.36 on paper |
| `--danger` | `#9d2235` | Error text on light | 7.19 on cream, 6.35 on notice |
| `--success` | `#2f6b4f` | Success text on light | 5.85 on cream |
| `--notice-bg`, `--notice-ink` | `#f5e6dd`, `#572b3c` | Notices | 9.50 |

Rules: never put gold text on a light background except `--gold-700`. Never use `--rose-500` for text. Body text on dark is cream at 100% or 72% (9.07:1). Do not go below 72% for text.

Old token names stay as aliases until `site.css` is removed (see 4.6).

### 4.2 Type scale

Fluid, with measured sizes at 360 / 768 / 1366px:

| Token | Use | 360px | 768px | 1366px |
|---|---|---|---|---|
| `--step-0` | Body | 16.1 | 16.7 | 17.0 |
| `--step-1` | Lede, large UI | 18 | 19 | 21 |
| `--step-2` | H3 | 22.2 | 24.6 | 28.0 |
| `--step-3` | Large H3, pull text | 28.3 | 33.2 | 40.0 |
| `--step-4` | H2 | 34.4 | 42.6 | 54.5 |
| `--step-5` | H1 on inner pages | 41.0 | 54.9 | 75.2 |
| `--step-hero` | Home H1 | 36 | 49 | 69 |

Headings: Fraunces 500, line height 1.1, letter spacing -0.02em, `text-wrap: balance`. Body: Plus Jakarta Sans 400, line height 1.65, `text-wrap: pretty`. Line length: prose 66ch, lede 52ch, headings under 20ch where possible. Left-aligned everywhere except the closing band and the 404 page.

### 4.3 Space, radius, elevation

Radius follows hierarchy: controls are smallest, containers largest.

| Token | Value | Use |
|---|---|---|
| `--r-xs` | 6px | Chips, tags |
| `--r-sm` | 12px | Inputs, small buttons |
| `--r-md` | 16px | Cards inside cards, icon tiles |
| `--r-lg` | 24px | Cards, bento cells, panels |
| `--r-xl` | 32px | Hero and band containers |
| `--r-pill` | 999px | Buttons, chips |

Elevation uses plum-tinted shadows, never grey, and shadows are not animated (fade a pseudo-element instead).

### 4.4 Motion tokens

Easing `--ease-out: cubic-bezier(.22, 1, .36, 1)`, `--ease-in-out: cubic-bezier(.65, 0, .35, 1)`. Durations: 120, 220, 420, 700 ms.

### 4.5 `src/styles/tokens.css`

```css
/* src/styles/tokens.css: self-contained so the staff app can reuse it later */
@layer tokens {
  :root {
    color-scheme: light;

    /* Brand */
    --plum-950: #140e15; --plum-900: #1b141c; --plum-800: #2a1c2b; --plum-600: #4d2437;
    --burgundy-700: #672a43; --burgundy-600: #7a3350;
    --rose-500: #a9506f; --rose-300: #d9a0b6;
    --gold-200: #f6e7c4; --gold-300: #f3dca8; --gold-400: #e9c986; --gold-500: #cfa95c; --gold-700: #8a6a2a;
    --cream-50: #fffdfa; --cream-100: #fbf6ed; --cream-200: #f1e5d8; --cream-300: #e9d9d3;
    --ink: #201821; --muted: #67595f;
    --line: #ddd0c3; --line-strong: #8a766b;
    --notice-bg: #f5e6dd; --notice-ink: #572b3c;
    --danger: #9d2235; --danger-light: #ff9aa8; --success: #2f6b4f;

    /* Compatibility aliases for the old stylesheet. Delete when site.css is gone. */
    --cream: var(--cream-100); --paper: var(--cream-50); --gold: var(--gold-400);
    --burgundy: var(--burgundy-700); --dark: var(--plum-900);

    /* Semantic tokens: components use ONLY these, so they work on light and dark */
    --bg: var(--cream-100);
    --surface: var(--cream-50);
    --border: var(--line);
    --border-hover: rgb(103 42 67 / .35);
    --text: var(--ink);
    --text-muted: var(--muted);
    --accent: var(--burgundy-700);   /* button fill */
    --on-accent: #fff;
    --link: var(--burgundy-700);
    --focus: #ae6544;

    /* Type */
    --font-display: "Fraunces", "Fraunces Fallback", Georgia, "Times New Roman", serif;
    --font-body: "Plus Jakarta Sans", "Plus Jakarta Fallback", system-ui, -apple-system, "Segoe UI", sans-serif;
    --step-0: clamp(1rem, .97rem + .15vw, 1.0625rem);
    --step-1: clamp(1.125rem, 1.07rem + .28vw, 1.3125rem);
    --step-2: clamp(1.375rem, 1.25rem + .6vw, 1.75rem);
    --step-3: clamp(1.75rem, 1.5rem + 1.2vw, 2.5rem);
    --step-4: clamp(2.125rem, 1.7rem + 2vw, 3.5rem);
    --step-5: clamp(2.5rem, 1.8rem + 3.4vw, 5rem);
    --step-hero: clamp(2.25rem, 1.55rem + 3.2vw, 4.75rem);

    /* Space */
    --space-1: .25rem; --space-2: .5rem; --space-3: .75rem; --space-4: 1rem; --space-5: 1.5rem;
    --space-6: 2rem; --space-7: 3rem; --space-8: 4rem; --space-9: 6rem;
    --section-y: clamp(4rem, 2.5rem + 6vw, 8rem);

    /* Layout */
    --container: 74rem; --gutter: clamp(1.25rem, 4vw, 3rem);
    --header-h: 4rem;

    /* Radius */
    --r-xs: 6px; --r-sm: 12px; --r-md: 16px; --r-lg: 24px; --r-xl: 32px; --r-pill: 999px;

    /* Elevation (plum-tinted) */
    --shadow-1: 0 1px 2px rgb(32 24 33 / .06), 0 1px 1px rgb(32 24 33 / .04);
    --shadow-2: 0 8px 24px -8px rgb(32 24 33 / .14), 0 2px 6px rgb(32 24 33 / .06);
    --shadow-3: 0 24px 56px -16px rgb(40 18 36 / .28), 0 4px 12px rgb(32 24 33 / .08);

    /* Motion */
    --ease-out: cubic-bezier(.22, 1, .36, 1);
    --ease-in-out: cubic-bezier(.65, 0, .35, 1);
    --dur-1: 120ms; --dur-2: 220ms; --dur-3: 420ms; --dur-4: 700ms;
  }

  @media (min-width: 56em) { :root { --header-h: 4.5rem; } }

  /* Dark surfaces: hero, footer, trust band, closing band. Same components, remapped tokens. */
  [data-surface="dark"] {
    --bg: var(--plum-900);
    --surface: linear-gradient(180deg, rgb(255 253 250 / .07), rgb(255 253 250 / .03));
    --border: rgb(255 253 250 / .12);
    --border-hover: rgb(233 201 134 / .4);
    --text: var(--cream-100);
    --text-muted: rgb(251 246 237 / .72);
    --accent: var(--gold-400);
    --on-accent: var(--plum-900);
    --link: var(--gold-300);
    --focus: var(--gold-400);
    color: var(--text);
  }
}
```

Section surfaces: `light` (cream-100, default), `tint` (cream-200), `blush` (cream-300), `dark` (plum-900), `deep` (plum-950), `band` (plum-600). The last three set `data-surface="dark"`. A section picks its background with `data-bg="tint"` and so on.

### 4.6 CSS architecture

Replace the single `site.css` with layered files, imported once from `main.jsx` through `src/styles/index.css`:

```
src/styles/
  index.css        @layer reset, tokens, base, layout, components, sections, utilities;  then imports
  fonts.css
  tokens.css
  base.css         reset, html and body, headings, links, focus, selection, sr-only, reduced motion
  layout.css       container, section, grid helpers, bento, rails
  components.css   button, card, field, notice, arch, marquee, details, header, footer, menu
  sections.css     home hero and page-specific rules
  motion.css       all keyframes and reveal rules (section 5)
```

- **Prefix every new class with `ph-`.** The old `site.css` is unlayered and wins conflicts, which keeps untouched pages intact while you migrate. Delete `site.css` and the compatibility aliases in Phase 6, once nothing references it.
- Keep specificity flat: one class per rule, no ids, no nesting deeper than two levels, no `!important` except in the reduced-motion block.
- Base rules to include:

```css
@layer base {
  html { scroll-padding-top: calc(var(--header-h) + 1rem); -webkit-text-size-adjust: 100%; }
  @media (prefers-reduced-motion: no-preference) { html { scroll-behavior: smooth; } }
  body { margin: 0; min-block-size: 100dvh; background: var(--bg); color: var(--text);
         font: 400 var(--step-0)/1.65 var(--font-body); -webkit-tap-highlight-color: transparent; }
  h1, h2, h3 { font-family: var(--font-display); font-weight: 500; line-height: 1.1; letter-spacing: -.02em; text-wrap: balance; }
  p { text-wrap: pretty; }
  :where(img, picture, svg) { display: block; max-inline-size: 100%; }
  :where(img) { block-size: auto; }
  :where(a, button, input, select, textarea, summary):focus-visible { outline: 2px solid var(--focus); outline-offset: 3px; border-radius: var(--r-xs); }
  :where(main):focus { outline: none; }
  button, [role="button"], a { touch-action: manipulation; }
  .sr-only { position: absolute; inline-size: 1px; block-size: 1px; overflow: hidden; clip-path: inset(50%); white-space: nowrap; }
}
```

---

## 5. Motion

Three tiers. Follow them strictly: restraint is what makes it look expensive.

| Tier | What | Rule |
|---|---|---|
| 1. One orchestrated moment | The home hero load sequence (below) | Plays once on load. Nothing else on the site is ambient, except the slow service marquee |
| 2. Answers to a person's action | Button press, card hover and focus, menu sheet, accordions, booking slot selection, intake step change, route cross-fade, sticky call-to-action | 120 to 420 ms, `transform` and `opacity` |
| 3. Scroll-linked, at most **two** places | The bento stagger on the home page, and the process line that draws as you scroll | Everything else on the page is static. Use the `Reveal` component in at most two groups on the home page and one on any other page |

### 5.1 Hero sequence (CSS only, no JavaScript)

| Time | Element | Effect |
|---|---|---|
| 0 ms | Headline | `translateY(18px)` to 0 over 700 ms. **Opacity stays 1** (LCP) |
| 120 ms | Lede and buttons | Fade and rise over 700 ms |
| 150 ms | Arch outline | Draws over 1100 ms (`stroke-dashoffset`) |
| 200 ms | Portrait | `scale(1.05)` to 1 over 1200 ms. No clip-path, no opacity |
| 900 ms | Glass chip | Fade and rise over 600 ms |

```css
/* src/styles/motion.css */
@layer components {
  @keyframes ph-rise-t { from { transform: translateY(18px); } to { transform: none; } }
  @keyframes ph-rise   { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
  @keyframes ph-settle { from { transform: scale(1.05); } to { transform: none; } }
  @keyframes ph-draw   { to { stroke-dashoffset: 0; } }

  .ph-hero-title   { animation: ph-rise-t 700ms var(--ease-out) both; }
  .ph-hero-copy    { animation: ph-rise 700ms var(--ease-out) 120ms both; }
  .ph-hero-actions { animation: ph-rise 700ms var(--ease-out) 240ms both; }
  .ph-hero .ph-arch img { animation: ph-settle 1200ms var(--ease-out) 200ms both; }
  .ph-hero .ph-arch-outline path { stroke-dasharray: 1; stroke-dashoffset: 1; animation: ph-draw 1100ms var(--ease-out) 150ms forwards; }
  .ph-hero-chip    { animation: ph-rise 600ms var(--ease-out) 900ms both; }
}
```

### 5.2 Everything else

```css
@layer components {
  /* Buttons: press feedback always, sheen on hover-capable devices only */
  .ph-button { transition: transform var(--dur-1) var(--ease-out), background-color var(--dur-2) var(--ease-out); }
  .ph-button:active { transform: scale(.98); }
  @media (hover: hover) {
    .ph-button--primary { position: relative; overflow: hidden; isolation: isolate; }
    .ph-button--primary::after { content: ""; position: absolute; inset: 0; z-index: -1; transform: translateX(-120%);
      background: linear-gradient(100deg, transparent 30%, rgb(255 255 255 / .28), transparent 70%); transition: transform 600ms var(--ease-out); }
    .ph-button--primary:hover::after { transform: translateX(120%); }
  }

  /* Cards: spotlight follows the pointer (JS sets --mx and --my). Hover-capable devices only */
  [data-spotlight] { position: relative; isolation: isolate; }
  [data-spotlight]::before { content: ""; position: absolute; inset: 0; z-index: -1; border-radius: inherit; opacity: 0;
    background: radial-gradient(360px circle at var(--mx, 50%) var(--my, 0%), rgb(233 201 134 / .16), transparent 70%);
    transition: opacity var(--dur-2) var(--ease-out); }
  @media (hover: hover) {
    .ph-card { transition: transform var(--dur-2) var(--ease-out), border-color var(--dur-2) var(--ease-out); }
    .ph-card:hover { transform: translateY(-2px); border-color: var(--border-hover); }
    [data-spotlight]:hover::before { opacity: 1; }
  }

  /* Tier 3a: scroll reveal. Hidden only when JS is running, so no-JS visitors see everything */
  .js [data-reveal]:not([data-in]) { opacity: 0; transform: translateY(14px); }
  .js [data-reveal] { transition: opacity var(--dur-4) var(--ease-out), transform var(--dur-4) var(--ease-out); }
  .js [data-reveal="stagger"]:not([data-in]) { opacity: 1; transform: none; }
  .js [data-reveal="stagger"]:not([data-in]) > * { opacity: 0; transform: translateY(14px); }
  .js [data-reveal="stagger"] > * { transition: opacity var(--dur-4) var(--ease-out), transform var(--dur-4) var(--ease-out); }
  .js [data-reveal="stagger"] > :nth-child(2) { transition-delay: 70ms; }
  .js [data-reveal="stagger"] > :nth-child(3) { transition-delay: 140ms; }
  .js [data-reveal="stagger"] > :nth-child(4) { transition-delay: 210ms; }
  .js [data-reveal="stagger"] > :nth-child(5) { transition-delay: 280ms; }
  .js [data-reveal="stagger"] > :nth-child(6) { transition-delay: 350ms; }
  .js [data-reveal="stagger"] > :nth-child(n+7) { transition-delay: 420ms; }

  /* Tier 3b: the process line draws as it scrolls into view, where supported (Chromium today). Elsewhere it is simply drawn. */
  @supports (animation-timeline: view()) {
    @keyframes ph-grow { to { transform: scaleY(1); } }
    .ph-process-line { transform: scaleY(0); transform-origin: top; animation: ph-grow linear both; animation-timeline: view(); animation-range: entry 10% cover 55%; }
  }

  /* Accordions: animate open and close where supported; instant elsewhere */
  @supports (interpolate-size: allow-keywords) {
    :root { interpolate-size: allow-keywords; }
    details::details-content { block-size: 0; overflow: clip; transition: block-size var(--dur-3) var(--ease-out), content-visibility var(--dur-3) allow-discrete; }
    details[open]::details-content { block-size: auto; }
  }

  /* Marquee: one slow strip of the nine service names. Each copy has gap AND equal padding-inline-end so -50% loops seamlessly */
  .ph-marquee { overflow: hidden; mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); }
  .ph-marquee-track { display: flex; inline-size: max-content; animation: ph-marquee 55s linear infinite; }
  .ph-marquee-list { display: flex; gap: var(--space-7); padding-inline-end: var(--space-7); margin: 0; padding-block: 0; list-style: none; }
  .ph-marquee:is(:hover, :focus-within) .ph-marquee-track { animation-play-state: paused; }
  @keyframes ph-marquee { to { transform: translateX(-50%); } }

  /* Route cross-fade. Header is excluded so it does not flash. */
  .ph-header { view-transition-name: ph-site-header; }
  ::view-transition-old(root), ::view-transition-new(root) { animation-duration: 180ms; animation-timing-function: var(--ease-out); }

  /* Reduced motion: show everything, stop everything */
  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
    .js [data-reveal], .js [data-reveal] > * { opacity: 1 !important; transform: none !important; }
    .ph-arch-outline path { stroke-dashoffset: 0 !important; }
    .ph-process-line { transform: none !important; }
    .ph-marquee-track { animation: none !important; }
  }
}
```

The marquee second copy is `aria-hidden="true"` with `tabindex="-1"` on its links. The marquee items are real links to the service pages.

### 5.3 The few scripts

```jsx
// src/components/Reveal.jsx  (tier 3 only)
import { useEffect, useRef } from 'react';

export function Reveal({ as: Tag = 'div', stagger = false, className, children, ...rest }) {
  const ref = useRef(null);
  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    if (!('IntersectionObserver' in window)) { el.setAttribute('data-in', ''); return; }
    const io = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) { el.setAttribute('data-in', ''); io.disconnect(); }
    }, { rootMargin: '0px 0px -12% 0px', threshold: 0.12 });
    io.observe(el);
    return () => io.disconnect();
  }, []);
  return <Tag ref={ref} data-reveal={stagger ? 'stagger' : ''} className={className} {...rest}>{children}</Tag>;
}
```

```js
// src/hooks/spotlight.js  (call initSpotlight() once in main.jsx; does nothing on touch devices)
export function initSpotlight() {
  if (!matchMedia('(hover: hover) and (pointer: fine)').matches) return;
  document.addEventListener('pointermove', (e) => {
    const el = e.target.closest?.('[data-spotlight]');
    if (!el) return;
    const r = el.getBoundingClientRect();
    el.style.setProperty('--mx', `${e.clientX - r.left}px`);
    el.style.setProperty('--my', `${e.clientY - r.top}px`);
  }, { passive: true });
}
```

```jsx
// src/app/ScrollManager.jsx  (fixes issue 9: hash links never scrolled to lazy-loaded content)
import { useEffect } from 'react';
import { useLocation, useNavigationType } from 'react-router';   // use the package the project already imports

export function ScrollManager() {
  const { pathname, hash } = useLocation();
  const navType = useNavigationType();
  useEffect(() => {
    if (hash) {
      const id = decodeURIComponent(hash.slice(1));
      const started = performance.now();
      const seek = () => {
        const el = document.getElementById(id);
        if (el) { el.scrollIntoView({ block: 'start' }); return; }
        if (performance.now() - started < 3000) requestAnimationFrame(seek);   // lazy chunk may still be loading
      };
      seek();
    } else if (navType !== 'POP') {
      window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
    }
  }, [pathname, hash, navType]);
  return null;
}
```

```jsx
// src/hooks/usePageMeta.js  (fixes issues 10, 11, 15: depends on strings only, so a #hash change never resets the title)
import { useEffect } from 'react';
const SITE = 'Phelonpreneur';

export function usePageMeta(title, description) {
  useEffect(() => {
    document.title = title ? `${title} | ${SITE}` : SITE;
    if (!description) return;
    let m = document.querySelector('meta[name="description"]');
    if (!m) { m = document.createElement('meta'); m.name = 'description'; document.head.append(m); }
    m.content = description;
  }, [title, description]);
}
```

Match the title format the Laravel server already uses for service pages (read the controller or view) so the two do not fight.

```jsx
// src/app/RouteAnnouncer.jsx  (accessibility gap: focus and announcement on route change)
import { useEffect, useRef, useState } from 'react';
import { useLocation } from 'react-router';

export function RouteAnnouncer() {
  const { pathname, hash } = useLocation();
  const first = useRef(true);
  const [text, setText] = useState('');
  useEffect(() => {
    if (first.current) { first.current = false; return; }
    if (!hash) document.getElementById('main')?.focus({ preventScroll: true });   // <main id="main" tabIndex={-1}>
    const t = setTimeout(() => setText(document.title), 250);                      // after usePageMeta has run
    return () => clearTimeout(t);
  }, [pathname, hash]);
  return <div className="sr-only" role="status" aria-live="polite">{text}</div>;
}
```

---

## 6. Responsive and touch

Mobile first: write base styles for 360px, then add `min-width` queries at **40em (640px)**, **56em (900px)** and **75em (1200px)**. Use container queries (`container-type: inline-size`) on bento cells so a card adapts to its cell, not the viewport.

| Rule | Value |
|---|---|
| Minimum touch target | 44px square, 48px for primary buttons and form fields; 8px gap between targets |
| Form fields | `font-size: 16px` or larger (prevents iOS zoom), `min-block-size: 48px`, correct `type`, `inputmode` and `autocomplete` |
| Viewport units | `100dvh`, never `100vh`, for full-height sheets |
| Bottom-fixed UI | Add `padding-block-end: env(safe-area-inset-bottom)`; give the page matching bottom padding so nothing is hidden |
| Hover | Wrap all hover-only styling in `@media (hover: hover)`. Nothing may require hover |
| Horizontal rails | `scroll-snap-type: x mandatory`, `scroll-padding-inline`, visible next-card peek, no scrollbar styling hacks |
| Menu sheet | `overscroll-behavior: contain`, links at least 52px tall |
| Images | `sizes` set honestly so phones download the 480 and 800 variants |

Test viewports for every page: 320×640, 360×740, 390×844, 768×1024, 1024×768, 1366×768, 1920×1080. There must be no horizontal overflow at any of them.

Layout: content width up to 74rem (`--container`) with fluid `--gutter`. Bento: one column, then two at 40em, then 12 columns at 56em. Four-up card rows become two-up at 56em and below, and one column under 40em.

```css
/* src/styles/layout.css (inside @layer layout) */
.ph-container { inline-size: min(100% - 2 * var(--gutter), var(--container)); margin-inline: auto; }
.ph-section { padding-block: var(--section-y); background: var(--bg); color: var(--text); }
.ph-section[data-bg="tint"]  { --bg: var(--cream-200); }
.ph-section[data-bg="blush"] { --bg: var(--cream-300); }
.ph-section[data-bg="deep"]  { --bg: var(--plum-950); }
.ph-section[data-bg="band"]  { --bg: var(--plum-600); }
.ph-defer { content-visibility: auto; contain-intrinsic-size: auto 700px; }

.ph-bento { display: grid; gap: var(--space-4); grid-template-columns: 1fr; }
@media (min-width: 40em) { .ph-bento { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 56em) {
  .ph-bento { grid-template-columns: repeat(12, 1fr); gap: var(--space-5); }
  .ph-span-4 { grid-column: span 4; } .ph-span-5 { grid-column: span 5; } .ph-span-6 { grid-column: span 6; }
  .ph-span-7 { grid-column: span 7; } .ph-span-8 { grid-column: span 8; }
}
.ph-rail { display: grid; grid-auto-flow: column; grid-auto-columns: 78%; gap: var(--space-4); overflow-x: auto; scroll-snap-type: x mandatory; scroll-padding-inline: var(--gutter); }
.ph-rail > * { scroll-snap-align: start; }
@media (min-width: 56em) { .ph-rail { grid-auto-flow: row; grid-auto-columns: auto; overflow: visible; } }
```

---

## 7. Component inventory and file map

Split the current `main.jsx`, `BusinessContent.jsx` and `Pages.jsx`. Keep `http.js`, `catalog.js`, `Booking.jsx` logic and `Intake.jsx` logic. Rename files where names clash, but keep the intent.

```
src/
  main.jsx                       slim: router, providers, initSpotlight(), style import
  app/
    routes.jsx                   route table (lazy imports as today)
    Shell.jsx                    skip link, Header, <main id="main" tabIndex={-1}>, Footer, StickyCta
    Header.jsx  MenuSheet.jsx  Footer.jsx  StickyCta.jsx
    ScrollManager.jsx  RouteAnnouncer.jsx
  components/
    Button.jsx  Card.jsx  Section.jsx  Picture.jsx  Icon.jsx  ArchFrame.jsx  Reveal.jsx
    Marquee.jsx  Accordion.jsx  Field.jsx  Notice.jsx  Stepper.jsx  Prose.jsx  Toc.jsx
  sections/home/                 Hero  Marquee  WhatWeDo  Pillars  Audiences  FamilyImpact  Protect  Process  Options  Trust  ClosingCta
  pages/                         Home  Services  ServiceDetail  Book  About  StartHere  Contact  Webinar  Legal  ThankYou  NotFound
  hooks/                         usePageMeta.js  spotlight.js
  content/                       flags.js  home.js  about.js  contact.js  start-here.js  webinar.js  (hand-owned text, moved out of JSX)
  assets/fonts/
  media-manifest.json            generated by scripts/optimize-images.mjs
  styles/                        see 4.6
```

| Component | Spec |
|---|---|
| `Button` | Variants `primary`, `secondary` (1px outline), `text`. Sizes `md` (44px min) and `lg` (52px). Pill radius. One optional leading icon, never an arrow suffix. `loading` shows a CSS spinner and sets `aria-busy`. Renders `<a>` for links, `<button>` otherwise. `primary` uses `--accent` and `--on-accent`, so it is burgundy on light and gold on dark automatically |
| `Card` | `.ph-card`: radius `--r-lg`, 1px `--border`, `--surface` fill, `--shadow-1`. Anatomy: 44px icon tile (`--r-md`, gold hairline on dark, burgundy 10% fill on light), `h3`, one or two lines, optional text link. Optional `spotlight` prop adds `data-spotlight` |
| `Section` | Wraps `.ph-section` and `.ph-container`; props `bg`, `surface`, `defer`, `id`. Heading block is `h2` plus an optional lede. **No eyebrow prop** |
| `Picture` | Appendix A. The only way to render a photo |
| `Icon` | One file that imports at most 24 lucide icons by name and exposes them by key. 1.5 stroke, 22px. No legal iconography |
| `Field` | Label above, 48px control, `--line-strong` border, hint and error text linked with `aria-describedby`, error has an icon and text, not colour alone. Required marks use text, not just an asterisk |
| `Notice` | `role="status"` or `role="alert"`; tones info, success, warning, danger; uses `--notice-bg` and `--notice-ink` |
| `Stepper` | An `<ol>`. Numbers only because the content is a sequence. Current step has `aria-current="step"` |
| `Accordion` | Native `<details>` with a styled `summary`; animates through section 5.2 where supported |
| `Prose` | Long-form text: 66ch max, heading rhythm, list and table styling. Used by policies |

Add a **dev-only style guide** at `/__styleguide`, lazy-loaded behind `import.meta.env.DEV`, showing every token, button, card, field and notice. It must not appear in the production build (check `backend/public/site/` after building).

---

## 8. Pages

### 8.1 Shell

**Header** (`data-surface="dark"`, sticky, height `--header-h`):
- Left: logo mark plus the word "Phelonpreneur" set in Fraunces as real text. The current logo file has an unreadable wordmark at 44px, so use a cropped mark only (about 88×88 WebP under 6 kB) next to the text.
- Desktop (900px and up): nav links Services, About, Start here, Webinar, Contact, and a primary button "Book a free discovery call". Services opens a **panel** (click, not hover) with a two-column list of the nine services (icon, name, one line from the catalog), a link to all services, and a link to Start here. Keep the existing behaviour: `aria-expanded`, closes on Escape or outside click, returns focus.
- Scrolled state: an IntersectionObserver on a 1px sentinel at the top of the page sets `data-scrolled`, which adds a hairline bottom border and `background: rgb(27 20 28 / .86)` with `backdrop-filter: saturate(1.3) blur(12px)`. This is the only use of `backdrop-filter` on the site.
- Mobile (under 900px): logo, then a menu button (44px). The **menu sheet** is full height (`100dvh`), slides in with `transform`, has large links (52px minimum), the Services group as a `<details>`, and the primary button pinned at the bottom with safe-area padding. While it is open, set `inert` on `<main>` and the footer, close on Escape, and return focus to the menu button. No focus-trap library is needed.

**Footer** (`data-surface="dark"`, `data-bg="deep"`): four columns on desktop (brand with one-line promise and a book button, Services, Company, Legal), stacked on mobile. Below them: the non-legal disclaimer text exactly as it exists in the content, then the copyright line. Hairline gold top border at 24% opacity.

**Sticky call-to-action** (under 900px only): a 52px pill "Book a free discovery call" fixed at the bottom with 1rem margins and safe-area padding. It appears when the home hero leaves the viewport (IntersectionObserver) and on every other page after 400px of scroll. Hidden on `/book`, `/intake`, `/webinar/checkout/*` and `/webinar/resources`.

**Skip link**, `RouteAnnouncer`, `ScrollManager` and `usePageMeta` on every page. Set `viewTransition` on the header, footer and call-to-action links, and check that it does not delay navigation. If it hurts responsiveness on a mid-range phone, remove it.

### 8.2 Home (`/`, `/home`)

Hero layout on desktop. On mobile the text comes first, then the arch at about 72% width, with the glass chip overlapping its lower edge.

```text
+--------------------------------------------------------------+
| header (dark, sticky)                                        |
|                                                              |
|  Headline, up to 3 lines (Fraunces)            .---------.   |
|  Lede, 52ch max                               /           \  |
|  [Book a free discovery call] [How it works]  |   arch    |  |
|  three plain trust phrases, text only         |  portrait |  |
|                                  +------------+-----------+  |
|                                  | glass chip: next step    |  |
|                                  +--------------------------+  |
+--------------------------------------------------------------+
|  service names, slow marquee (links)                         |
+--------------------------------------------------------------+
```

| # | Section | Surface | Desktop | Mobile | Effect |
|---|---|---|---|---|---|
| 1 | Hero: headline, two buttons, portrait | dark | Two columns, text 7/12, arch 5/12 | Text then arch | Tier 1 sequence |
| 2 | Services marquee (nine names, links) | dark | Single strip under the hero | Same | Slow marquee |
| 3 | What Phelon does | light | Sticky heading left, large lede and two short paragraphs right. No cards | Stacked | None |
| 4 | Three pillars and nine services | tint | Bento. Three tall pillar panels, each listing its services as icon link rows. If the catalog has no pillar field, use a 3×3 grid where the first card of each row spans 6 columns | Single column, then two | `Reveal stagger` (tier 3a), card spotlight |
| 5 | Who we support (five audiences) | light | Top row 3 cards, bottom row 2 wider cards | Horizontal rail, 78% cards | None |
| 6 | The family gets hit too | `blush` | Arch image left, statement and three short points right | Stacked | None |
| 7 | What we help protect (four areas) | light | Four columns | 2×2 | None |
| 8 | How the process works (three steps) | tint | Horizontal, three columns, line behind the numbers | Vertical timeline | Process line (tier 3b) |
| 9 | Ways we can help (three options), and what this is not | light | Three option cards, then a **separate, clearly bordered "What this is not" panel** | Stacked | None |
| 10 | Trust and discretion | dark | Four glass tiles, lock and shield icons | 2×2 then one column | None |
| 11 | Closing call to action | band | Centred headline, one line, two buttons, arch watermark at 6% | Same | None |

- Keep all existing copy and order. Only the layout changes.
- Section 6 and the About page need a **textless** version of the family image (see section 10). The current one has baked-in text that its alt text does not convey.
- Add `.ph-defer` to sections 5 through 10.

### 8.3 Services and service detail

**`/services`**: small dark hero (title and one line), then the program overview as three option cards, then the nine services as a bento with the same card used on the home page (icon, name, one line, "View service" text link). Reuse the home components, do not fork them.

**`/services/:key`**: small dark hero with the service icon and name. Body is two columns from 1024px: the catalog content as readable prose with the sub-options as a `<details>` accordion (fix issue 12: leave `--space-5` between the sub-option cards and the note below them). A sticky aside card holds "Book a free discovery call", linking to `/book?service=<key>`. On mobile the sticky call-to-action covers it.

**Unknown key** (issue 14): guard with `Object.hasOwn(catalog, key)` and render `NotFound` with a 404 title. `/services/constructor` must not crash.

### 8.4 Booking (`/book`)

Restyle and restructure the markup. **Do not change the logic, the endpoints or the request fields.**

- Desktop: two columns. Left, a dark glass summary card showing the chosen service, call length if known, and what happens next. Right, the picker.
- Picker: a horizontal date strip (14 days, scroll-snap, 56px chips with weekday and day number) above a grid of time slots (pill buttons, 3 columns on mobile, 4 on desktop, 48px tall), then the time zone select, then details fields. Selected slot uses `--accent` fill and `aria-pressed`. Past or unavailable slots are removed, not greyed.
- States: skeleton shimmer for loading slots (transform-only), an inline notice on a 409 conflict ("That time was just taken. Pick another."), the error pattern from section 1.5, and a success panel with the reference number, a copy button, and a clear primary button to the intake form.
- Mobile: the confirm button sticks to the bottom of the viewport while a slot is selected.
- Keep `?service=` and `?subservice=` handling.

### 8.5 Intake (`/intake`)

- 11 steps. Show a progress bar and "Step 3 of 11 · Name of step". On mobile show only the current step name; on desktop show the full `Stepper` in a left column.
- One question group per screen section, large fields, a sticky bottom bar with **Back** and **Continue**, step change is a 220 ms cross-fade (tier 2).
- Conditional fields appear with a height-free fade, never by pushing the page.
- Mirror the server's rules **inline, on blur**: phone needs at least 7 digits, state codes validated as the backend validates them. Read the backend rules and mirror them exactly; do not guess. This removes errors that only appear after submit.
- File upload: a dropzone with up to 4 files and 5 MB each, checked in the browser with clear messages, shown as removable chips with name and size.
- **Do not persist answers.** Add a `beforeunload` warning while any field is dirty, and a plain notice at the top: "Your answers are not saved if you leave this page." List persistence as an owner decision in the report (section 14).
- The page needs `?appointmentId=` or `?reference=`; when missing, show a helpful state with a link to `/book`.

### 8.6 Webinar, checkout, library

- **`/webinar`**: rebuild as a component (not from `pages.json`). Hero, what you will learn, the offers (47, 497, 3,497 as cards with the $997 option leading to booking), the FAQ as an accordion, and a link to the resource library. Use at most two images and keep the page under 500 kB of images. The gold marketing graphics are much busier than the rest of the interface: use them only as contained product images inside a card, never as section backgrounds.
- Dates, the FAQ and the claims follow section 9.
- **`/webinar/checkout/:offer`**: restyle only. Offer summary card, payment link button when the backend provides one, and a clear message when it does not.
- **`/webinar/resources`**: restyle only. A two-field form (purchase reference and access code), a "Send my access again" secondary action, and a download state.

### 8.7 About, Start here, Contact, policies, thank-you, 404

These pages lost their layout because the converter strips CSS classes (issue 7). **Rebuild About, Start here, Contact and Webinar as hand-written components** with their text moved into `src/content/*.js`. Keep `pages.json` only for the four policy pages and the thank-you pages.

- **About**: story with an arch portrait, values as three cards, a "How we work" section with `id="how-we-work"` (so `/about#how-we-work` works), the disclaimer, a closing call to action. Statistics and quotes are controlled by a flag (section 9).
- **Start here**: three clear paths derived from the existing text (do not invent new paths), each a card with a button. Fix issue 13: the two links that ran together become two separate buttons.
- **Contact**: email as a real `mailto:` link with a working "Copy email" button that announces success (`role="status"`). Remove the stray "Click to copy" text. "Start with a free discovery call" links to `/book`, not the home page. State the response time as "within one business day". Do not add a contact form.
- **Policies** (`/privacy-policy`, `/terms-of-service`, `/cookie-policy`, `/disclaimer`): `Prose` layout, 66ch, with a sticky table of contents on desktop (300px column) and a collapsible `<details>` TOC on mobile. Headings get ids, links get `scroll-margin-top`. A TOC click must not change the tab title (use `usePageMeta`).
- **Thank-you** (`/thank-you`, `/thanks`): a calm success panel, one next step. Keep both routes.
- **404**: arch graphic, one line, three links (Home, Services, Book).

---

## 9. Content and trust rules

These are the status document's high-priority issues. Handle them first; do not publish anything unverified.

Create `src/content/flags.js`:

```js
// OWNER: flip these only after the business has confirmed the facts.
export const flags = {
  showUnverifiedClaims: false,   // "98% Client Satisfaction", "15+ Years Experience", client quotes
  webinarDatesConfirmed: false,  // the 22 August and 5 September 2026 dates have passed
};
```

| # | Issue | What to do |
|---|---|---|
| 1 | `guide.jpg` reads "Empathetic Legal Guidance" on a site that gives no legal advice | Do not use that image anywhere. Use another photo from the archive that has no text, or a plain arch with a plum gradient. List it in the report |
| 2 | Webinar FAQ: all seven questions are missing, only answers show | Recover the seven questions from the original PHP page (search `backend/` for the webinar view and its `<button>` elements) and put them in `src/content/webinar.js`. If a question cannot be recovered with certainty, leave a visible `OWNER:` comment and render the answer under a neutral heading. **Do not invent questions** |
| 3 | Webinar dates 22 Aug and 5 Sep 2026 have passed; "Reserve My Free Seat" leads to an unavailable notice | While `webinarDatesConfirmed` is false, replace the dates with "Next dates to be announced" and replace the seat button with "Ask to be told about the next session", linking to `/contact` |
| 4 | "98% Client Satisfaction", "15+ Years Experience", client quotes | Do not render while `showUnverifiedClaims` is false. Build the layout so it works with and without them |
| 5 | "24/7 Support" conflicts with "within one business day" | Show only "within one business day". Remove the 24/7 wording from About and Contact |
| 6 | "Readiness Blueprint" names both a guided implementation option and the $47 starter kit | **Do not resolve this yourself.** Keep each name where it is, add an `OWNER:` comment at both places, and list it in the report |

Also: the disclaimer that the business does not give legal advice must be visible in the footer on every page and in a distinct "What this is not" panel on the home and About pages.

---

## 10. Images and media

The snapshot found 84 files (43.6 MB) in `public/media/`. The frontend source uses 12. The other 72 (about 32 MB) are copied into every build. `this.png` is 2.9 MB, product images are 1.6 to 2.1 MB each, and `logo.png` is 382 kB shown at 44px.

**Procedure** (Phase 7):

1. List used images: search `src/` (including `content/` and `pages.json`), `index.html` and `catalog.js` for `media/`.
2. For the other 72, search `backend/` and any templates for each file name. A file that is still referenced from outside `frontend/src` stays at its **old path, unchanged**, in `public/media/`. List these in the report.
3. Create `frontend/media-src/` (used originals) and `frontend/media-archive/` (unreferenced originals, never built, never deleted). Move files with `git mv` or `mv`. Do not delete anything.
4. Run `npm run images` (Appendix A). It creates AVIF and WebP at 480, 800, 1280 and 1920 widths (never enlarging) in `public/media/`, plus `src/media-manifest.json`.
5. Replace every `<img>` with `Picture`. The hero portrait gets `priority`. Set `sizes` honestly, for example `(min-width: 900px) 40vw, 80vw`.
6. Make four new assets: the logo mark (about 88×88), a **textless** crop for the family image, an Open Graph image (1200×630, under 100 kB JPEG), and a favicon set.
7. Run `npm run build` and `npm run check:budget`.

Image rules: serve 2× for sharpness on high-density screens (that is what the 1280 and 1920 variants are for), keep the hero image under 120 kB at its largest displayed variant, and never ship a PNG photo.

```jsx
// src/components/Picture.jsx
import manifest from '../media-manifest.json';
const BASE = `${import.meta.env.BASE_URL}media/`;

export function Picture({ name, alt, sizes = '100vw', priority = false, className }) {
  const m = manifest[name];
  if (!m) {
    if (import.meta.env.DEV) console.warn(`[Picture] unknown image "${name}"`);
    return null;
  }
  const set = (ext) => m.widths.map((w) => `${BASE}${name}-${w}.${ext} ${w}w`).join(', ');
  return (
    <picture>
      <source type="image/avif" srcSet={set('avif')} sizes={sizes} />
      <source type="image/webp" srcSet={set('webp')} sizes={sizes} />
      <img
        src={`${BASE}${name}-${m.widths.at(-1)}.webp`}
        width={m.w} height={m.h} alt={alt} className={className}
        loading={priority ? 'eager' : 'lazy'}
        decoding={priority ? undefined : 'async'}
        fetchPriority={priority ? 'high' : 'auto'}
      />
    </picture>
  );
}
```

---

## 11. Accessibility

Keep what exists (skip link, visible focus, labelled fields, menu behaviour, status and alert roles, text contrast at AA or better). Fix the three known gaps and meet this list. WCAG 2.2 AA is the bar.

- Input and control borders use `--line-strong` (4.23:1). The old `#aa9387` failed at 2.86:1.
- Focus moves to `<main>` and a polite live region announces the new page title on every route change (`RouteAnnouncer`).
- The family image gets either a textless version (preferred) or an `alt` that conveys all text in it.
- One `<h1>` per page, no skipped heading levels, landmarks for header, nav, main and footer.
- Every icon-only button has an accessible name; decorative icons and SVGs are `aria-hidden`.
- Error messages are linked to fields with `aria-describedby`, and are not colour alone.
- Reduced motion shows everything immediately (section 5.2). Test with the OS setting on.
- 200% zoom and 320px width cause no loss of content or horizontal scroll.
- Hover is never required. Targets are at least 44px.
- Add `@media (forced-colors: active)` fallbacks: buttons keep a visible border, and the arch outline uses `CanvasText`.
- No screen reader testing was done before this redesign. Run axe (Appendix C) and do one manual pass with VoiceOver or NVDA on the home page, booking and intake, and say in the report what was and was not tested.

---

## 12. Execution plan

Run `npm run verify` at the end of every phase. A phase is not finished until it is green.

### Phase 0: preflight, install, baseline

```bash
cd <project>/frontend
git status --short | head -20                       # note the state; do not commit
tar --exclude=node_modules -czf ../frontend-backup-20261128.tgz .
tar -czf ../site-build-backup-20261128.tgz -C ../backend/public site   # the build empties this folder
npm install                                         # then the commands in section 3
npm run build                                       # record sizes in the report as "before"
```

Add the scripts and files from the appendices, and merge these into the existing `scripts` block of `package.json` (keep the existing `dev` and `build`):

```json
{
  "scripts": {
    "lint": "eslint .",
    "images": "node scripts/optimize-images.mjs",
    "analyze": "vite build --mode analyze",
    "check:budget": "node scripts/check-budget.mjs",
    "test:e2e": "playwright test",
    "verify": "npm run lint && npm run build && npm run check:budget"
  }
}
```

`npm run analyze` also rebuilds into `backend/public/site/`. That is expected; the report is written to `qa/bundle.html`.

Add `qa/` to `.gitignore`. Take **baseline screenshots** of every route at 390 and 1366 into `qa/baseline/` with `SHOTS=baseline npx playwright test` (PowerShell: `$env:SHOTS='baseline'; npx playwright test`). Run this against the **old** site: its assertions (single `h1`, specific titles, axe) are expected to fail. Only the screenshots matter at this stage.

### Phase 1: foundations

Fonts (section 3), `tokens.css`, the layered CSS files (4.6), `index.html` changes (below), `vite.config.js` changes (below), `Picture`, `usePageMeta`, `ScrollManager`, `Reveal`, `initSpotlight`, ESLint config.
**Gate:** `npm run verify` green; every old page still renders (old `site.css` still loaded).

`index.html` additions. Keep the existing description and favicon:

```html
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta name="theme-color" content="#1b141c" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Phelonpreneur" />
<meta property="og:title" content="Phelonpreneur" />
<meta property="og:description" content="(same text as the meta description)" />
<meta property="og:image" content="https://YOUR-DOMAIN/site/media/og-1200x630.jpg" /> <!-- OWNER: needs the real domain, absolute URL -->
<meta name="twitter:card" content="summary_large_image" />
<link rel="preload" href="/src/assets/fonts/fraunces-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin />
<link rel="preload" href="/src/assets/fonts/plus-jakarta-sans-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin />
<script>document.documentElement.classList.add('js')</script>
<style>html{background:#fbf6ed}body{margin:0}#root:empty{min-height:100dvh;background:linear-gradient(#1b141c 0 4rem,#fbf6ed 4rem)}</style>
```

The inline script makes reveal states apply only when JavaScript runs. The inline style (under 1 kB) avoids a white flash before React mounts. **Check the built `backend/public/site/index.html`**: the two preload `href`s must have been rewritten to hashed `/site/assets/...woff2` paths. If Vite left them as `/src/...`, add a ten-line plugin using `transformIndexHtml` that looks the hashed font file names up from the bundle.

`vite.config.js`: **read the existing file first and merge**. Keep `base`, `build.outDir`, `emptyOutDir` and the `/api` proxy exactly as they are. The finished file has this shape:

```js
// frontend/vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig(({ mode }) => ({
  // ...existing keys stay as they are: base, server.proxy (/api -> PHELON_API_TARGET), and so on
  plugins: [react(), mode === 'analyze' && visualizer({ filename: 'qa/bundle.html', gzipSize: true })].filter(Boolean),
  build: {
    // ...existing outDir ('../backend/public/site') and emptyOutDir stay as they are
    sourcemap: false,
    rollupOptions: {
      output: {
        // stable vendor chunk: stays cached across deploys when only app code changes
        manualChunks(id) {
          if (/node_modules[\\/](react|react-dom|scheduler|react-router|react-router-dom)[\\/]/.test(id)) return 'react';
        },
      },
    },
  },
}));
```

### Phase 2: shell

Header with services panel and mobile menu sheet, footer, sticky call-to-action, skip link, `RouteAnnouncer`, the dev-only style guide, `Button`, `Card`, `Section`, `Icon`, `Field`, `Notice`, `Accordion`.
**Gate:** header and footer correct at all seven viewports; keyboard only: tab order, menu open and close, Escape, focus return; `inert` really blocks the page behind the sheet.

### Phase 3: home

All eleven sections (8.2).
**Gate:** budget green; Lighthouse mobile on the home page meets section 2; the hero sequence is smooth on a throttled CPU (DevTools 4× slowdown); reduced motion shows everything.

### Phase 4: services, service detail, booking

**Gate:** the unknown-key route shows 404; booking works against the real API (see section 13) or against Playwright route mocks; no logic or field changes (diff `Booking.jsx` logic).

### Phase 5: content pages

About, Start here, Contact, Webinar (with section 9 applied), the policy `Prose` layout and TOC, thank-you, 404, `src/content/*.js`.
**Gate:** `/about#how-we-work` and `/webinar#registration` scroll to their sections on a cold load; the Privacy Policy TOC does not reset the tab title; every route has a specific title.

### Phase 6: intake, checkout, library, and old CSS removal

Restyle (8.5, 8.6). Delete `site.css` and the compatibility aliases once `grep -r "site.css\|--paper\|--dark\|--cream\b" src` finds nothing.
**Gate:** all flows work; initial CSS is within budget.

### Phase 7: images and cleanup

Section 10.
**Gate:** `check:budget` green; the webinar page images are under 500 kB; nothing referenced from outside was removed.

### Phase 8: QA and report

Section 13, then the report (section 14).

---

## 13. QA

1. `npm run verify` green.
2. `npm run test:e2e` green (Appendix C). Add tests for: the menu (opens, `inert` page, Escape closes, focus returns), hash scroll on a cold load, the unknown-service 404, and reduced-motion visibility.
3. **Start the real backend** and test booking, intake, checkout and the library end to end. The snapshot could not: the API at `127.0.0.1:8000` was not running.

```bash
cd ../backend && php artisan serve --host=127.0.0.1 --port=8000
```

   If it will not start within ten minutes, test those flows with Playwright route mocks that match the real response shapes in `src/http.js` and the backend controllers, and say so in the report. **Do not submit real payments, send real emails or write real bookings.** Use the backend's test mode, or stop at the last step before submit.
4. Lighthouse against the production build served by Laravel (so it matches real conditions):

```bash
npm run build
npx lighthouse http://127.0.0.1:8000/ --form-factor=mobile --only-categories=performance,accessibility,best-practices,seo --output=html --output-path=qa/lighthouse-home.html --chrome-flags="--headless=new"
```

5. Compare `qa/baseline/` and `qa/after/` screenshots at 390 and 1366 for every route.
6. Check on a throttled CPU (DevTools 4× slowdown) that scrolling stays smooth and the hero sequence does not stutter.
7. Check the built output: no `/__styleguide` code, no unused font files, and no request to any other origin.

---

## 14. Completion

### Final checklist

- [ ] Section 8 routes all redesigned; no horizontal overflow at the seven viewports
- [ ] Budget (section 2) green and Lighthouse targets met
- [ ] Axe: zero serious or critical violations on every route
- [ ] Six trust issues handled (section 9); flags default to `false`
- [ ] No new classes without the `ph-` prefix; `site.css` removed
- [ ] No third-party origin; no animation library; no intake data in browser storage
- [ ] Nothing deleted: unused images are in `media-archive/`
- [ ] Backups exist and are named in the report
- [ ] `git status` reviewed; nothing committed

### Report: `frontend/REDESIGN-REPORT.md`

```md
# Redesign report
## What changed (5 to 10 lines)
## Before and after
| Measure | Before | After |   (initial JS gz, CSS gz, fonts, home images, Lighthouse four scores, LCP, CLS, TBT)
## Tests
What passed. What was run against the real API and what was mocked. Screen reader pass: what was and was not done.
## Deviations from the brief and why
## Owner decisions needed
## Known gaps
```

### Owner decisions to list (at minimum)

1. Confirm or remove "98% Client Satisfaction", "15+ Years Experience" and the client quotes, then flip `showUnverifiedClaims`.
2. New webinar dates, then flip `webinarDatesConfirmed`; and confirm the recovered FAQ questions.
3. Which name wins: "Readiness Blueprint" for the guided option or for the $47 kit.
4. Approve the replacement for the `guide.jpg` "Legal Guidance" image.
5. Whether the 72 archived images can be deleted after the owner checks external links.
6. The production domain for the Open Graph image, and payment links for the $497 and $3,497 offers in the backend environment.
7. Whether intake answers should ever be saved in the browser (privacy trade-off), or the page left as memory-only with a leave warning.
8. Server settings outside this task: long-lived immutable cache headers for `/site/assets/*`, and gzip or Brotli. Consider prerendering the public routes later for faster first paint and link previews; the project uses React Router in library mode, so that is a separate piece of work.

---

## Appendix A: scripts

### `scripts/optimize-images.mjs`

Tested with sharp 0.35 on Node 22. Reads `media-src/`, writes `public/media/` and `src/media-manifest.json`.

```js
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
```

### `scripts/check-budget.mjs`

Tested against a fake build. Run after `npm run build`. Exits 1 when over budget.

```js
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
```

## Appendix B: `eslint.config.js`

Tested with ESLint 9. It passes clean code and flags a missing `alt` and an unused variable. If the new `react-hooks` v7 rules flag many existing files, set those specific rules to `'warn'` and list them in the report. Do not rewrite working code only to silence them.

```js
// frontend/eslint.config.js  (ESLint 9 flat config)
import js from '@eslint/js';
import globals from 'globals';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import reactHooks from 'eslint-plugin-react-hooks';
import reactRefresh from 'eslint-plugin-react-refresh';

export default [
  { ignores: ['dist', 'node_modules', 'qa', 'media-src', 'media-archive', 'public'] },
  js.configs.recommended,
  jsxA11y.flatConfigs.recommended,
  reactHooks.configs.flat.recommended,
  reactRefresh.configs.vite,
  {
    files: ['**/*.{js,jsx}'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: globals.browser,
      parserOptions: { ecmaFeatures: { jsx: true } },
    },
    rules: {
      // Components used only as <Foo /> are not seen as "used" without eslint-plugin-react.
      'no-unused-vars': ['error', { varsIgnorePattern: '^[A-Z_]', argsIgnorePattern: '^_' }],
    },
  },
  {
    files: ['scripts/**', 'e2e/**', '*.config.js'],
    languageOptions: { globals: globals.node },
  },
];
```

## Appendix C: Playwright

`playwright.config.js`:

```js
// frontend/playwright.config.js
import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: 'e2e',
  timeout: 30_000,
  use: { baseURL: 'http://127.0.0.1:5173' /* , channel: 'chrome' */ },
  webServer: {
    command: 'npm run dev -- --host 127.0.0.1 --port 5173',
    url: 'http://127.0.0.1:5173',
    reuseExistingServer: true,
    timeout: 60_000,
  },
  projects: [
    { name: 'mobile', use: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, deviceScaleFactor: 2 } },
    { name: 'desktop', use: { viewport: { width: 1366, height: 768 } } },
  ],
});
```

`e2e/smoke.spec.js`. Fill in one real service key from `backend/resources/catalog/services.json`. Refine the API mock to the real response shapes in `src/http.js`. Run `SHOTS=after npx playwright test` to save full-page screenshots (reduced motion is forced so every section is visible).

```js
// frontend/e2e/smoke.spec.js
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

const SERVICE_KEY = 'REPLACE_ME'; // a real key from services.json
const routes = [
  '/', '/services', `/services/${SERVICE_KEY}`, '/services/not-a-service', '/book', '/intake?reference=TEST',
  '/webinar', '/webinar/checkout/47', '/webinar/resources', '/about', '/start-here', '/contact',
  '/privacy-policy', '/terms-of-service', '/cookie-policy', '/disclaimer', '/thank-you', '/no-such-page',
];

test.beforeEach(async ({ page }) => {
  // The API may be offline. Keep the UI deterministic. Refine these shapes after reading src/http.js.
  await page.route('**/api/**', (route) => route.fulfill({ status: 200, contentType: 'application/json', body: '{}' }));
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
```