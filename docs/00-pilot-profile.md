# Pilot Profile

Last reviewed: 2026-05-06

## Restaurant

| Field | Value |
| --- | --- |
| Restaurant name | The Boudin Company |
| Pilot location | Rosenberg, Texas |
| Address | 28115 Southwest Freeway |
| City | Rosenberg |
| State | Texas |
| ZIP code | 77461 |
| Phone | 713-561-5645 |
| Normalized phone | +17135615645 |
| Time zone | America/Chicago |
| SMS provider | Telnyx |
| Points earning basis | Visit |
| Draft points value | 10 points per staff-confirmed visit |
| Redemption methods | QR scan and manual short-code entry |

## Pilot Assumptions

- The Rosenberg location is the first deployment.
- SMS messaging will use Telnyx once account access, API keys, a Messaging Profile, and sender registration are ready.
- Marketing SMS still needs clear opt-in language, opt-out handling, HELP handling, and business texting registration/compliance review.
- QR signup should default to this location unless another location is added.
- Version 1 rewards should use staff-confirmed visits. Spend-based points can be considered later if POS integration is added.
- Staff redemption should support both QR scanning and manual short-code entry.

## Telnyx Information Needed Before Build

To implement the SMS adapter, collect:

- Telnyx API key.
- Messaging Profile ID.
- Send SMS endpoint configuration.
- Inbound SMS webhook URL and payload format.
- Delivery status webhook URL and payload format.
- Webhook public key/signature verification details.
- Sender number or sender ID configuration.
- 10DLC, toll-free, or other U.S. business texting registration status.
- Per-message pricing and carrier fee structure.
- Rate limits and daily/monthly volume limits.
- Required STOP/HELP behavior.

## Suggested Initial Public Signup Copy

```text
Join The Boudin Company Rewards for special offers, coupons, and reward updates.
```

## Suggested SMS Opt-In Copy

```text
Yes, I agree to receive recurring automated marketing text messages from The Boudin Company Rewards at the mobile number I provide. Message frequency varies, up to 4 messages per month. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help. I agree to the Rewards Terms and Privacy Policy.
```

## Suggested Welcome Text

```text
The Boudin Company: Welcome to rewards. Your first offer is ready: [Offer]. Show code [Code] by [Date]. Reply STOP to opt out.
```
