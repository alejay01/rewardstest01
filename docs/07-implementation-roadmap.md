# Implementation Roadmap

Last reviewed: 2026-05-06

## Phase 0: Decisions And Setup

Duration: 1-3 days

Deliverables:

- Confirm restaurant name, locations, timezone, and domain. Restaurant and location are confirmed for The Boudin Company in Rosenberg, Texas; domain still needs confirmation.
- Choose SMS provider. Telelinux is selected; API credentials/docs still need confirmation.
- Confirm reward earning basis. Visit-based earning is selected for version 1.
- Draft privacy policy, terms, SMS opt-in language, and sample messages. Drafts are prepared in `docs/legal`; they still need owner/legal review and public URLs.
- Decide first reward rule and welcome coupon.
- Create repository, hosting account, and database.

## Phase 1: Core MVP

Duration: 1-2 weeks

Deliverables:

- MySQL schema.
- Admin login and roles.
- Customer signup page.
- QR code signup links by location.
- Customer records and consent records.
- Coupon generation.
- Staff redemption screen with QR scanning and manual short-code entry.
- Basic points ledger.
- Audit logs.

## Phase 2: Messaging

Duration: 1 week

Deliverables:

- SMS provider adapter.
- SMS confirmation messages.
- STOP/HELP inbound handling.
- Delivery receipt handling.
- Email provider adapter.
- Message templates.
- Campaign send queue.
- Send throttling through cron.

## Phase 3: PWA And Push

Duration: 1 week

Deliverables:

- App manifest.
- Service worker.
- Customer account screen.
- Coupon wallet screen.
- Add-to-home-screen guidance.
- OneSignal or FCM push integration.
- Push subscription tracking.

## Phase 4: Automation And Gamification

Duration: 1-2 weeks

Deliverables:

- Birthday automation.
- Win-back automation.
- Visit streaks.
- Spin-to-win or prize wheel.
- Referral links/codes.
- Campaign reports.
- Fraud/rate-limit controls.

## Phase 5: POS And Live Games

Duration: depends on provider access

Deliverables:

- POS data import or API integration.
- Spend-based points.
- Item/category rewards.
- Live trivia/event mode.
- Game show buzzer proof of concept.
- WebSocket service on VPS/cloud if real-time timing is required.

## Pilot Scope Recommendation

Pilot with:

- One location.
- One welcome coupon.
- One visit-based points rule.
- SMS opt-in.
- Staff redemption by QR scan and manual short-code entry.
- No POS integration.
- Weekly campaign sending only after consent checks are tested.

## Budget Categories

| Category | Low-Cost Approach |
| --- | --- |
| Hosting | Existing HostGator/cPanel plan. |
| Domain/SSL | Existing website domain and cPanel SSL. |
| SMS | Telelinux provider fees plus any required U.S. business texting registration/carrier fees. |
| Email | Brevo free or Starter/Standard depending on volume. |
| Push | OneSignal free/Growth or Firebase no-cost. |
| QR codes | Generated internally. |
| Kiosk | Existing tablet in kiosk mode. |

## First Release Acceptance Tests

- Customer can sign up from QR code.
- Consent event is stored with exact disclosure text.
- Welcome coupon is created.
- Staff can redeem coupon once by QR scan.
- Staff can redeem coupon once by manual short-code entry.
- Second redemption attempt is rejected.
- Opt-out prevents future SMS campaigns.
- Admin can create a campaign and preview estimated recipients.
- Cron can send queued messages in batches.
- Database backup process is documented.
