# Staff back office: API the backend needs to provide

For the backend developer. The frontend is built against this contract; none of the endpoints marked **new** exist yet.

## Security rules (required)

The app hides screens a person may not use. **That is not security.** The server must:

1. Check the role and permission on every request below, including the existing `/api/staff/*` routes.
2. Return `401` when signed out and `403` when signed in without the permission.
3. Keep CSRF protection on every write, and rate-limit password endpoints.
4. Require the current password to change your own password. Hash passwords; never return them.
5. Refuse to deactivate or demote your own account, or the last active administrator.
6. End a person's other sessions when their password is reset or the account is deactivated.
7. Record who changed accounts, permissions, schedule and holidays.

## Conventions

- JSON, same origin, session cookie, `X-CSRF-TOKEN` from `GET /api/context`.
- Errors: `{ "message": "...", "errors": { "field": ["..."] } }`. The first field error is shown to the user.
- Successful writes return a JSON body (for example `{ "saved": true }`).
- Until an endpoint exists, `404` is expected: the app reports it as "not connected".

## Roles and permissions

| Role | Access |
|---|---|
| `admin` | Everything |
| `staff` | Only the permissions granted, plus their own account |

| Permission | Allows | Existing routes it should guard |
|---|---|---|
| `clients.view` | Client list and contact details | new |
| `clients.manage` | Add and edit clients (needs `clients.view`) | new |
| `contracts.view` | Contract records | new |
| `contracts.manage` | Add and edit contracts (needs `contracts.view`) | new |
| `email.drafts` | Own email drafts | new |
| `services.manage` | Change service descriptions | new |
| `finance.view` | Payment records | new |
| `bookings.view` | Upcoming bookings | `GET /api/staff/bookings` |
| `intakes.view` | Intake list and answers | `GET /api/staff/intakes`, `GET /api/staff/intakes/{id}` |
| `intakes.files` | Attachment download (needs `intakes.view`) | `GET /api/staff/intakes/{id}/files/{file}` |
| `schedule.manage` | Read and change the schedule | new |
| `holidays.manage` | Read and change holidays | new |

Account management is administrator only. The database needs a role and a permissions column on `phelon_staff_users`.

## Session (change)

`GET` and `POST /api/session` should return:

```json
{ "user": { "id": 1, "displayName": "Jordan Lee", "email": "jordan@example.com", "role": "admin", "permissions": [] },
  "csrfToken": "..." }
```

`permissions` is ignored for administrators. If `role` is missing, the app treats names in `VITE_ADMIN_NAMES` as admin (temporary) and everyone else as staff with `email.drafts`, `clients.view`, `bookings.view`, `intakes.view` and `intakes.files`.

## Own account (new)

| Request | Body |
|---|---|
| `PUT /api/account` | `{ "displayName": "...", "email": "..." }` |
| `PUT /api/account/password` | `{ "currentPassword": "...", "newPassword": "..." }` |
| `POST /api/account/avatar` | Multipart field `avatar`. Returns `{ "avatarUrl": "..." }` |
| `DELETE /api/account/avatar` | Removes the photo |

The app sends a 256 by 256 JPEG, but the server must not rely on that: check the real file type and size, re-encode the image, store it under a random name outside executable paths, and serve it with `X-Content-Type-Options: nosniff`. Include `avatarUrl` in the session user.

Password rule, matching `phelon:staff-create`: 12 to 72 bytes, no null characters.

## Accounts (new, administrator only)

| Request | Body or response |
|---|---|
| `GET /api/admin/accounts` | `{ "accounts": [ { "id", "displayName", "email", "role", "permissions", "active" } ] }` |
| `POST /api/admin/accounts` | `{ "displayName", "email", "role", "permissions", "active", "password" }` |
| `PUT /api/admin/accounts/{id}` | Same fields without `password` |
| `PUT /api/admin/accounts/{id}/password` | `{ "password": "..." }` |

Editing an account and resetting its password are two requests. If the second fails, the first has already been applied.

## Clients (new)

| Request | Permission | Body or response |
|---|---|---|
| `GET /api/admin/clients` | `clients.view` | `{ "clients": [ { "id", "name", "email", "phone", "status" } ] }` |
| `POST /api/admin/clients` | `clients.manage` | `{ "name", "email", "phone", "status" }` |
| `PUT /api/admin/clients/{id}` | `clients.manage` | Same fields |

`status` is `new`, `active` or `closed`. Name and email are required; email is unique. The app links a client to upcoming bookings by email. Decide whether a new booking creates a client automatically.

Until this exists, the app builds the list from `GET /api/staff/bookings`.

## Settings (new, administrator only)

`GET /api/admin/settings` returns `{ "settings": ... }`. `PUT` sends the same object.

```json
{ "settings": {
  "organizationName": "...", "contactEmail": "...", "responseTime": "...",
  "notifyEmail": "", "notifyBooking": false, "notifyIntake": false,
  "checkout": { "47": "", "497": "", "3497": "" },
  "resourceLibrary": false, "legacyIntake": false
} }
```

- Checkout links are empty or `https://link.fastpaydirect.com/payment-link/<letters and digits>`. Validate on the server: these links take money.
- `checkout`, `resourceLibrary` and `legacyIntake` currently come from the environment (`config/phelon.php`). The server must read the saved values instead.
- Organization details and notifications are not used anywhere yet. Notification emails must never contain intake answers.
- Never expose secrets (mail or database passwords, keys) through this endpoint.

## Migrations (implemented, migration administrators only)

`GET /api/admin/migrations` returns `{ "database": "phelon", "environment": "local", "hasHistory": true, "migrations": [ { "name": "...", "batch": 1, "status": "ran" } ] }`. The status is `ran` or `pending`, and `batch` is `null` when pending.

`POST /api/admin/migrations/run` accepts `{ "password": "..." }` and returns refreshed status plus `applied`, the list of migration names applied. Both endpoints require an active staff session for an account in the server's `PHELON_MIGRATION_ADMINS` list. Session responses expose `user.canManageMigrations` for navigation. The run endpoint also requires CSRF and the current password, is rate-limited, and runs only pending files in `backend/database/migrations/` on the configured connection. It never accepts migration files, paths, SQL or commands from the browser and does not support rollback or seeding. A concurrent run returns 409. Run attempts and outcomes are recorded in the Laravel logs, not returned as console output.

## Calendar

The Calendar screen reads bookings, holidays and the schedule. Allow `GET /api/admin/holidays` and `GET /api/admin/schedule` for anyone with `bookings.view`; changing them still needs the manage permission.

## Contracts (new)

| Request | Permission | Body or response |
|---|---|---|
| `GET /api/admin/contracts` | `contracts.view` | `{ "contracts": [ { "id", "title", "clientName", "clientEmail", "serviceId", "amount", "status", "startsOn" } ] }` |
| `POST /api/admin/contracts`, `PUT /api/admin/contracts/{id}` | `contracts.manage` | Same fields without `id` |

`status`: `draft`, `sent`, `signed` or `ended`. `amount` is US dollars or `null`. `startsOn` is `YYYY-MM-DD` or empty. No files or signatures are handled.

## Email drafts (new, `email.drafts`)

`GET /api/admin/email-drafts` returns `{ "drafts": [ { "id", "to", "subject", "body" } ] }`. `POST` and `PUT /{id}` take `{ "to", "subject", "body" }` and return `{ "draft": { ... } }`; `DELETE /{id}` removes one. Each person sees only their own drafts. The app never sends email.

## Services (new)

`GET /api/admin/services` (any signed-in user) returns `{ "services": [ { "id", "title", "description", "audience", "support": [], "options": [], "price": null } ] }`. `PUT /api/admin/services/{id}` (`services.manage`) takes `title`, `description`, `audience`, `support`, `price`. `price` is US dollars from 0 to 1,000,000 with up to two decimals, or `null`. Prices are not shown on the public website.

The public website is built from `backend/resources/catalog/services.json`. Saved changes only reach visitors if that file, or the website, is updated to use them.

## Audit logs (new, administrator only, read only)

`GET /api/admin/audit-logs` returns the newest entries first: `{ "entries": [ { "id", "at", "actor", "category", "action", "target", "ip" } ] }`.

- `at` is ISO 8601 with a time zone, for example `2026-11-28T15:04:00Z`.
- `category`: `sign-in`, `account`, `booking`, `client`, `settings` or `data`.
- Record sign-ins (including failures), account and permission changes, password resets, settings changes, and every time an intake or attachment is opened.
- Never record passwords, tokens or intake answers. The application must not be able to edit or delete entries.
- The app filters and exports only what this returns. Add paging before the list grows large.

## Finance (new, `finance.view`, read only)

`GET /api/admin/finance/transactions` returns `{ "transactions": [ { "id", "reference", "clientName", "clientEmail", "description", "amount", "status", "method", "createdAt" } ] }`.

- `amount` is US dollars with up to two decimals. `status`: `paid`, `pending`, `failed` or `refunded`. `createdAt` is ISO 8601 with a time zone.
- Take status from the payment provider (for example verified webhooks), never from the browser.
- Never return or store card numbers, security codes or bank details. `method` is a general label such as `Card`.
- The app derives all three screens and their totals from this one list. Add paging and server-side totals before it grows large.
- The existing `customers` table holds some purchase records but not these statuses.

## Schedule (new, `schedule.manage`)

`GET /api/admin/schedule` returns `{ "schedule": ... }`. `PUT` sends the same object.

```json
{ "schedule": {
  "timezone": "America/New_York",
  "slotMinutes": 15,
  "noticeHours": 1,
  "windowDays": 45,
  "days": {
    "mon": { "open": true, "from": "09:00", "to": "17:00", "breakFrom": "", "breakTo": "" },
    "tue": {}, "wed": {}, "thu": {}, "fri": {}, "sat": {}, "sun": {}
  } } }
```

- `timezone` must equal the app's `VITE_BOOKING_TIMEZONE` and the server's `PHELON_BOOKING_TIMEZONE`. The app shows it read-only.
- Times are `HH:MM` on a quarter hour, in `timezone`. `to` is later than `from`.
- A break is optional; both ends or neither, inside opening hours.
- `slotMinutes`: 15, 30, 45 or 60. `noticeHours`: 1 to 72. `windowDays`: 1 to 365. At least one day open.

**Today's booking code supports less than this.** `BookingSchedule` reads one opening and closing time for all days from the environment, with a fixed 15-minute length, 1-hour notice and 45-day window, and `ends_at_utc` assumes 15 minutes. It must read these settings, handle breaks, and prevent overlapping bookings when lengths change.

## Holidays (new, `holidays.manage`)

`GET /api/admin/holidays` returns `{ "holidays": ... }`. `PUT` sends the same object.

```json
{ "holidays": {
  "federal": ["new-year", "christmas"],
  "observances": ["christmas-eve"],
  "custom": [
    { "id": "uuid", "name": "Team day", "date": "2027-03-12", "allDay": true },
    { "id": "uuid", "name": "Short day", "date": "2027-03-19", "allDay": false, "open": "09:00", "close": "12:00" }
  ] } }
```

Selected federal holidays and observances repeat every year. The server must calculate their dates and **apply all closures to availability and to booking validation**. Use `src/lib/holidays.js` as the reference:

| ID | Rule |
|---|---|
| `new-year`, `juneteenth`, `independence`, `veterans`, `christmas` | 1 Jan, 19 Jun, 4 Jul, 11 Nov, 25 Dec. Saturday is observed Friday; Sunday is observed Monday |
| `mlk`, `washington` | Third Monday of January, of February |
| `memorial` | Last Monday of May |
| `labor`, `columbus` | First Monday of September, second Monday of October |
| `thanksgiving` | Fourth Thursday of November |
| `good-friday`, `thanksgiving-friday`, `christmas-eve`, `new-years-eve` | Two days before Easter Sunday; day after Thanksgiving; 24 Dec; 31 Dec. No observed-date shift |

When 1 January is a Saturday, it is observed on 31 December of the year before. A custom entry overrides a holiday on the same date.
