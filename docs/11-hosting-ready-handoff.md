# Hosting Ready Handoff

Last reviewed: 2026-05-06

Hosting is ready for The Boudin Company Rewards project. This means the build can move from planning into cPanel setup, app scaffolding, and database import.

Do not store real hosting, database, Telnyx, email, or push credentials in this repository.

## Remaining Hosting Details To Confirm

Collect these values before first deployment:

| Item | Status | Notes |
| --- | --- | --- |
| Hosting account | Ready | HostGator/cPanel is ready. |
| Public URL | Ready | `http://theboudincompany.com/test/rewards` |
| Document root | Ready | `public_html/test/rewards` |
| PHP version | Pending | Prefer PHP 8.1+ where available. |
| HTTPS/SSL | Pending | Must be enabled before signup, login, PWA, or web push. |
| MySQL database name | Ready | `cpanel_rewards` |
| MySQL username | Ready | `cpanel_rewards_user` |
| MySQL password | Pending | `CHANGE_ME` is only a placeholder. Put the real password in `config.local.php` on the server only. |
| Non-public config path | Pending | Current scaffold uses blocked `_includes/config.local.php`; prefer a path outside `public_html` later if cPanel allows it. |
| Upload method | Pending | cPanel Git, SFTP, or File Manager. |
| Cron command path | Pending | Needed for campaign queue and automation jobs. |

## Recommended cPanel Layout

Use a public document root only for browser-accessible files:

```text
public_html/test/rewards/
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

1. Confirm `public_html/test/rewards` exists.
2. Open `http://theboudincompany.com/test/rewards` after uploading the scaffold.
3. Enable SSL and switch the final app URL to HTTPS when available.
4. Select PHP 8.1+ if available.
5. Create the MySQL database.
6. Create the MySQL user.
7. Assign the user to the database with least necessary permissions.
8. Import `database/schema-draft.sql`.
9. Import `database/seed-boudin-company.sql`.
10. Confirm `business_settings.sms.mode` is `log_only`.
11. Create `_includes/config.local.php` from `_includes/config.example.php` and enter the real password on the server only.
12. Configure a cron placeholder for queue processing.

## Local Config Template

The committed PHP template is `public_html/test/rewards/_includes/config.example.php`.

Create the real config file on hosting, not in Git:

```php
<?php
declare(strict_types=1);

return [
    'app' => [
        'environment' => 'production',
        'url' => 'http://theboudincompany.com/test/rewards',
        'timezone' => 'America/Chicago',
    ],
    'database' => [
        'host' => 'localhost',
        'database' => 'cpanel_rewards',
        'username' => 'cpanel_rewards_user',
        'password' => '[real_password_here]',
        'charset' => 'utf8mb4',
    ],
    'sms' => [
        'provider' => 'telnyx',
        'mode' => 'log_only',
        'live_send_enabled' => false,
        'telnyx_api_key' => '',
        'telnyx_messaging_profile_id' => '',
        'telnyx_sender_number' => '',
        'telnyx_10dlc_status' => 'pending',
    ],
];
```

The `.htaccess` file blocks direct browser access to `_includes`, but a config path outside `public_html` is still the stronger production option when HostGator allows it.

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
