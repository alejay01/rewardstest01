# Configuration Parameters

Last reviewed: 2026-05-06

These are the parameters the system should support before development starts. They can be stored in database tables and edited from an admin settings screen.

## Business And Location

| Parameter | Example | Notes |
| --- | --- | --- |
| Business name | Redline Rewards | Used in SMS/email/push templates. |
| Legal business name | Restaurant LLC | Needed for SMS 10DLC registration. |
| EIN / tax ID | On file | Do not store casually unless required. Keep secure. |
| Location name | Downtown | Multi-location support should be built in from the start. |
| Address | 123 Main St | Used for campaigns and legal pages. |
| Time zone | America/Chicago | Needed for expiration and campaign scheduling. |
| Public website | https://example.com | Needed for SMS campaign review. |
| Privacy policy URL | https://example.com/privacy | Needed before marketing messages. |
| Terms URL | https://example.com/rewards-terms | Needed for program rules. |

## Customer Profile

| Parameter | Required | Notes |
| --- | --- | --- |
| Phone number | Yes for SMS | Store normalized E.164 format. |
| Email | Optional | Required for email campaigns. |
| First name | Optional | Useful for personalization. |
| Birthday month/day | Optional | Avoid collecting birth year unless needed. |
| Favorite location | Optional | Supports segmented offers. |
| Signup source | Yes | QR, kiosk, staff, web, SMS keyword, import. |
| Consent status | Yes | Separate SMS, email, and push consent. |
| Customer status | Yes | Active, unsubscribed, banned, merged, deleted. |

## Reward Rules

| Parameter | Example | Notes |
| --- | --- | --- |
| Earn basis | Visit | Visit is easiest before POS integration. |
| Points per visit | 10 | Can be configured by location or day. |
| Points per dollar | 1 per $1 | Requires order total from POS or staff input. |
| Welcome bonus | 25 points | Optional. |
| Referral bonus | 50 points | Award after referred customer completes first purchase. |
| Birthday reward | Free dessert | Trigger by birthday month/day. |
| Point expiration | 180 days | Optional, but must be disclosed in terms. |
| Max manual adjustment | 500 points | Fraud control for staff/admin actions. |

## Coupon Rules

| Parameter | Example | Notes |
| --- | --- | --- |
| Coupon type | Percent off, dollar off, free item | Store as structured values. |
| Discount value | 10 percent | Avoid free-form math. |
| Code format | 8 character code | Short enough for manual entry. |
| QR enabled | Yes | QR contains redemption token or URL. |
| Single use | Yes | Default for rewards. |
| Campaign limit | 500 uses | Useful for public offers. |
| Per-customer limit | 1 | Prevent repeated use. |
| Expiration | 14 days | Use location time zone. |
| Minimum purchase | $10 | Optional. |
| Eligible location | All locations | Restrict when needed. |
| Staff override | Manager only | Require reason and audit log. |

## Notification Rules

| Parameter | Example | Notes |
| --- | --- | --- |
| SMS provider | Telnyx | Provider should be swappable. |
| Email provider | Brevo | Provider should be swappable. |
| Push provider | OneSignal | Good for PWA/web push. |
| Quiet hours | 9 PM to 9 AM | Avoid late marketing. |
| Max marketing SMS | 4 per month | Prevent over-messaging. |
| Message categories | Transactional, marketing, event | Consent and rules vary by category. |
| Required footer | Reply STOP to opt out | Required for SMS marketing. |
| Campaign approval | Manager approval | Prevent accidental sends. |

## Signup Channels

| Channel | Version | Notes |
| --- | --- | --- |
| QR code | V1 | Printed at counter, tables, receipts, windows. |
| Kiosk/tablet | V1/V2 | Same signup page in kiosk mode. |
| Staff signup | V1 | Staff enters number after verbal/customer consent flow. |
| Website form | V1 | Link from restaurant website and social profiles. |
| SMS keyword | V2 | Example: text JOIN to the restaurant number. |
| Receipt link | V2 | Requires POS or receipt customization. |

## Reporting

| Report | Purpose |
| --- | --- |
| Daily signups | Shows QR/kiosk performance. |
| Coupon redemptions | Measures offer usage. |
| Campaign performance | Sent, delivered, clicked, redeemed. |
| Customer growth | Active, opted out, deleted, duplicate customers. |
| Reward liability | Outstanding points and unused rewards. |
| Staff actions | Adjustments, overrides, redemption mistakes. |

## Security And Access

| Parameter | Recommendation |
| --- | --- |
| Admin roles | Owner, manager, staff, read-only. |
| Password storage | PHP password_hash using bcrypt or Argon2 if available. |
| Sessions | Secure cookies, HTTPS only, idle timeout. |
| API keys | Store outside public web root when possible. |
| Audit logs | Record admin, action, object, timestamp, IP. |
| Backups | Daily database backup and weekly off-server copy. |
