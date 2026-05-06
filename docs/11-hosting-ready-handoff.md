# Hosting Ready Handoff

Last reviewed: 2026-05-06

Hosting is ready for The Boudin Company Rewards project. This means the build can move from planning into cPanel setup, app scaffolding, and database import.

Do not store real hosting, database, Telnyx, email, or push credentials in this repository.

## Remaining Hosting Details To Confirm

Collect these values before first deployment:

| Item | Status | Notes |
| --- | --- | --- |
| Hosting account | Ready | HostGator/cPanel is ready. |
| Public domain/subdomain | Pending | Example: `rewards.[domain]` or `[domain]/rewards`. |
| Document root | Pending | Needed for file upload and routing. |
| PHP version | Pending | Prefer PHP 8.1+ where available. |
| HTTPS/SSL | Pending | Must be enabled before signup, login, PWA, or web push. |
| MySQL database name | Pending | Keep out of Git. |
| MySQL username | Pending | Keep out of Git. |
| MySQL password | Pending | Keep out of Git. |
| Non-public config path | Pending | Prefer a path outside `public_html`. |
| Upload method | Pending | cPanel Git, SFTP, or File Manager. |
| Cron command path | Pending | Needed for campaign queue and automation jobs. |

## Recommended cPanel Layout

Use a public document root only for browser-accessible files:

```text
public_html/rewards/
  index.php
  assets/
  manifest.json
  service-worker.js
  uploads/limited-public-assets/
```

Keep application code and config outside the public web root when cPanel allows it:

```text
rewards_app/
  app/
  admin/
  api/
  cron/
  config/
  storage/
```

If the host only allows everything under `public_html`, add deny rules for config, logs, and app internals before storing secrets.

## First cPanel Actions

1. Create the rewards subdomain or folder.
2. Enable SSL.
3. Select PHP 8.1+ if available.
4. Create the MySQL database.
5. Create the MySQL user.
6. Assign the user to the database with least necessary permissions.
7. Import `database/schema-draft.sql`.
8. Import `database/seed-boudin-company.sql`.
9. Confirm `business_settings.sms.mode` is `log_only`.
10. Create a non-public config file for database credentials.
11. Configure a cron placeholder for queue processing.

## Local Config Template

Create the real config file on hosting, not in Git:

```text
APP_ENV=production
APP_URL=https://[domain-or-subdomain]
APP_TIMEZONE=America/Chicago

DB_HOST=localhost
DB_NAME=[cpanel_database_name]
DB_USER=[cpanel_database_user]
DB_PASS=[cpanel_database_password]

SMS_PROVIDER=telnyx
SMS_MODE=log_only
SMS_LIVE_SEND_ENABLED=0

TELNYX_API_KEY=
TELNYX_MESSAGING_PROFILE_ID=
TELNYX_SENDER_NUMBER=
TELNYX_10DLC_STATUS=pending
```

## What Can Start Now

- PHP app scaffold.
- Database connection layer.
- Admin login.
- Customer signup page.
- QR source routing.
- Consent capture.
- Coupon generation.
- Staff QR and short-code redemption.
- Log-only SMS message queue.

## Still Deferred

These are not blockers for the app build, but they are blockers for public/live SMS:

- Telnyx API key.
- Telnyx Messaging Profile.
- Sender number.
- 10DLC/business texting registration.
- Published privacy policy URL.
- Published rewards terms URL.
- Owner/legal approval of SMS opt-in language.
