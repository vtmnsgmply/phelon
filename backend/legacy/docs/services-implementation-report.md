# PHELONPRENEUR Native Booking and Staff Login

**November 28, 2026. Source changes are local. The configured database is not ready for activation.**

## What changed

- `Book.php` uses a reusable first-party date/time picker. It preserves the service and either reputation subtype, collects contact details, displays times in the visitor's selected time zone, and shows a reference only after the 15-minute reservation is stored.
- `Login/` uses Phelon’s logo and black, cream, gold, and burgundy styling. The frontend is separate from the plain PHP REST session API. The backend has a controller, request validator, service, repository, and CLI-only account provisioning. It uses password hashes, cookie sessions, CSRF checks, request throttling, and session rotation.
- Removed the former online calendar and registration embeds, scripts, links, callback implementations and setup guide. Apache returns `410 Gone` for the retired callback routes. Webinar registration now points to Contact, and the former webinar consultation/application links lead to native discovery booking. Independent checkout pages were left intact. End-to-end payment fulfillment was not tested and should be verified separately before release.
- No historical tables, bookings or customer data were migrated or deleted. Existing appointments are not imported or blocked on the native calendar. Before turning on native availability, reconcile any outstanding appointments with the schedule to prevent overlap.
- This change does not send confirmation or reminder emails, sync external calendars, arrange meeting links, or provide self-service cancellation/rescheduling. The customer sees an on-screen confirmation after storage. Confirm the operational handoff and invitation process before making availability public.

## Database status

The local MariaDB server responded to the credentials from the root `.env` but rejected authentication with driver error **1045**. The existing schema could not be listed. The repository has source references to existing customer and intake tables, but those references do not establish what is currently in the database.

Run the read-only schema inspection again after fixing or confirming the root credentials:

    php scripts/inspect-native-schema.php

It reads column metadata from the configured schema and does not select customer rows. It does not print connection credentials. No migration was run on that database.

## Local or staging setup

1. Verify the target database and its existing tables. Apply `database/native-booking-and-login.sql` to the verified target. It creates three tables only. `phelon_staff_users` holds staff password hashes, `phelon_discovery_bookings` holds reservations with unique UTC start times, and `phelon_rate_limits` holds short-lived request counters. It does not modify existing customer/intake tables.
2. Keep using the existing root `.env` for `DB_HOST`, `DB_NAME`, `DB_USERNAME`, and `DB_PASSWORD`. Set `DB_PORT` if it is not 3306. The app also accepts process-level overrides for isolated environments. Do not put credentials in frontend code or URLs.
3. Add the approved schedule to the root `.env`. The example below is illustrative only and is not installed by default. The picker fails closed until a valid schedule is configured. Days use ISO weekdays, where Monday is 1. Times must be on 15-minute boundaries. Booking opens 45 days ahead and requires at least one hour of notice.

       PHELON_BOOKING_TIMEZONE=America/New_York
       PHELON_BOOKING_OPEN=09:00
       PHELON_BOOKING_CLOSE=17:00
       PHELON_BOOKING_DAYS=1,2,3,4,5

4. Provision an authorized staff user after the migration. This PowerShell example passes the password through stdin rather than placing it in command arguments. Use a strong, unique 12–72 byte password. There are no default credentials or public account signup.

       $phelonStaffSecret = Read-Host 'Staff password' -AsSecureString
       $phelonStaffPointer = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($phelonStaffSecret)
       try {
           [Runtime.InteropServices.Marshal]::PtrToStringBSTR($phelonStaffPointer) |
               php Login/backend/bin/create-user.php 'person@example.test' 'Staff Name'
       } finally {
           [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($phelonStaffPointer)
       }

5. Serve through Apache. HTTPS enables secure cookies automatically. Set `PHELON_SECURE_COOKIES=1` when TLS is terminated before PHP and PHP cannot detect it. PHP must have a writable private session directory. Leave the checkout database credentials and internal scripts outside public access. Apache access rules were checked using HEAD requests that did not retrieve file contents.

## Routes and verification

| Route | Behavior |
|---|---|
| `Book.php?service=financial-stability#booking` | Prefills the selected service in the site's date/time picker. |
| `api/bookings.php` | GET returns availability; POST validates and stores a reservation with CSRF protection. |
| `Login/` | The branded staff sign-in page. |
| `Login/backend/api/session.php` | GET checks session; POST signs in; DELETE signs out. |

- `node tests/native-integration.mjs` started an isolated MariaDB instance, applied the SQL, and created synthetic staff and booking records. Verified sign-in, sign-out, CSRF rejection, session rotation, 15-minute availability, service subtype persistence, and duplicate-slot rejection.
- `node tests/services-smoke.mjs` verified the nine routes and eleven booking selections, legacy GET pages, invalid inputs, the new login route, and retired endpoint behavior at root and under `/Phelon/`.
- `node tests/services-browser.mjs` with `PHELON_CHROME` set checked navigation, the booking picker and the login page at 320, 375, 768, 1024 and 1440 px. Browser test fixtures do not connect to external services.
- On the local Apache instance, Book and Login returned 200, the retired webhook returned 410, and `.env`, internal PHP classes, and the SQL migration returned 403.

Nothing was published. The root `.env` was not changed. The configured database must pass authentication and schema inspection before migration or account provisioning on that target.
