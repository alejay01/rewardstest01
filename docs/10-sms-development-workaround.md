# SMS Development Workaround

Last reviewed: 2026-05-06

Telnyx remains the selected SMS provider, but Telnyx login/API details and the restaurant's business registration information for 10DLC can be handled later.

This workaround lets development continue without sending real SMS.

## Decision

Use `log_only` SMS mode until live SMS is approved.

Default seeded settings:

| Setting | Value |
| --- | --- |
| `sms.provider` | `telnyx` |
| `sms.mode` | `log_only` |
| `sms.live_send_enabled` | `0` |
| `sms.telnyx.10dlc_status` | `pending` |

## What Can Be Built Now

- QR signup flow.
- Kiosk signup flow.
- Customer profile and consent storage.
- SMS opt-in checkbox and exact disclosure capture.
- Welcome coupon generation.
- Coupon wallet and redemption by QR scan or short code.
- Message templates.
- Campaign draft, preview, approval, and scheduling.
- Message queue creation.
- Logged SMS delivery records.
- Simulated inbound `STOP`, `HELP`, `JOIN`, and `YES` events.
- Admin reporting for queued, logged, failed, and simulated delivery statuses.

## What Must Stay Blocked

- Real outbound SMS to customers.
- Marketing SMS campaigns.
- Public SMS keyword advertising.
- Production sender number use.
- Live Telnyx webhook processing as the only source of truth.

## Implementation Notes

Create a `LogOnlySmsProvider` behind the same `SmsProvider` interface as Telnyx.

Expected behavior:

- `sendSms(to, body, metadata)` writes a `notification_deliveries` row.
- Use provider value `log_only`.
- Keep status as `queued` or `sent` depending on how the admin UI wants to label simulated sends.
- Do not make an external HTTP request.
- Store enough metadata to trace customer, campaign, template, coupon, source, and consent decision.
- Allow admin-only simulated inbound events for testing opt-in and opt-out logic.

## Live SMS Activation Checklist

Only switch from `log_only` to live Telnyx sending after:

- Telnyx account access is confirmed.
- Telnyx API key is stored outside public web root.
- Messaging Profile ID is configured.
- Sender number is purchased, hosted, or assigned.
- 10DLC/business texting registration is approved where required.
- Privacy policy and rewards terms are published.
- SMS opt-in language is approved.
- STOP and HELP handling is tested.
- Delivery status webhooks are tested.
- Webhook signature verification is enabled.
- Owner approves the first live internal test send.

## Safety Rule

The application should refuse live SMS if any of these are missing:

- `sms.live_send_enabled = 1`.
- API key.
- Messaging Profile ID.
- Sender number.
- Public privacy policy URL.
- Public rewards terms URL.
- Approved SMS policy version.
- 10DLC or sender registration status.
