# PHELONPRENEUR frontend: status and design

> **Out of date:** the site was redesigned after this snapshot. See [REDESIGN-REPORT.md](REDESIGN-REPORT.md).

Snapshot taken 28 November 2026, about 01:45 local time. The frontend is not yet committed to Git, and the code was changing while this was written. Re-check anything that matters before relying on it.

## 1. Status at a glance

| Area | Status |
|---|---|
| Production build | Succeeds. A test build matched the file names in `backend/public/site/` |
| Page rendering | 14 routes loaded at 1366px and 390px: no JavaScript errors, broken images or horizontal overflow |
| Home, services, booking layout | Designed and responsive |
| About, Start Here, Contact, Webinar, policy pages | Content shows, but mostly without layout (see section 8) |
| Booking, intake, checkout, resource library | Built. **Not tested end to end**: the API at `127.0.0.1:8000` was not running |
| Automated tests, linting, type checking | None found |

**Most urgent:** one image advertises "Legal Guidance" on a site that says it gives none, the webinar page has lost its FAQ questions, and its dates have passed. See section 8.

## 2. Stack

| Package | Installed version |
|---|---|
| React, React DOM | 19.3.1 |
| React Router | 7.18.7 |
| Vite | 7.3.8 |
| `@vitejs/plugin-react` | 5.2.0 |

Plain JavaScript (JSX) and one hand-written stylesheet. No CSS framework, UI library or state library.

### Run and build

```bash
cd frontend
npm install
npm run dev      # http://127.0.0.1:5173
npm run build    # writes to ../backend/public/site/
```

- In development, `/api` requests are proxied to `PHELON_API_TARGET`, which defaults to `http://127.0.0.1:8000`.
- The build **empties** `backend/public/site/` before writing to it.
- Production assets are served under `/site/`. Laravel serves `index.html` for the public routes.

### Build output

| File | Size | Gzipped |
|---|---|---|
| Main JavaScript | 293.6 kB | 92.8 kB |
| Content pages chunk | 91.2 kB | 23.8 kB |
| Intake chunk | 17.9 kB | 5.2 kB |
| CSS | 10.4 kB | 3.2 kB |

## 3. Structure

```
frontend/
├── index.html            Page shell, meta description, favicon
├── vite.config.js        Build output, base path, API proxy
├── public/media/         Images (copied into every build)
└── src/
    ├── main.jsx          Router, nav, footer, home, services, booking, 404
    ├── BusinessContent.jsx  Home page sections and shared content blocks
    ├── Pages.jsx         Content pages, checkout, resource library
    ├── Booking.jsx       Appointment picker
    ├── Intake.jsx        Multi-step intake form
    ├── http.js           API client and CSRF handling
    ├── catalog.js        Service data, shared text, link and media helpers
    ├── site.css          All styles
    └── content/pages.json   Converted content for 10 pages
```

### Where content comes from

| Content | Source | How to edit |
|---|---|---|
| Home sections, booking details, program cards | `src/BusinessContent.jsx` and `src/main.jsx` | Edit the JSX arrays |
| Nine services | `backend/resources/catalog/services.json` | Edit the JSON (shared with the backend) |
| Intake questions | `backend/resources/catalog/intake.json` | Edit the JSON (shared with the backend) |
| About, Start Here, Contact, Webinar, policies, thank-you pages | `src/content/pages.json` | Generated from the old PHP pages |

`backend/tools/export-legacy-content.php` generates `pages.json`, `services.json` and `intake.json`. **Running it again overwrites hand edits to all three.**

## 4. Routes

| Route | Page | Source |
|---|---|---|
| `/`, `/home` | Home | JSX |
| `/services` | Service directory and program overview | JSX + catalog |
| `/services/:key` | Service detail (9 services) | Catalog |
| `/book` | Discovery call booking. Accepts `?service=` and `?subservice=` | JSX |
| `/intake` | Readiness intake. Needs `?appointmentId=` or `?reference=` | Catalog |
| `/webinar` | Webinar landing page | `pages.json` |
| `/webinar/checkout/:offer` | Checkout for offers 47, 497, 3497 | JSX |
| `/webinar/resources` | Access a purchased document | JSX |
| `/about`, `/start-here`, `/contact` | Company pages | `pages.json` |
| `/privacy-policy`, `/terms-of-service`, `/cookie-policy`, `/disclaimer` | Policies | `pages.json` |
| `/thank-you`, `/thanks` | Confirmation pages. No link found to them inside the app | `pages.json` |
| anything else | 404 | JSX |

The content pages, checkout, resource library and intake are loaded only when visited.

### Home page, top to bottom

1. Hero with headline, two buttons and portrait
2. What Phelon does
3. Three pillars of readiness, plus all nine services
4. Who we support (five audiences)
5. The family gets hit too
6. What we help protect (four areas)
7. How the process works (three steps)
8. Ways we can help (three options) and what this is not
9. Trust and discretion
10. Closing call to action

## 5. Features and API use

| Feature | Endpoints | Behaviour |
|---|---|---|
| CSRF | `GET /api/context` | Token fetched before the first write; cleared on a 419 response |
| Booking | `GET /api/availability`, `POST /api/bookings` | Pick date, time and time zone; reloads times on a 409 conflict; shows a reference and an intake link |
| Intake | `GET /api/intake/status`, `POST /api/intake` | 11 steps, 55 defined fields (some conditional), up to 4 files of 5 MB each |
| Checkout | `GET /api/checkout/:offer` | Shows a payment link if the backend has one configured |
| Resource library | `POST /api/library/verify`, `POST /api/library/resend` | Purchase reference plus access code, then a download link |

The request fields the frontend sends match the backend validation rules as read on the snapshot date.

Things to know:

- **Intake answers are held in memory only.** Reloading or leaving the page loses all progress.
- The server validates phone numbers (at least 7 digits) and state codes more strictly than the form does, so some errors appear only after submitting.
- Checkout has a default payment link for the $47 offer only. The $497 and $3,497 links must be set in the backend environment.
- The $997 offer has no checkout; it leads to the booking page.
- The resource library must be enabled in the backend, and has documents for the $47 and $497 purchases only.
- When the API is unreachable, the booking page shows "The request could not be completed" and a refresh button.

## 6. Design system

### Direction

A restrained, premium look: dark plum and gold for the header, hero and footer; warm cream and paper for reading areas; serif headings over sans-serif body text. Sections on the home page alternate background tints. The tone is calm and discreet.

### Colour tokens

| Token | Value | Use |
|---|---|---|
| `--ink` | `#201821` | Body text |
| `--cream` | `#fbf6ed` | Page background |
| `--paper` | `#fffdfa` | Cards and panels |
| `--muted` | `#67595f` | Hints and secondary text |
| `--gold` | `#e9c986` | Accent on dark backgrounds |
| `--burgundy` | `#672a43` | Links, buttons, eyebrows |
| `--line` | `#ddd0c3` | Borders |
| `--dark` | `#1b141c` | Header, hero, footer |

Frequently used values that are not yet tokens: `#4d2437` (closing band), `#f1e5d8` (tinted sections), `#e9d9d3` (trust section), `#f5e6dd` and `#572b3c` (notices), `#ae6544` (focus ring), `#aa9387` (input borders). The browser `theme-color` (`#1a1218`) differs slightly from `--dark`.

There is a light theme only.

### Typography

| Element | Style |
|---|---|
| Body | `Inter, system-ui, sans-serif`, 16px, line height 1.62 |
| Headings | Georgia, weight 500, line height 1.11, tight letter spacing |
| H1 | 2.45 to 5.6rem, fluid (home: 2.35 to 4.85rem) |
| H2 | 1.85 to 3.15rem, fluid |
| H3 | 1.35 to 1.75rem, fluid |
| Eyebrow | Uppercase, spaced, bold, 0.875rem |

**Inter is named but never loaded.** Visitors see it only if it is installed on their device; otherwise they get the system font.

### Layout

- Content width: up to 74rem, with fluid side margins.
- Two-column split: 1.1 to 0.9. Card grids: 2, 3 or 4 columns.
- Header is sticky.

| Breakpoint | Change |
|---|---|
| 900px and below | Menu button replaces the inline nav; four-card grid becomes two |
| 670px and below | Most grids become one column |
| 470px and below | Form fields stack; portrait shrinks |

### Components

| Component | Classes | Notes |
|---|---|---|
| Buttons | `.button`, `.gold`, `.quiet`, `.outline`, `.text-button` | Minimum height 2.8rem |
| Cards and panels | `.card`, `.panel`, `.business-cards` | Thin border, small radius |
| Hero | `.hero`, `.home-hero`, `.small` | Radial plum gradient |
| Portrait | `.portrait` | Arched top with caption |
| Closing band | `.closing` | Centred call to action |
| Forms | `.form-stack`, `.form-grid` | Labels above fields |
| Messages | `.notice`, `.hint` | |
| Disclosure | `<details>` | Services menu, booking questions |
| Generic content | `.article` | Used by all converted pages |

### Accessibility

In place:

- Skip link, visible focus outline, labelled form fields.
- Menu has `aria-expanded`, closes on Escape or an outside click, and returns focus.
- Status and error messages use `role="status"` or `role="alert"`.
- All text colour pairs measured pass WCAG AA (lowest was 6.15:1).

Gaps:

- Input borders measure 2.90:1 against white, below the 3:1 guideline.
- Focus is not moved or announced when the route changes.
- The family image on the home page contains text that its alt text does not convey.

This was not a full accessibility audit; no screen reader testing was done.

## 7. Images

- `public/media/` holds 84 files, 43.6 MB. The frontend source references 12 of them. The other 72 (about 32 MB) are still copied into each build. Check the backend and any external links before deleting them.
- Large files in use: `this.png` 2.9 MB, product images 1.6 to 2.1 MB each, `logo.png` 382 kB (shown at 44px).
- The webinar page references about 8 MB of images.
- The logo file includes a wordmark that is unreadable at header size.
- The gold marketing graphics are much busier than the rest of the interface.

## 8. Open issues

### High priority: content and trust

| # | Issue | Where |
|---|---|---|
| 1 | Image reads "Empathetic **Legal Guidance**", contradicting the non-legal disclaimer | `guide.jpg` on About |
| 2 | All seven FAQ questions are missing; only answers show. The converter drops `<button>` tags | Webinar |
| 3 | Advertises 22 August and 5 September 2026, both past; "Reserve My Free Seat" leads to an unavailable notice | Webinar |
| 4 | Claims "98% Client Satisfaction", "15+ Years Experience" and client quotes. Confirm they are accurate and approved | About |
| 5 | "24/7 Support" conflicts with "within one business day" | About, Contact |
| 6 | "Readiness Blueprint" names both a guided implementation option and the $47 starter kit | Home, Webinar |

### Medium priority: layout and behaviour

| # | Issue | Evidence |
|---|---|---|
| 7 | Converted pages lost their layout: the converter removes CSS classes, so cards, statistics and button groups appear as stacked text. About and Webinar are the worst | Seen in browser |
| 8 | Contact shows a stray "Click to copy", the email is not clickable, and "Start With a Free Discovery Call" goes to the home page | Seen in browser and content |
| 9 | Direct links to `/about#how-we-work` and `/webinar#registration` do not scroll to the section | Reproduced |
| 10 | Clicking a table-of-contents link on Privacy Policy resets the tab title to the generic site name | Reproduced |
| 11 | Checkout, resource library and 404 pages have no specific tab title | Seen in browser |

### Low priority

| # | Issue | Evidence |
|---|---|---|
| 12 | The note under the service sub-options touches the cards | Seen in browser (Reputation service) |
| 13 | Two links on Start Here run together as one line of text | Seen in browser |
| 14 | A URL such as `/services/constructor` would likely crash the page | Code reading only |
| 15 | Only service pages get a page-specific title and description from the server; no social sharing tags | Code reading |
| 16 | Colour and base styles are duplicated between this app and the staff app | Code reading |

## 9. Related: staff app

`login/` is a separate React app, outside `frontend/`. It builds to `backend/public/staff/`, is reached at `/login/`, and offers sign-in, upcoming bookings, and intake review with attachment downloads and printing. It has its own copies of the base styles and API client. The public footer no longer links to it. It was reviewed by reading its code only.

## 10. Suggested next steps

1. Replace the "Legal Guidance" image and confirm the About page claims.
2. Update or temporarily hide the webinar dates, and restore the FAQ questions.
3. Rebuild About, Contact, Start Here and Webinar as proper components, as the home page already is.
4. Fix section scrolling and page titles.
5. Compress images, remove unused ones, and either load Inter or drop it from the font list.
6. Start the backend and test booking, intake, checkout and the resource library end to end.
7. Add linting and a few browser tests, then commit.

## How this was checked

Source reading, a test build into a temporary folder, and read-only page loads in headless Chrome against the running development server. No forms were submitted. Payment, email, file upload and database behaviour were not exercised.
