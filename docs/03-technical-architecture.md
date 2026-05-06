# Technical Architecture

Last reviewed: 2026-05-06

## Recommended Architecture

Use a monolithic PHP/MySQL application for the first production version.

This is the best fit for a small restaurant because it is inexpensive, deploys cleanly to cPanel, and is easy to replicate. The app can still use provider APIs for SMS, email, and push notifications.

## Hosting Layer

| Component | Recommendation |
| --- | --- |
| Hosting | HostGator shared hosting or small VPS with cPanel. |
| Runtime | PHP 8.1+ where available. |
| Database | MySQL/MariaDB through cPanel. |
| Web server | Apache through cPanel. |
| Scheduler | cPanel cron jobs. |
| SSL | cPanel/AutoSSL or existing certificate. |
| File storage | Local storage for QR/coupon images; S3-compatible storage later if needed. |

HostGator documentation says shared/reseller cPanels provide a PHP configuration plugin and lists PHP 8.1 as the current minimum for shared hosting servers. cPanel also provides MySQL database management and cron jobs.

## Application Shape

```text
public/
  index.php
  app manifest
  service worker
  customer PWA screens
admin/
  dashboard
  campaigns
  coupons
  customers
  reports
api/
  customer signup
  coupon redemption
  provider webhooks
cron/
  scheduled campaigns
  birthday rewards
  cleanup jobs
app/
  controllers
  services
  provider adapters
  database repositories
```

## Key Design Choices

- Use PHP prepared statements through PDO.
- Keep provider integrations behind adapter classes.
- Store all reward changes in an append-only ledger.
- Store all consent changes in a consent event table.
- Store coupon redemption attempts, even failed ones.
- Never hard-code a single restaurant; use business and location tables.
- Keep the PWA and admin panel in the same codebase at first.

## Provider Adapter Pattern

Create one internal interface for each channel:

```text
SmsProvider
  sendSms(to, body, metadata)
  handleInbound(payload)
  handleDeliveryReceipt(payload)

EmailProvider
  sendEmail(to, subject, html, text, metadata)

PushProvider
  sendPush(subscriptionId, title, body, url, metadata)
```

Then add providers:

- LogOnlySmsProvider
- TelnyxSmsProvider
- TwilioSmsProvider
- BrevoEmailProvider
- OneSignalPushProvider

This makes it possible to start with one provider and switch later without rewriting the reward system.

Telnyx is selected for the first pilot. The adapter should use the Telnyx Messaging API for outbound SMS, inbound message webhooks for STOP/HELP/keyword handling, and delivery status webhooks for message reporting. Keep Telnyx behind the same `SmsProvider` interface so another provider can be used later if pricing, compliance, or API support changes.

## SMS No-Send Development Mode

Until Telnyx login/API details and 10DLC registration are ready, use `LogOnlySmsProvider`.

In `log_only` mode:

- `sendSms` creates a `notification_deliveries` row with provider `log_only`.
- No external HTTP request is made.
- Message body, destination, campaign, template, and consent decision are visible in the admin/test screen.
- `handleInbound` can accept simulated test payloads for `STOP`, `HELP`, `JOIN`, and `YES`.
- Campaigns can be drafted, previewed, approved, queued, and reported without contacting a carrier.

Live Telnyx sending should require an explicit setting such as `sms.live_send_enabled = 1` plus a configured API key, Messaging Profile ID, sender number, public legal URLs, and completed Telnyx/10DLC setup.

## Data Flow

### QR Signup

1. Customer scans QR code.
2. PWA opens signup URL with location code.
3. Customer enters phone/email and checks consent boxes.
4. App validates fields and stores customer.
5. App records consent event and signup source.
6. App creates welcome coupon.
7. App sends confirmation via SMS/email/push if consent allows.

### Coupon Redemption

1. Customer shows QR code or short coupon code.
2. Staff opens redemption screen.
3. System checks coupon status, expiration, customer, location, and usage limits.
4. Staff confirms redemption.
5. System marks coupon redeemed and writes ledger/audit records.

### Campaign Send

1. Admin creates campaign.
2. Admin chooses audience segment.
3. System estimates recipients and cost.
4. Manager approves.
5. Cron job or immediate send queues messages.
6. Provider webhooks update delivery status.
7. Coupon redemptions link back to campaign.

## PWA Requirements

- HTTPS.
- `manifest.json` with app name, icons, theme color, display mode.
- Service worker for app shell caching.
- Install prompt for Android/desktop where supported.
- iOS add-to-home-screen instructions inside customer account screen.
- Web push integration where browser and OS support it.

PWAs are a strong fit because they avoid app store review, work through regular web pages, and can be installed on supported phones and desktops.

## cPanel Considerations

Shared hosting is affordable but has limits:

- Avoid long-running background workers.
- Use cron jobs for scheduled work.
- Keep outbound API calls short and retry later on failure.
- Queue campaign messages in small batches.
- Use database indexes carefully.
- Keep image uploads small.
- Use a VPS later if real-time games, higher traffic, or websocket services become important.

## When To Upgrade Beyond Shared Hosting

Move to VPS/cloud hosting when any of these become necessary:

- Real-time live buzzer with sub-second timing.
- High-volume messaging jobs.
- More than 25 simultaneous MySQL connections on shared hosting.
- Queue workers, websockets, or Node.js services.
- Advanced analytics workloads.
- Multiple restaurant brands with high traffic.
