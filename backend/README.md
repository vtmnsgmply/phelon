# Phelonpreneur Network

The repository contains three application folders. The React website is in `frontend/`, the Laravel API is in `backend/`, and the React back office is in `login/`. The old PHP source is preserved in `backend/legacy/` for reference and rollback. It is not part of the running website.

| App | URL on the combined origin | Responsibility |
|---|---|---|
| Public website | `/` | Public pages, all nine services, appointment selection, readiness intake, webinar and resource pages |
| Laravel API | `/api/` | Availability, reservations, staff sessions, intake storage, and restricted document access |
| Back office | `/login/` | Staff sign-in, upcoming appointments, intake review, authorized downloads, and printing |

## Run locally

Use PHP 8.2 or newer with PDO and Composer, plus Node compatible with Vite. Laravel 12 was selected for the PHP 8.2 runtime installed in this checkout.

From the repository root in PowerShell:

```powershell
composer --working-dir=backend install
npm.cmd --prefix frontend ci
npm.cmd --prefix login ci
npm.cmd --prefix frontend run build
npm.cmd --prefix login run build
php backend/artisan serve --host=127.0.0.1 --port=8000
```

Open `http://127.0.0.1:8000/` or `http://127.0.0.1:8000/login/`. Builds write separately to `backend/public/site/` and `backend/public/staff/`. Rebuild the public website after changing the service catalog. The staff app owns its UI, logo, and API client. It does not import website source or backend files. Staff sessions, appointment summaries, intake labels, and attachments all come from Laravel's same-origin `/api` routes. For Apache, set the dedicated virtual host DocumentRoot to the absolute `backend/public` directory, enable the included Laravel rewrite rules, and use the site at that host's root. Serving the repository directly as `/Phelon/` under the shared XAMPP document root is not the new entry point.

## REST API

Laravel registers JSON routes from `routes/api.php` and supplies the `/api` prefix. The public and staff React apps still use same-origin session cookies and CSRF protection. Laravel Sanctum is installed, but no personal access tokens are issued and staff access continues to use the existing session authorization.

List the endpoints with `php backend/artisan route:list --path=api`.

## Configure and verify the database

The existing root credentials were moved to ignored `backend/.env`. A local Laravel key was generated without replacing the database credentials. On a new checkout, copy `.env.example` to `.env` inside `backend/`, set your environment values, and generate an application key once. Back up the key before storing intake data, since answers are encrypted using it. Do not regenerate an existing key to solve connection problems.

```powershell
php backend/artisan phelon:schema
```

The configured local MySQL `phelon` database was backed up and migrated. The schema command lists only the configured schema. To see which migrations ran, use `php backend/artisan migrate:status`. Legacy purchase and intake flows were not exercised against existing customer records.

After credentials and the intended schema are confirmed:

1. Back up the database. Verify the columns and unique indexes on existing `phelon_staff_users` and `phelon_discovery_bookings` if those tables were already installed. The Laravel migration adopts those table names and adds `phelon_intakes`. It does not drop or change the legacy customer and intake tables.
2. Run `php backend/artisan migrate` for the verified target. This data-bearing migration does not support an automatic destructive down migration. Roll back the application or restore an approved backup.
3. Configure the agreed business schedule in `backend/.env` using `PHELON_BOOKING_TIMEZONE`, `PHELON_BOOKING_OPEN`, `PHELON_BOOKING_CLOSE`, and `PHELON_BOOKING_DAYS`. Open/close must be on 15-minute boundaries, and days are ISO weekday numbers such as `1,2,3,4,5`. The picker stays closed until configured. Reconcile any existing appointments to avoid overlap.
4. Provision a back-office user with `php backend/artisan phelon:staff-create person@example.com "Staff Name"`, then sign in at `/login/`. The command prompts for a strong password. Migrations do not create accounts, and there are no public sign-ups.

Database settings use `DB_DATABASE` or the earlier `DB_NAME`. File-based sessions and cache do not depend on the database. Production should use HTTPS, secure session cookies, `APP_DEBUG=false`, private storage and an explicitly set `APP_URL`. The old root `.env` was tracked in Git. The new file is ignored, but this does not remove past copies from repository history.

### Run pending migrations from the back office

After the initial setup, set `PHELON_MIGRATION_ADMINS` in the backend environment to a comma-separated list of existing staff email addresses authorized to change the schema. Sign in and open **Administration → Migrations**. The screen lists applied and pending repository files with the target database and environment. After verifying the target and backup, choose **Run pending migrations** and enter your current password. The dashboard only runs the files shipped in `backend/database/migrations/` and does not support uploads, rollbacks, or seeding. If using a cached configuration, refresh it after changing the allowlist. On multi-server installations use a shared lock-capable cache. If migrations are also run from the terminal, use `php backend/artisan migrate --isolated` so they share Laravel's migration lock.

## Migrated and gated workflows

- Public service pages use the original nine-service registry, including the personal and executive reputation options. Previous PHP page URLs redirect to the React routes. Retired callback paths return 410.
- The public homepage carries the original business introduction, readiness pillars, audiences, family impact, process, program options, and confidentiality commitments. Booking includes fit, preparation, and confirmation details. Staff screens are available separately at `/login/`.
- Laravel confirms discovery bookings after storage, prevents duplicate time slots, and displays the confirmation reference. No automatic calendar invitations, reminder emails, or self-service cancellation were added.
- The React intake reuses the prior form’s question groups and conditionals. It allows up to four private PDF or image attachments. Staff can review, download, and print submitted intake. Enable `PHELON_LEGACY_INTAKE_ENABLED` only after testing those older links against an authorized non-production copy of the legacy schema.
- The webinar starter-kit checkout retains its independently hosted checkout. The old dated workshop and premium checkout routes remain but require approved checkout URLs before sending buyers onward. Registration points to Contact. No checkout transaction was made.
- Paid PDFs are outside the public directory. Enable `PHELON_LIBRARY_ENABLED` only after validating the legacy `customers` schema and access-code rules. For reissue, configure an approved SMTP account using Laravel's `MAIL_*` settings. No SMTP was contacted during the migration.

## Controller convention

Each controller action should be one line, with an upper limit of two executable body lines. Return a single call to a service or Form Request. Keep validation in `app/Http/Requests/`, workflow logic in `app/Services/`, and database operations in repositories. The `ThinControllersTest` enforces the controller convention.

## Reusable reads

`BookingRepository::findBookings()` and `ReadinessIntakeRepository::findIntakes()` always return a collection. Pass typed criteria for an exact ID or reference, combine filters, or omit the criteria to read the first page. Call `first()` for a unique lookup and have the service decide how to handle a missing record. Use `ReadPage` to choose the page and size. The default is 50 records, and the maximum is 100. Booking results are sorted by start time, and intake results are newest first. Intake lists omit encrypted answers and attachment paths unless the service explicitly requests details.

```php
$booking = $bookings->findBookings(new BookingCriteria(id: $id))->first();
$upcoming = $bookings->findBookings(
    new BookingCriteria(service: $serviceKey, startsFrom: now('UTC')),
    new ReadPage(number: 2, size: 25),
);
```

## Repeat verification

```powershell
php backend/artisan test
node backend/tools/smoke.mjs
```

The feature tests use in-memory SQLite. The smoke command uses a separate temporary SQLite file, synthetic records and a loopback server. Set `PHELON_CHROME` to an installed Chromium executable to also check the rendered screens. Neither suite reads project customer data or reaches a checkout provider.

No production deployment was performed.
