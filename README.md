# Restaurant Rewards Gamification System

Last reviewed: 2026-05-06

This repository contains the planning package for a simple, budget-friendly restaurant reward and gamification system designed for cPanel hosting on HostGator, PHP, and MySQL.

The recommended approach is a custom PHP/MySQL backend with a Progressive Web App (PWA) customer experience, QR-code signup, coupon generation, SMS/email/web-push notifications, and room for future live game features such as a buzzer system.

## Recommended Version 1 Direction

- Backend: PHP 8.x and MySQL/MariaDB on cPanel.
- Customer app: installable PWA that works in browser, Android, iPhone, Windows, and desktop.
- Messaging: Telnyx for lowest SMS cost, or Twilio for easier setup and documentation.
- Push notifications: OneSignal for web push and later mobile-style messaging flows.
- Email: Brevo or a transactional email provider, depending on whether marketing automation is needed immediately.
- Coupon engine: internal coupon codes and QR redemption so the restaurant owns the reward rules.
- Future games: build on the same customer identity and event ledger.

## Document Index

- [Product Blueprint](docs/01-product-blueprint.md)
- [Configuration Parameters](docs/02-configuration-parameters.md)
- [Technical Architecture](docs/03-technical-architecture.md)
- [Integration Options](docs/04-integration-options.md)
- [Coupon And Gamification Rules](docs/05-coupon-and-gamification-rules.md)
- [Compliance Checklist](docs/06-compliance-checklist.md)
- [Implementation Roadmap](docs/07-implementation-roadmap.md)
- [Deployment Checklist](docs/08-deployment-checklist.md)
- [Source Notes](docs/09-source-notes.md)
- [Draft MySQL Schema](database/schema-draft.sql)

## Immediate Decisions Needed

1. Pick the first restaurant/location for the pilot.
2. Pick the SMS provider: Telnyx for cost, Twilio for ease.
3. Decide whether points are earned by visit, dollars spent, item/category, or manual staff action for version 1.
4. Decide whether redemption happens by staff scanning a QR code or manually entering a short code.
5. Prepare SMS opt-in language, privacy policy, and terms page before sending marketing texts.

## Build Philosophy

Keep the first version small and reliable:

- Customers sign up with phone number and optional email.
- The system tracks consent, rewards, coupons, and redemptions.
- Staff can redeem rewards quickly at the counter.
- Admins can create specials and send compliant notifications.
- Everything is designed to be duplicated for additional restaurant locations.
