# Integration Options

Last reviewed: 2026-05-06

## Recommended Provider Stack

| Need | First Choice | Backup Choice | Reason |
| --- | --- | --- | --- |
| SMS API | Telnyx | Twilio | Telnyx is selected for the pilot; Twilio remains the backup API path. |
| Web/app push | OneSignal | Firebase Cloud Messaging | OneSignal is faster to launch; FCM is no-cost but more engineering. |
| Email marketing | Brevo | OneSignal Email or custom SMTP | Brevo is affordable and has automation. |
| Customer app | PWA | Native app later | PWA is cheapest and easiest to replicate. |
| QR codes | Internal generation | Static QR tools | Internal links can track location and source. |
| POS integration | None at V1 | Square/Toast later | Manual visit/coupon flow is faster and cheaper to pilot. |

## SMS Options

### Telnyx

Selected SMS provider for The Boudin Company pilot.

Use Telnyx for:

- Customer signup confirmation texts.
- Reward and coupon notifications.
- Marketing SMS after opt-in.
- STOP/HELP handling through inbound SMS webhooks.
- Delivery status updates through messaging webhooks.

Before live SMS, confirm:

- Telnyx account access and billing.
- API key and production key storage location.
- Messaging Profile ID.
- Sender number, hosted SMS number, toll-free number, or other sender type.
- 10DLC Brand and Campaign registration status for U.S. long-code SMS.
- Outbound SMS request/response format for `POST /v2/messages`.
- Inbound message webhook URL.
- Delivery status webhook URL.
- Webhook signature verification using Telnyx public key.
- Price per message segment and carrier fees.
- Rate limits and daily/monthly sending limits.

Current public Telnyx messaging pages list SMS pricing starting at $0.004 per message, but final cost depends on sender type, destination, carrier fees, registration fees, and volume.

Telnyx is a good fit because it is API-first, supports send/receive SMS and MMS, supports 10DLC registration workflows, and provides webhooks for inbound messages and delivery status. The restaurant system should still record opt-ins, opt-outs, HELP requests, message attempts, and delivery events internally.

### Development Workaround

Do not block the MVP build on Telnyx credentials or 10DLC. Build against `LogOnlySmsProvider` first.

Allowed before Telnyx is ready:

- QR signup and kiosk signup.
- SMS consent capture.
- Coupon generation.
- Message template rendering.
- Campaign draft, preview, approval, and queue creation.
- Logged SMS records in `notification_deliveries`.
- Simulated inbound STOP/HELP/JOIN/YES handling.

Blocked until Telnyx and legal launch requirements are complete:

- Real marketing SMS sends.
- Public SMS keyword advertising.
- Production sender number use.
- Any campaign that contacts real customers by SMS.

### Twilio

Backup option for ease, documentation, and developer familiarity.

Current U.S. SMS pricing page lists SMS starting at $0.0083 per outbound message to U.S. long codes, plus carrier fees and other possible fees. U.S. A2P 10DLC registration is required for long-code business texting.

Use Twilio if:

- Setup speed and documentation matter most.
- We want easy examples and broad support.
- Slightly higher per-message cost is acceptable.

### SMS Marketing Platforms

Examples include SimpleTexting, EZ Texting, SlickText, and TextMagic.

Use these if:

- You want less custom development.
- You are willing to pay more monthly.
- You do not need deep coupon/reward integration immediately.

For this project, a direct API provider is better because the reward system should own customer records, coupons, redemptions, and game activity.

## Push Notification Options

### OneSignal

Best practical choice for web push/PWA notifications.

Current public pricing shows a free plan with access to channels and free email sends, and Growth starting at $19/month plus usage costs. OneSignal lists mobile push, web push, in-app, email, and SMS/RCS as channels.

Use OneSignal for:

- PWA web push.
- Windows/browser notifications.
- Future mobile-style notification campaigns.
- Segmentation and delivery reporting.

### Firebase Cloud Messaging

Best no-cost infrastructure option.

Firebase pricing lists Cloud Messaging (FCM) as no-cost. However, using it directly usually requires more engineering effort, especially for a clean non-native PWA and admin workflow.

Use FCM if:

- We want maximum control.
- We are willing to build more notification infrastructure.
- We may later create native Android/iOS wrappers.

## Email Options

### Brevo

Good budget option for email campaigns and automation.

Brevo currently lists:

- Free plan: 300 daily email sends.
- Starter: starting at $9/month.
- Standard: starting at $18/month.
- SMS/WhatsApp credits as add-ons, with cost varying by recipient country.

Use Brevo if:

- Email marketing matters in version 1 or 2.
- We want forms, automation, reporting, and segmentation.
- We want a low-cost email platform instead of building everything.

### Custom SMTP / Host Email

Use only for system messages, not marketing campaigns.

Marketing email needs unsubscribe handling, bounce management, reputation management, and suppression lists. A dedicated provider is safer.

## PWA Capability

PWAs can support:

- Home screen install.
- App-like standalone window.
- Offline shell caching.
- Web push on supported browsers and devices.
- Android install through Chrome and other browsers.
- Windows install through Edge/Chrome.
- iOS install through Add to Home Screen.

Important iOS note: iOS/iPadOS 16.4 added Web Push support for web apps added to the Home Screen. Older iOS devices may have limited or no push support, so SMS/email should remain available.

## POS Integration Options

### Start Without POS Integration

This is the recommended pilot approach.

Staff can:

- Scan coupons.
- Add a visit.
- Add manual points.
- Redeem rewards.

This avoids getting blocked by POS vendor rules, API access, and subscriptions.

### Square

Square has a Loyalty API that supports loyalty programs, loyalty accounts, accruing points, redeeming rewards, promotions, and webhooks. This is attractive if the restaurant already uses Square and subscribes to Square Loyalty.

### Toast

Toast supports loyalty workflows and third-party loyalty in some contexts, but third-party loyalty features may require Toast Online Ordering Pro or Toast Websites and an existing Toast POS loyalty integration. Toast integrations can also be more gated than Square.

## Kiosk And In-Restaurant Screens

The same PWA can run in kiosk mode on:

- iPad.
- Android tablet.
- Windows touch screen.
- Counter display.
- QR code landing page.

Kiosk mode should use:

- Large form controls.
- Auto-reset after signup.
- No admin session on public devices.
- Location locked by kiosk token.

## Future Game Show Buzzer

Shared hosting is not ideal for precise real-time buzzer timing. For a later version:

- Use the same customer accounts.
- Run live events from an admin host screen.
- Let players join by QR/event code.
- Use WebSockets on a VPS or cloud service for timing.
- Store final game results back in MySQL.

For version 1, do not let the future buzzer force the core rewards system into expensive infrastructure.
