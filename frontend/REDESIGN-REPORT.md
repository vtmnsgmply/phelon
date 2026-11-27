# Redesign report

Written 28 November 2026. Brief: `docs/me.md`. Nothing is committed.

## What changed

- Every public route was redesigned, mobile first, on a new token-based design system (`src/styles/`, `ph-` classes). `site.css` is gone.
- New shell: header with services panel, mobile menu sheet, footer, sticky booking button, route announcements, hash scrolling and page titles.
- About, Start here, Contact and Webinar are hand-built components with text in `src/content/`. `pages.json` now holds only the policy and thank-you pages.
- Booking has a date strip, time buttons, inline phone and state checks and a success panel. Intake has a stepper, upload chips and leave warnings. Request fields are unchanged.
- Trust issues are handled, with unverified material behind flags that default to `false`.
- Images go through `npm run images` (AVIF and WebP). Unused originals are in `media-archive/`.
- Added at the owner's request during the work: photos on the service cards, cover photos behind selected sections, a more prominent header button, and a shared `CheckList` component.

## Before and after

Lighthouse mobile, home page. "Before" and "After" were served by `php artisan serve`, which does not compress. "After, gzip" used a temporary static server with compression and long-lived asset caching.

| Measure | Before | After | After, gzip | Target |
|---|---|---|---|---|
| Initial JavaScript, gzip | 92.8 kB | 95.3 kB | | ≤ 110 kB |
| Initial CSS, gzip | 3.2 kB | 8.8 kB | | ≤ 14 kB |
| Web fonts | none | 62.5 kB | | ≤ 70 kB |
| Home images, first load | 498 KiB | 78 KiB | | ≤ 450 kB |
| Home images, full scroll (phone / desktop) | not measured | 170 / 197 kB | | ≤ 450 kB |
| Webinar images, full scroll | about 8 MB | 43 / 57 kB | | ≤ 500 kB |
| Cold home load, all bytes | 812 KiB | 496 KiB | 262 KiB | ≤ 700 kB |
| Performance | 75 | 86 | **88** | ≥ 90 |
| Accessibility / Best practices / SEO | 100 / 100 / 100 | 100 / 100 / 100 | | ≥ 95 |
| LCP | 5.4 s | 3.6 s | **2.7 s** | ≤ 2.5 s |
| CLS | 0 | 0 | 0 | ≤ 0.05 |
| Total blocking time | 150 ms | 40 ms | **330 ms** | ≤ 200 ms |

**The speed targets are not met.** Compression accounts for part of the gap, not all of it. Blocking time varied widely between runs. Other pages on the uncompressed server scored: About 79, Booking 82, Services 80, Webinar 82 (accessibility and SEO 100 on all; Booking best practices 96, from the 503 response of unconfigured scheduling). About scored 92 with compression. Prerendering the public routes is the likely next step.

## Tests

- `npm run verify` passes (3 lint warnings, listed below).
- `npm run test:e2e`: 72 passed, 6 skipped because they apply to only one screen size. Uses the installed Chrome and a mocked API.
- Covered: every route without console errors; axe with no serious or critical violations; no horizontal overflow at the seven viewports; one `h1` and no skipped headings; menu, services panel and sticky button; cold-load hash scrolling; unknown service keys; policy contents links; reduced motion; hidden claims and dates; booking, intake, checkout and library request shapes; nothing written to browser storage during intake; no third-party requests.
- Real API (nothing booked, paid, emailed or stored): checkout $47 returns a link; $497 and $3,497 report not open; booking reports that scheduling is not available; an invalid intake link is refused; library resend reports that access is not enabled (this also exercised the CSRF flow).
- **Mocked only:** a successful booking, intake submission and library verification and download.
- **Not done:** screen reader pass (VoiceOver or NVDA), CPU-throttled animation check, real-device testing, 200% zoom check.

## Deviations from the brief and why

| Deviation | Reason |
|---|---|
| Phases were not verified and screenshotted one by one | Built in larger passes, verified at checkpoints |
| Backups are in `frontend/backups/` | The brief's paths are in the repository root, which Apache serves |
| `viewTransition` route cross-fade not added | React Router ignores it with `BrowserRouter`; it needs the data router |
| Desktop navigation starts at 992px, not 900px | The brand, five items and button do not fit at 900px |
| `content-visibility` deferral removed | It made anchors land in the wrong place and left a section blank in captures |
| Phone and state checks are inline on booking, not intake | The server has those rules for booking only. Intake mirrors its own rules: required, email, date |
| The date strip pages through the whole booking window | A fixed 14 days would have removed later dates |
| Home hero's second button is "How it works" | Matches the brief's hero layout |
| Booking links no longer add `#booking` | The page now starts at its heading. The anchor still exists |
| Photos on service cards and cover images | Owner request. Each cover has a dark scrim; text stays real text |
| Policy pages drop decorative emoji, the breadcrumb and the old quick-navigation list; some heading levels are renumbered | Design and accessibility. Policy wording is unchanged |
| Thank-you entries in `pages.json` were restructured | To lay them out and to hide the testimonial and statistics |
| `react-hooks/set-state-in-effect` is a warning | It flags three existing data-loading effects that were kept as written |
| The pre-mount style is injected by a small Vite plugin | Vite's inline style handling failed with a lower-case Windows drive letter |

## Owner decisions needed

1. Confirm or remove "98% Client Satisfaction", "15+ Years Experience", "100% Confidential" and the client quotes (About and thank-you), then set `showUnverifiedClaims`.
2. New webinar dates, then `webinarDatesConfirmed`. Confirm the seven FAQ questions, recovered word for word from `backend/legacy/webinar_index.php`.
3. "Readiness Blueprint" names both a guided option and the $47 kit.
4. The product artwork disagrees with the page text: the $3,497 image says "Executive Readiness Concierge", the $497 image "Build Your Personal Readiness Blueprint", the $47 image "Starter".
5. **Approve the photos.** Several appear to be AI-generated: they carried a generator mark in the corner, which the crops remove. Confirm you have the right to use them, whether they need disclosure, and that nobody will read them as real clients. This includes the replacement for the "Legal Guidance" image.
6. The thank-you pages promise confirmation emails. The booking code reviewed does not send one.
7. Whether the archived images can be deleted after checking external links.
8. The real domain for the social share image (`index.html`), and payment links for $497 and $3,497.
9. Whether intake answers should ever be saved in the browser. They are currently kept in memory only.
10. Server settings: compression, long-lived caching for `/site/assets/*`.

## Known gaps

- **Backend bug, not fixed (backend out of scope): unknown addresses return a blank page.** `backend/routes/web.php` sends `index.html` with `response()->file()` and then sets status 404 (or 400 for a bad booking link). Laravel's file response sends no body for non-success statuses, so visitors never see the 404 page. Returning the file's contents with `response(file_get_contents($file), $status)` should fix it.
- Online scheduling is not configured (`PHELON_BOOKING_*`), so nobody can book against this backend yet.
- Do not run `backend/tools/export-legacy-content.php`. It would now break the thank-you pages.
- `frontend/FRONTEND.md` describes the site before this redesign.
- Unused images remain in the Git working tree (`media-archive/`, about 38 MB).
