# Product Blueprint

Last reviewed: 2026-05-06

## Goal

Create a restaurant reward system that can start small, run affordably on HostGator cPanel, and grow into gamified campaigns, kiosk signups, push/SMS/email notifications, and live event games.

## Core Users

| User | Needs |
| --- | --- |
| Customer | Join quickly, see rewards, receive offers, redeem coupons without friction. |
| Staff | Look up a customer, scan/redeem a coupon, add points, handle mistakes quickly. |
| Manager | Create specials, generate coupons, review signups, send campaigns, see basic reporting. |
| Owner/Admin | Configure rules, locations, permissions, provider keys, and compliance records. |

## Version 1 Experience

1. Customer scans a QR code at the restaurant.
2. Customer enters mobile number, optional email, birthday, and consent checkboxes.
3. System sends a confirmation text or displays a join confirmation.
4. Customer receives a welcome coupon.
5. Staff can redeem the coupon by scanning QR or typing a short code.
6. Admin can view customers, coupons, points, campaigns, and redemptions.

## Primary Modules

| Module | Version | Purpose |
| --- | --- | --- |
| Customer enrollment | V1 | QR signup, kiosk signup, SMS keyword signup later. |
| Consent records | V1 | Store opt-in, opt-out, source, timestamp, and policy version. |
| Reward ledger | V1 | Track point earns, redemptions, adjustments, and expiration. |
| Coupon engine | V1 | Generate single-use and campaign coupons. |
| Staff redemption | V1 | Scan QR or enter code, with fraud checks. |
| Admin dashboard | V1 | Manage settings, campaigns, customers, rewards, reports. |
| Notifications | V1/V2 | SMS first, then email and push. |
| PWA install | V1/V2 | Customer "app" without App Store deployment. |
| Automation | V2 | Birthday rewards, win-back offers, streaks, slow-day specials. |
| Game mechanics | V2/V3 | Spin-to-win, trivia, streaks, referrals, leaderboard. |
| Live buzzer | V3 | Real-time or near-real-time game show flow. |

## Reward Concepts

Start with rewards that are easy to explain:

- Welcome reward: join and get a coupon.
- Visit reward: earn points per visit.
- Spend reward: earn points per dollar when purchase data is available.
- Streak reward: visit X times in Y days.
- Birthday reward: send a birthday coupon.
- Slow-day reward: bonus offer for specific weekdays or time windows.
- Referral reward: customer shares link or code with a friend.

## Version 1 Success Criteria

- Customer can join in less than 60 seconds.
- Staff can redeem a coupon in less than 15 seconds.
- Admin can create a coupon campaign without developer help.
- System records proof of SMS/email consent.
- No customer receives marketing messages after opting out.
- The same codebase can support another location by adding records, not copying code.

## Long-Term Product Shape

The system should become a lightweight restaurant CRM:

- Customer identity and preferences.
- Rewards and coupon history.
- Campaigns and segmentation.
- Notification delivery logs.
- Event and game participation.
- Optional POS/order integrations once the restaurant chooses a POS path.
