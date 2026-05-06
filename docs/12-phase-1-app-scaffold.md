# Phase 1 App Scaffold

Last reviewed: 2026-05-06

The first coding phase is a PHP/PWA scaffold for the HostGator/cPanel test path. It now includes a safe MySQL config pattern, connection status check, customer signup, wallet lookup, and staff-confirmed visit crediting.

Upload target:

```text
public_html/test/rewards
```

Public test URL:

```text
https://theboudincompany.com/test/rewards
```

## Current Files

| Path | Purpose |
| --- | --- |
| `public_html/test/rewards/index.php` | Status page and route index. |
| `public_html/test/rewards/join.php` | Customer signup and optional SMS consent capture. |
| `public_html/test/rewards/wallet.php` | Customer wallet lookup with real points and recent point activity. |
| `public_html/test/rewards/redeem.php` | Staff QR/short-code redemption preview. |
| `public_html/test/rewards/qrcode.php` | Signup QR generator for source-tagged links. |
| `public_html/test/rewards/admin.php` | Admin launch gates, manual customer add, QR link, and visit point crediting. |
| `public_html/test/rewards/_includes/rewards.php` | Reward/customer helper functions for signup, lookup, and visit crediting. |
| `public_html/test/rewards/_includes/bootstrap.php` | Shared app settings, helpers, header, and footer. |
| `public_html/test/rewards/_includes/config.example.php` | Safe committed config template with placeholder password. |
| `public_html/test/rewards/_includes/config.local.php` | Real server-only config file. Do not commit this file. |
| `public_html/test/rewards/_includes/database.php` | PDO connection helper and database status check. |
| `public_html/test/rewards/assets/css/app.css` | Responsive app styling. |
| `public_html/test/rewards/assets/css/phase2.css` | Phase 2 customer/QR styling. |
| `public_html/test/rewards/assets/js/app.js` | PWA registration and coupon-code input cleanup. |
| `public_html/test/rewards/assets/img/boudin-logo-icon.svg` | Icon-only restaurant logo. |
| `public_html/test/rewards/assets/img/boudin-logo-wordmark.svg` | Restaurant logo with wordmark. |
| `public_html/test/rewards/manifest.json` | PWA manifest. |
| `public_html/test/rewards/service-worker.js` | Basic app shell cache. |
| `public_html/test/rewards/.htaccess` | Directory listing disabled and sensitive-file blocking. |

## Database Status Layer

This phase includes a PDO helper that checks whether `_includes/config.local.php` exists and whether the MySQL connection works.

The committed example uses:

- Host: `localhost`
- Database: `cpanel_rewards`
- User: `cpanel_rewards_user`
- Password: `CHANGE_ME`

The real password belongs only in `_includes/config.local.php` on the server. The app will not try to connect with the placeholder password.

The signup form and admin quick-add form can now save customer records and optional SMS consent events. Admin can also credit staff-confirmed visit points. SMS remains log-only and no text message is sent.

The current database layer uses:

- `customers`
- `customer_consents`
- `signup_sources`
- `visits`
- `reward_rules`
- `points_ledger`

Later phases should wire these screens to coupons, coupon redemptions, notification deliveries, audit logs, and business settings.

## SQL Import

Use `database/schema-draft.sql` first, then `database/seed-boudin-company.sql`.

The schema file is ordered for MySQL import and includes reverse-order table drops for a fresh setup. Do not run it over live customer data later unless you intentionally want to rebuild the tables.

## Safe SMS Behavior

SMS remains in `log_only` mode.

No real Telnyx request is made. No real text message is sent.

## Test Checklist

After upload:

1. Open `https://theboudincompany.com/test/rewards`.
2. Confirm the status page loads.
3. Open `join.php` and submit a test signup preview.
4. Open `wallet.php`, enter the same phone number, and confirm the customer wallet loads.
5. Open `admin.php`, credit a visit for that phone number, then reopen the wallet and confirm points increased.
6. Open `redeem.php` and submit a mock short code such as `BC100PT`.
7. Confirm live SMS is shown as blocked.
