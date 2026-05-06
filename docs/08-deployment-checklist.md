# Deployment Checklist

Last reviewed: 2026-05-06

## cPanel Setup

- Create subdomain or path, for example `rewards.example.com`.
- Enable SSL.
- Select PHP 8.1+ where available.
- Create MySQL database.
- Create MySQL user with least necessary privileges.
- Store database credentials outside public web root when possible.
- Set file permissions conservatively.
- Configure cron jobs for queued sends and automation.

## Database Setup

- Import schema.
- Create initial business record.
- Create first location.
- Create owner/admin account.
- Create default reward settings.
- Create default coupon templates.
- Create consent policy version.

## Provider Setup

### SMS

- Register or confirm Telelinux provider account.
- Complete Brand and Campaign/10DLC registration.
- Purchase or assign sending number.
- Configure inbound webhook URL.
- Configure delivery receipt webhook URL.
- Test STOP and HELP.
- Test send to internal phones only before public launch.

### Email

- Verify sending domain.
- Set SPF/DKIM/DMARC as provider recommends.
- Create unsubscribe/suppression process.
- Test transactional and marketing messages separately.

### Push

- Create OneSignal or Firebase project.
- Add app/site configuration.
- Add service worker integration.
- Test desktop, Android, and iOS home-screen behavior.

## QR And Kiosk Setup

- Generate QR per location.
- Add source code to each QR URL.
- Print table/counter/window signs.
- Test QR on iPhone and Android.
- Test kiosk reset after signup.
- Lock kiosk device to signup page.

## Security Checklist

- Force HTTPS.
- Use secure and HttpOnly cookies.
- Hash passwords with PHP password_hash.
- Add CSRF tokens to admin forms.
- Validate and rate-limit public forms.
- Do not expose provider API keys in JavaScript.
- Log admin actions.
- Back up database daily.
- Test restore process.

## Launch Checklist

- Legal language approved.
- Staff trained.
- Manager knows how to void/redeem coupons.
- First campaign limited to test audience.
- Opt-out tested.
- QR redemption scanner tested.
- Manual short-code redemption tested.
- Printed signs match signup URL.
- Privacy/terms pages live.
- Backup confirmed.

## Replication Checklist For A New Location

- Add location record.
- Generate location QR codes.
- Assign manager/staff users.
- Set location timezone if different.
- Configure eligible coupons.
- Print signage.
- Test redemption at that location.
