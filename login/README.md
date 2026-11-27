# Staff back office

React app for staff and administrators. It is served by Laravel at `/login/`.

## Preview it (no backend needed)

```bash
cd login
npm install
npm run dev
```

Open <http://127.0.0.1:5174/staff/?preview> and choose **Preview as admin** or **Preview as staff**.

Preview mode uses sample data held in memory. It checks no passwords, calls no server and resets on reload. It exists only in development: production builds leave it out.

Without `?preview` the app talks to the real server through `/api`.

## Alerts

Confirmations use SweetAlert2, configured once in `src/lib/alerts.js`. Call `confirmAction({ title, text, confirmText })`; it resolves `true` or `false`.

## Time zone

Set `VITE_BOOKING_TIMEZONE` in `login/.env` (default `America/New_York`, US Eastern). The calendar, bookings and schedule settings use it. Restart or rebuild after changing it, and keep it equal to the backend's `PHELON_BOOKING_TIMEZONE`.

## Build

```bash
npm run build      # writes to ../backend/public/staff/ and empties that folder first
```

**This redesign has not been built into `backend/` yet.** `/login/` keeps showing the old version until you run the command above.

## What works with today's server

| Screen | Status |
|---|---|
| Sign in and sign out | Works |
| Bookings, intakes, attachments | Works |
| Overview | Works, except "Next closure" (administrators only) |
| Clients | Lists people from upcoming bookings. Full records, adding and editing **need backend** |
| Contracts | Screen ready. **Needs backend** |
| Email drafts | Writing, copying and "Open in email app" work. Saving drafts **needs backend** |
| Services | Lists the website's services. Saving changes **needs backend** |
| Calendar (Month view, Bookings, Schedule settings, Holidays) | Month view shows upcoming bookings. Holidays and days off **need backend** |
| Settings | Screen ready. **Needs backend** |
| Migrations | Authorized accounts can view and run pending repository migrations after confirming their password |
| My account: photo | Choosing and previewing works. Saving **needs backend** |
| My account: profile and password | Screen ready. **Needs backend** |
| Finance (Transactions, Payments, Pending) | Screens ready, with filters, totals and CSV export. **Needs backend** |
| Audit logs | Screen ready, with filters and CSV export. **Needs backend** |
| Accounts | Screen ready. **Needs backend** |
| Schedule | Screen ready. **Needs backend** |
| Holidays | Screen ready. Dates are calculated in the app. **Saving needs backend** |

Where the server has no endpoint, the screen says so and saving is turned off or reports that nothing changed.

**Today the server sends no general role.** As a temporary stand-in, display names listed in `VITE_ADMIN_NAMES` (`login/.env`) are shown as Admin; everyone else is staff. That public client setting does not grant server permissions. Migrations uses the separate server-provided `canManageMigrations` capability and ignores the client admin list.

## Structure

```
src/
  api/         client (CSRF, errors), server (endpoints), preview (development only)
  auth/        permissions and user normalising
  components/  Button, Card, Dialog, Field, PasswordField, Switch, SaveBar, ResourceState, ...
  layout/      sidebar shell and navigation list
  pages/       one screen each; team/ and holidays/ have sub-parts
  hooks/       data loading, settings forms, unsaved-change guard, routing
  lib/         US holidays, schedule rules, password rules, dates
```

To add a screen: create it in `pages/`, add one line to `layout/navigation.js` (with the permission it needs) and register it in `App.jsx`.

See [BACKEND-CONTRACT.md](BACKEND-CONTRACT.md) for the API the backend must provide.
