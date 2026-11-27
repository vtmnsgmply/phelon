# PHELONPRENEUR Network — Services & Discovery Call Implementation

**Priority:** High | **Target:** Local development / isolated staging only

See the [implementation and verification report](services-implementation-report.md) for the native booking flow and staff sign-in setup. The provider-specific requirements in the original brief were superseded by the native booking request.

## 1. Codex mission

Act as a senior PHP full-stack engineer. Implement, test, and document the services dropdown, nine dedicated service landing pages, and a centralized 15-minute discovery-call workflow in the existing `Phelon/` repository. Do not merely provide a plan: inspect the code, make the changes, run available tests, and report evidence. Do not deploy to production or send real emails, create live appointments, or mutate live CRM records.

## 2. Repository discovery and safeguards

The supplied project tree identifies these candidate files; their contents and actual integrations must be verified before editing:

| Existing path | Inspect for |
|---|---|
| `nav.php`, `footer.php` | Shared navigation, footer, links and accessibility |
| `Services.php`, `components/index/services.php`, `index.php` | Existing services and homepage entry points |
| `Book.php`, `components/booking-picker.php`, `css/book.css`, `api/bookings.php` | Native discovery call and availability |
| `Login/`, `app/` | Staff sign-in and shared PHP services |
| `env.php`, `create_database.php`, `create_table.php`, `database/native-booking-and-login.sql` | Configuration and persistence conventions |
| `api/readiness-intake.php`, `api/intake-submission-email.php` | Validation and email conventions; avoid unrelated changes |
| `css/style.css`, `js/main.js`, `js/tailwind-config.js` | Existing visual design and navigation behavior |
| `Privacy-Policy.php`, `Disclaimer.php`, `Terms-of-Service.php` | Privacy and compliance wording |
| `webinar/`, `vendor/`, `Documents/`, `uploads/intake/` | Preserve existing unrelated functionality and data |

1. Read applicable `AGENTS.md` instructions, inspect `git status`, existing diffs, PHP version, routing, Apache configuration, and dependency manifests. Preserve user changes; do not reset, overwrite or commit unrelated work.
2. Verify the configured database connection and schema before activating native booking or staff access. Do not invent credentials, availability or successful responses.
3. Use isolated Windows/XAMPP-compatible local/staging configuration. Never expose secrets from `env.php` or production customer data in output. Do not run database creation scripts against an unverified database.
4. Follow existing PHP conventions; no framework migration, dependency upgrades, or vendor edits unless essential and separately justified. Keep `/Book` and existing public links functional. Verify both root deployment and `/Phelon/` subdirectory behavior where applicable.

## 3. Required public navigation and content

Add a **Services** dropdown in the exact order below. Keep `Services.php` as the existing overview if compatible. Each entry must resolve to its own shareable, crawlable landing page. Suggested paths are relative to the application base URL; adapt to existing routing conventions rather than requiring new rewrite rules. Use a centralized allowlisted service registry as the single source of truth for slugs, titles, descriptions and valid booking selections.

| # | Service title | Suggested route / service key |
|---|---|---|
| 1 | Private Readiness & Life Planning | `private-readiness-life-planning` |
| 2 | Family & Household Stability | `family-household-stability` |
| 3 | Financial Stability | `financial-stability` |
| 4 | Business & Career Continuity | `business-career-continuity` |
| 5 | Reputation & Digital Presence | `reputation-digital-presence` |
| 6 | Federal & Prison Readiness | `federal-prison-readiness` |
| 7 | Productive-Time Planning | `productive-time-planning` |
| 8 | Release & Reentry Preparation | `release-reentry-preparation` |
| 9 | 90-Day & Ongoing Support | `90-day-ongoing-support` |

### 3.1 Page copy and content requirements

Use the descriptions below as public copy. Each page must include the indicated support areas and audience. Do not imply legal representation, guaranteed outcomes or professional advice.

#### 1. Private Readiness & Life Planning
Confidential support for individuals and families navigating serious legal disruption. We help clients organize important responsibilities, identify priorities, and create structured plans for the life circumstances surrounding their situation.
- Support: responsibility inventory; document organization; priority mapping; practical continuity plans.
- Audience: individuals and families facing serious legal disruption or major life transitions.

#### 2. Family & Household Stability
Supporting spouses, partners, children, and families through major life transitions. Our services focus on household organization, family responsibilities, communication preparation, and maintaining stability during uncertain times.
- Support: household responsibilities; family communication; caregiving arrangements; backup plans.
- Audience: spouses, partners, caregivers and families.

#### 3. Financial Stability
Helping individuals and families organize financial responsibilities, important documents, household obligations, and continuity plans during periods of disruption. This service focuses on organization and preparedness, not financial advice or credit repair.
- Support: financial document inventory; recurring obligations; household bill organization; continuity checklists.
- Audience: individuals and households managing financial responsibilities during disruption.

#### 4. Business & Career Continuity
Structured readiness support for business owners, entrepreneurs, executives, and working professionals. We help organize business responsibilities, operational continuity plans, employment transitions, and important business information.
- Support: responsibility handover; operational documentation; employment transition preparation; continuity checklists.
- Audience: business owners, entrepreneurs, executives and working professionals.

#### 5. Reputation & Digital Presence
Private support for individuals and professionals seeking to organize their digital presence, prepare for reputation-related challenges, and develop responsible personal or professional rebuilding plans.

Render two distinct sections on this same page, each with a CTA that captures its subtype:
- **Personal Reputation Readiness** (`personal-reputation-readiness`): Digital footprint organization, online profile assessment, and communication preparedness.
- **Executive Reputation & Business Continuity** (`executive-reputation-business-continuity`): Premium, customized support for physicians, executives, business owners, founders, and other professionals managing complex business and reputation-related challenges.
- Audience: individuals and professionals managing personal or business reputation concerns.
- No guarantees of content removal or reputation restoration; no deceptive or unlawful tactics.

#### 6. Federal & Prison Readiness
Non-legal preparation and life-stability support for individuals and families navigating incarceration-related transitions. Services include personal document organization, family readiness, household transition planning, and practical preparation resources.
- Support: document organization; family readiness; household transition planning; practical preparation resources.
- Audience: individuals and families preparing for incarceration-related transitions.

#### 7. Productive-Time Planning
Helping individuals establish meaningful personal development goals, educational priorities, family connection plans, and structured routines during periods of disruption.
- Support: personal development goals; educational priorities; family connection plans; structured routines.
- Audience: individuals seeking purposeful use of time during major transitions.

#### 8. Release & Reentry Preparation
Life-stability planning for individuals and families preparing for the transition back into the community. Support includes housing readiness, employment preparation, family reunification planning, personal document organization, and resource navigation.
- Support: housing readiness; employment preparation; family reunification planning; document and resource organization.
- Audience: individuals preparing for reentry and their support networks.

#### 9. 90-Day & Ongoing Support
Extended planning, accountability, and continuity support for clients who need structured assistance implementing their readiness plans over time.
- Support: implementation check-ins; priority reviews; accountability; continuity planning.
- Audience: clients requiring extended structured readiness support.

## 4. UI, accessibility and architecture

- Match existing PHELONPRENEUR branding: black, cream, gold and burgundy; official existing logo; premium, confidential presentation. Reuse existing CSS tokens and layout conventions.
- Each page: unique H1 and description, hero CTA, 3–5 support areas, audience section, closing CTA, shared footer and disclaimer. Use reusable PHP template/components rather than nine copied layouts. Reputation subtypes must remain individually selectable.
- All CTAs use the exact text **Schedule a Free 15-Minute Discovery Call**.
- Closing heading: **Start With a Private Conversation**.
- Closing copy: “Every situation is different. Schedule a complimentary 15-minute discovery call to discuss your concerns, learn about our non-legal readiness services, and explore the next appropriate step.”
- Responsive desktop/mobile menu, keyboard operation, visible focus, Escape dismissal, accessible toggle state (`aria-expanded`), sensible focus management, no hover-only controls. Avoid horizontal overflow and layout shifts.
- Unique page titles and meta descriptions; canonical URLs following existing conventions. Return 404 for invalid service routes. Escape output and never use a user-controlled file include path.
- Update existing homepage and service overview links to the matching pages. Avoid changing unrelated homepage content or webinar funnels.

## 5. Native discovery booking and staff access

Keep one shared 15-minute booking path in `Book.php`. Its local date/time picker obtains availability from `GET api/bookings.php`. `POST api/bookings.php` validates the date, time, contact fields, and service or subtype, then stores the reservation before returning a confirmation reference.

- Configure the business time zone, operating hours, and weekdays in the root `.env`. Do not book against an unverified schedule or database.
- Preserve the service and reputation subtype from the landing-page CTA through the database record. Support a general or undecided option. Do not put contact details or concern text in URLs or logs.
- Use parameterized SQL, CSRF checks, limited request sizes and rate limits. Store appointment times in UTC and enforce one reservation per available slot in the database.
- Confirmed on-screen reservations do not imply that reminder emails, external calendar sync, or payment workflows are active.
- Keep the staff-facing login UI under `Login/frontend/` and the plain PHP REST session endpoint, request validation, controller, service, and repository under `Login/backend/`. Use the root credentials and hashed staff passwords. Do not create a public registration endpoint.


## 6. Private pricing — non-negotiable

No public service prices, package pricing tables, public checkout buttons, price metadata, or automatic pricing emails in the new service pages or their discovery flow. Do not expose private pricing in HTML, JavaScript, public JSON, or service configuration delivered to clients.

Workflow: **Website service → Free 15-minute discovery call → Service recommendation → Private proposal & pricing → Client decision → Payment & onboarding.**

Reuse verified private back-office proposal/payment capabilities if present. If absent, report the gap and required configuration or follow-up implementation; do not expose an unauthenticated proposal endpoint or invent payment links. Preserve existing independent webinar/product purchase pages and payment workflows unless a separate approved requirement changes them.

## 7. Compliance copy

Include this exact statement in every service-page footer:

> The PHELONPRENEUR Network provides confidential, non-legal readiness and life-stability support. We do not provide legal advice, legal representation, financial advice, tax advice, therapy, medical advice, or guarantees of legal, financial, professional, or other outcomes.

Do not suggest legal case strategy, sentencing guidance, release-date guarantees, or guaranteed reputation removal. Final tagline where appropriate: **The attorney handles the legal case. PHELONPRENEUR helps stabilize the life around it.**

## 8. Implementation sequence

1. Inventory actual code and integrations; identify risks and baseline behavior; record a concise implementation plan in the work report.
2. Implement the centralized service registry and reusable landing-page template with nine routes.
3. Update shared navigation, homepage links, service overview, styling and accessibility.
4. Implement validated service/subtype propagation through native booking and staff sessions through the REST API.
5. Add security controls and tests for invalid input, duplicate events, failure paths and privacy.
6. Run local/staging regression tests; fix failures attributable to this change. Record blockers instead of bypassing them.
7. Produce a final changed-file inventory, test evidence, configuration checklist, rollback instructions, and release decision. Do not deploy.

## 9. Acceptance tests / definition of done

Mark each item PASS, FAIL or BLOCKED with evidence; never mark unexecuted tests PASS.

| ID | Priority | Acceptance criterion |
|---|---|---|
| A01 | P0 | Exactly nine dropdown entries in required order; each opens its correct unique page |
| A02 | P0 | Every page has correct copy, hero and closing CTA, audience, support areas and disclaimer |
| A03 | P0 | All nine CTA paths preserve service interest; both reputation subtype CTAs preserve correct subtype |
| A04 | P0 | One real configured 15-minute calendar; existing `/Book` remains usable |
| A05 | P0 | Native booking stores the validated service and executive subtype on the appointment |
| A06 | P0 | The booking reference is displayed only after the slot is stored |
| A07 | P0 | Invalid selections, missing CSRF tokens, disabled/invalid sign-ins, and duplicate slots are safely handled |
| A08 | P0 | No public service pricing, checkout CTA or automatic pricing message in service flow |
| A09 | P0 | Existing webinar, payment, intake, contact and booking flows show no attributable regressions |
| A10 | P0 | No secrets/PII leaks, live side effects or unauthorized database changes |
| A11 | P1 | Desktop and mobile layouts tested at 320, 375, 768, 1024 and 1440 px; keyboard navigation works |
| A12 | P1 | Unique titles/meta, valid links, no attributable console errors or missing assets |
| A13 | P1 | Invalid date, stale slot, and unavailable database or schedule produce safe errors |
| A14 | P0 | Private proposal/payment handoff verified or explicitly blocked pending back-office configuration |

Run PHP syntax checks on changed PHP files (`php -l <file>` or equivalent), existing automated tests, and browser/HTTP smoke tests where available. Check `git diff --check`. Use synthetic fixtures in an isolated database. Capture actual commands, results and any limitations. Verify changes do not alter unrelated vendor or generated files.

## 10. Required completion report

Create `docs/services-implementation-report.md` (or existing documentation convention) containing:
- Summary and implementation decisions, actual routes, changed files and reasons.
- Native schedule and database configuration variable names, setup instructions and remaining operational work.
- Database migration and rollback details, if applicable; file-level rollback guidance that preserves unrelated user edits.
- Acceptance matrix with PASS/FAIL/BLOCKED, evidence and remaining work.
- Local and staging verification instructions, screenshots if tooling permits, and production release checklist.
- Explicit release status: **GO for leadership review** only when all required staging checks pass; otherwise **NO-GO** with blockers. Production remains **NO-GO pending leadership approval, legal review and explicit deployment authorization**.

Implement as much as possible now using the actual repository. Do not stop at a proposed solution, fabricate evidence, or make risky assumptions to force a green result.
