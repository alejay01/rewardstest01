# Phase 1 App Scaffold

Last reviewed: 2026-05-06

The first coding phase is a database-free PHP/PWA scaffold for the HostGator/cPanel test path.

Upload target:

```text
public_html/test/rewards
```

Public test URL:

```text
http://theboudincompany.com/test/rewards
```

## Current Files

| Path | Purpose |
| --- | --- |
| `public_html/test/rewards/index.php` | Status page and route index. |
| `public_html/test/rewards/join.php` | Customer signup and SMS consent preview. |
| `public_html/test/rewards/wallet.php` | Mock customer wallet and coupon view. |
| `public_html/test/rewards/redeem.php` | Staff QR/short-code redemption preview. |
| `public_html/test/rewards/admin.php` | Admin launch-gate preview. |
| `public_html/test/rewards/_includes/bootstrap.php` | Shared app settings, helpers, header, and footer. |
| `public_html/test/rewards/assets/css/app.css` | Responsive app styling. |
| `public_html/test/rewards/assets/js/app.js` | PWA registration and coupon-code input cleanup. |
| `public_html/test/rewards/assets/img/boudin-rewards-mark.svg` | App mark/icon. |
| `public_html/test/rewards/manifest.json` | PWA manifest. |
| `public_html/test/rewards/service-worker.js` | Basic app shell cache. |
| `public_html/test/rewards/.htaccess` | Directory listing disabled and sensitive-file blocking. |

## No Database Code Yet

This phase intentionally does not include PHP database code. Forms preview the shape of the data that will later be saved, but they do not persist customer records, consent events, coupons, or redemptions.

The later database layer should wire these screens to:

- `customers`
- `customer_consents`
- `signup_sources`
- `coupons`
- `customer_coupons`
- `coupon_redemptions`
- `notification_deliveries`
- `audit_logs`
- `business_settings`

## Safe SMS Behavior

SMS remains in `log_only` mode.

No real Telnyx request is made. No real text message is sent.

## Test Checklist

After upload:

1. Open `http://theboudincompany.com/test/rewards`.
2. Confirm the status page loads.
3. Open `join.php` and submit a test signup preview.
4. Open `wallet.php` and confirm mock coupon display.
5. Open `redeem.php` and submit a mock short code such as `BC100PT`.
6. Open `admin.php` and confirm live SMS is shown as blocked.
7. Enable SSL when ready; service worker installation generally requires HTTPS outside localhost.
