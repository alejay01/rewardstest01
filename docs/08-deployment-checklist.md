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

## Legal Page Setup

- Review and approve `docs/legal/sms-opt-in-language.md`.
- Review and approve `docs/legal/privacy-policy-template.md`.
- Review and approve `docs/legal/rewards-terms-template.md`.
- Publish privacy policy and rewards terms at public URLs.
- Add privacy policy and terms links beside the SMS opt-in checkbox.
- Save screenshots of the signup page, QR flow, and kiosk flow for Telnyx/business texting registration.
- Store policy and terms version as `boudin-rewards-2026-05-06-v1` unless the published copy changes.

## Provider Setup

### SMS

- Register or confirm Telnyx provider account.
- Complete Brand and Campaign/10DLC registration.
- Purchase or assign sending number.
- Create or confirm Messaging Profile.
- Configure inbound webhook URL.
- Configure delivery receipt webhook URL.
- Configure webhook signature verification.
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
- Privacy policy and rewards terms published.
- SMS opt-in checkbox uses approved copy and is unchecked by default.
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
