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
| SMS provider | Telelinux |

## Pilot Assumptions

- The Rosenberg location is the first deployment.
- SMS messaging will use Telelinux once API credentials and documentation are available.
- Marketing SMS still needs clear opt-in language, opt-out handling, HELP handling, and business texting registration/compliance review.
- QR signup should default to this location unless another location is added.
- Initial rewards should use manual staff-confirmed visits until POS integration is selected.

## Telelinux Information Needed Before Build

To implement the SMS adapter, collect:

- API base URL.
- Authentication method.
- Send SMS endpoint.
- Inbound SMS webhook format.
- Delivery receipt webhook format.
- Sender number or sender ID configuration.
- 10DLC, toll-free, or other U.S. business texting registration requirements.
- Per-message pricing and carrier fee structure.
- Rate limits and daily/monthly volume limits.
- Required STOP/HELP behavior.

## Suggested Initial Public Signup Copy

```text
Join The Boudin Company Rewards for special offers, coupons, and reward updates.
```

## Suggested SMS Opt-In Copy

```text
By checking this box, I agree to receive recurring automated marketing text messages from The Boudin Company at the phone number provided. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help. See Terms and Privacy Policy.
```

## Suggested Welcome Text

```text
The Boudin Company: Welcome to rewards. Your first offer is ready: [Offer]. Show code [Code] by [Date]. Reply STOP to opt out.
```
