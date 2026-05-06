# Restaurant Rewards Gamification System

Last reviewed: 2026-05-06

This repository contains the planning package for a simple, budget-friendly restaurant reward and gamification system designed for cPanel hosting on HostGator, PHP, and MySQL.

The first pilot location is The Boudin Company in Rosenberg, Texas.

The recommended approach is a custom PHP/MySQL backend with a Progressive Web App (PWA) customer experience, QR-code signup, coupon generation, SMS/email/web-push notifications, and room for future live game features such as a buzzer system.

## Recommended Version 1 Direction

- Backend: PHP 8.x and MySQL/MariaDB on cPanel.
- Customer app: installable PWA that works in browser, Android, iPhone, Windows, and desktop.
- Messaging: Telnyx is the selected SMS provider for the pilot. Twilio remains documented as a backup option.
- Rewards: points are earned by visit for version 1, using staff-confirmed visits.
- Redemption: staff can redeem coupons by scanning a QR code or manually entering a short code.
- Push notifications: OneSignal for web push and later mobile-style messaging flows.
- Email: Brevo or a transactional email provider, depending on whether marketing automation is needed immediately.
- Coupon engine: internal coupon codes and QR redemption so the restaurant owns the reward rules.
- Future games: build on the same customer identity and event ledger.

## Document Index

- [Pilot Profile](docs/00-pilot-profile.md)
- [Product Blueprint](docs/01-product-blueprint.md)
- [Configuration Parameters](docs/02-configuration-parameters.md)
- [Technical Architecture](docs/03-technical-architecture.md)
- [Integration Options](docs/04-integration-options.md)
- [Coupon And Gamification Rules](docs/05-coupon-and-gamification-rules.md)
- [Compliance Checklist](docs/06-compliance-checklist.md)
- [Legal Draft Package](docs/legal/README.md)
- [Implementation Roadmap](docs/07-implementation-roadmap.md)
- [Deployment Checklist](docs/08-deployment-checklist.md)
- [Source Notes](docs/09-source-notes.md)
- [SMS Development Workaround](docs/10-sms-development-workaround.md)
- [Hosting Ready Handoff](docs/11-hosting-ready-handoff.md)
- [Draft MySQL Schema](database/schema-draft.sql)
- [Pilot Seed Data](database/seed-boudin-company.sql)

## Immediate Decisions Needed

1. Hosting is ready. Next setup items are the public domain/subdomain, document root path, PHP version, MySQL database, and non-public config location.
2. Build can proceed in `log_only` SMS mode while Telnyx account access, API key, Messaging Profile, sender number, webhook URLs, and 10DLC/business texting process are pending.
3. Before live marketing SMS, confirm Telnyx setup, owner/legal approval, and published SMS opt-in, privacy policy, and rewards terms.

## Build Philosophy

Keep the first version small and reliable:

- Customers sign up with phone number and optional email.
- The system tracks consent, rewards, coupons, and redemptions.
- Hosting is ready, so app scaffolding and database setup can begin.
- SMS starts in no-send/log-only mode until Telnyx and 10DLC are ready.
- Staff can redeem rewards quickly at the counter.
- Admins can create specials and send compliant notifications.
- Everything is designed to be duplicated for additional restaurant locations.
