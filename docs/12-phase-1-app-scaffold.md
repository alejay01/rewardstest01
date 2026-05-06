# Phase 1 App Scaffold

Last reviewed: 2026-05-06

The first coding phase is a PHP/PWA scaffold for the HostGator/cPanel test path. It now includes a safe MySQL config pattern and connection status check, but forms still do not write customer records yet.

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
| `public_html/test/rewards/join.php` | Customer signup and SMS consent preview. |
| `public_html/test/rewards/wallet.php` | Mock customer wallet and coupon view. |
| `public_html/test/rewards/redeem.php` | Staff QR/short-code redemption preview. |
| `public_html/test/rewards/qrcode.php` | Signup QR generator for source-tagged links. |
| `public_html/test/rewards/admin.php` | Admin launch-gate preview. |
| `public_html/test/rewards/_includes/bootstrap.php` | Shared app settings, helpers, header, and footer. |
| `public_html/test/rewards/_includes/config.example.php` | Safe committed config template with placeholder password. |
| `public_html/test/rewards/_includes/config.local.php` | Real server-only config file. Do not commit this file. |
| `public_html/test/rewards/_includes/database.php` | PDO connection helper and database status check. |
| `public_html/test/rewards/assets/css/app.css` | Responsive app styling. |
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

The signup form and admin quick-add form can now save customer records and optional SMS consent events. SMS remains log-only and no text message is sent.

The later database layer should wire these screens to:

- `customers`
- `customer_consents`
- `signup_sources`
- `visits`
- `coupons`
- `customer_coupons`
- `coupon_redemptions`
- `notification_deliveries`
- `audit_logs`
- `business_settings`

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
4. Open `wallet.php` and confirm mock coupon display.
5. Open `redeem.php` and submit a mock short code such as `BC100PT`.
6. Open `admin.php` and confirm live SMS is shown as blocked.
7. Enable SSL when ready; service worker installation generally requires HTTPS outside localhost.
