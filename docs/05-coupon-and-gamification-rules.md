# Coupon And Gamification Rules

Last reviewed: 2026-05-06

## Coupon Types

| Type | Example | Notes |
| --- | --- | --- |
| Welcome | Free drink with purchase | Created after signup. |
| Birthday | Free dessert during birthday month | Triggered by birthday month/day. |
| Points reward | $5 off after 100 points | Created when customer redeems points. |
| Slow-day special | 20 percent off Tuesday 2-5 PM | Campaign-driven. |
| Win-back | We miss you: $5 off this week | Sent after no visit for X days. |
| Referral | Bring a friend, both get a reward | Requires referral tracking. |
| Event | Trivia night bonus coupon | Linked to event participation. |
| Staff issued | Manager apology coupon | Requires reason and audit log. |

## Coupon Statuses

- Draft
- Active
- Reserved
- Issued
- Viewed
- Redeemed
- Expired
- Canceled
- Voided

## Redemption Checks

Before redemption, the system should check:

- Coupon exists.
- Coupon belongs to the customer or is public.
- Coupon is active.
- Coupon has not expired.
- Coupon has remaining uses.
- Customer has not exceeded per-customer limit.
- Location is eligible.
- Minimum purchase is met if configured.
- Staff member has permission.

## Code And QR Strategy

Use both:

- Human-readable short code for manual entry.
- Secure redemption token for QR scanning.

Both redemption methods are confirmed for version 1. The staff screen should accept QR scans first for speed and also provide a manual short-code field when the camera/scanner is unavailable.

The short code can be visible, but the QR token should be harder to guess. If possible, redemption should happen through a server-side lookup, not by trusting the QR payload.

## Fraud Controls

| Control | Purpose |
| --- | --- |
| One-time tokens | Prevent screenshots from being reused. |
| Redemption attempt logs | Detect repeated failed attempts. |
| Staff permissions | Limit manual overrides. |
| Manager override reason | Preserve accountability. |
| Rate limits | Prevent code guessing. |
| Duplicate customer detection | Merge repeated signups by same phone/email. |
| Campaign limits | Prevent public coupons from exceeding budget. |

## Gamification Mechanics

Start with mechanics that do not depend on POS integration:

| Mechanic | How It Works |
| --- | --- |
| Welcome bonus | Customer gets points or coupon after joining. |
| Visit streak | Staff records visit; system counts visits within a window. |
| Spin-to-win | Customer can spin after signup or redemption. Prizes are limited by odds and caps. |
| Trivia reward | Customers answer a question and get points/coupon. |
| Referral | Customer shares referral link/code. |
| Flash challenge | Visit during a time window to unlock bonus. |

## Prize Controls

For random or game-based rewards, define:

- Prize name.
- Prize type.
- Odds/weight.
- Daily max wins.
- Campaign max wins.
- Per-customer max wins.
- Expiration.
- Staff approval requirement.

Example:

| Prize | Weight | Daily Cap | Campaign Cap |
| --- | ---: | ---: | ---: |
| 10 bonus points | 70 | 500 | 5000 |
| Free drink | 20 | 50 | 500 |
| Free appetizer | 9 | 10 | 100 |
| Grand prize | 1 | 1 | 10 |

## Points Ledger Rules

Never store points only as a single editable number.

Use a ledger:

- Earn entries add points.
- Redemption entries subtract points.
- Expiration entries subtract points.
- Adjustment entries add/subtract points with reason.

The current balance is calculated from ledger entries or cached after each ledger write.

## Suggested Version 1 Reward Setup

- Signup reward: one single-use coupon.
- Visit earn: 10 points per staff-confirmed visit. Visit-based earning is confirmed for version 1.
- Points reward: 100 points = $5 off.
- Birthday reward: free dessert, valid for 30 days.
- Win-back: $5 off after 45 days without activity.
- Campaign limit: manager must approve sends over 250 recipients.
