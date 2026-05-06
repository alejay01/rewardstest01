# Deployment Checklist

Last reviewed: 2026-05-06

## cPanel Setup

- Hosting account ready.
- Public test URL confirmed: `http://theboudincompany.com/test/rewards`.
- Document root confirmed: `public_html/test/rewards`.
- Enable SSL.
- Select PHP 8.1+ where available.
- Create MySQL database.
- Create MySQL user with least necessary privileges.
- Store database credentials outside public web root when possible.
- Set file permissions conservatively.
- Configure cron jobs for queued sends and automation.
- Confirm whether Git deployment, FTP/SFTP upload, or cPanel File Manager upload will be used.
- Upload the initial scaffold from `public_html/test/rewards/`.

## Database Setup

- Create database and user in cPanel MySQL Databases.
- Save credentials outside the repository.
- Import schema.
- Create initial business record.
- Create first location.
- Create owner/admin account.
- Create default reward settings.
- Create default coupon templates.
- Create consent policy version.
- Confirm seed default `sms.mode = log_only` before any SMS testing.

## Legal Page Setup

- Review and approve `docs/legal/sms-opt-in-language.md`.
- Review and approve `docs/legal/privacy-policy-template.md`.
- Review and approve `docs/legal/rewards-terms-template.md`.
- Publish privacy policy and rewards terms at public URLs.
- Add privacy policy and terms links beside the SMS opt-in checkbox.
- Save screenshots of the signup page, QR flow, and kiosk flow for Telnyx/business texting registration.
- Store policy and terms version as `boudin-rewards-2026-05-06-v1` unless the published copy changes.

## Development Workaround

- Keep SMS mode set to `log_only` until launch approval.
- Confirm `notification_deliveries.provider` stores `log_only` for simulated SMS.
- Confirm no outbound Telnyx API call is made in `log_only` mode.
- Test campaign drafts, previews, queueing, and reporting using logged SMS only.
- Test STOP, HELP, JOIN, and YES through a simulated inbound webhook/admin test tool.
- Add a launch guard that refuses live SMS when API key, sender number, Messaging Profile, legal URLs, or 10DLC status are missing.

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
- Do not commit hosting, database, or provider credentials to GitHub.
- Keep production config outside public web root.
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
- SMS mode changed from `log_only` to live only after Telnyx and 10DLC are complete.
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
