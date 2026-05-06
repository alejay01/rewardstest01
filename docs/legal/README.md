# Legal Draft Package

Last reviewed: 2026-05-06

This folder contains draft legal and compliance copy for The Boudin Company Rewards. These drafts are implementation-ready starting points, not legal advice. The restaurant owner and qualified counsel should review and approve them before the system sends marketing text messages, emails, or push notifications.

## Files

- [SMS Opt-In Language](sms-opt-in-language.md)
- [Privacy Policy Template](privacy-policy-template.md)
- [Rewards Terms Template](rewards-terms-template.md)

## Publish Before Marketing SMS

Before sending marketing SMS:

1. Review the SMS opt-in language with the business owner and counsel.
2. Publish the privacy policy and rewards terms at public URLs.
3. Put links to the published policy and terms next to the SMS opt-in checkbox.
4. Use an unchecked SMS marketing checkbox.
5. Store the exact opt-in text, policy version, terms version, timestamp, source, and customer phone number.
6. Capture screenshots of the QR/kiosk/web opt-in flow for SMS provider registration.
7. Confirm Telnyx sender registration, opt-out handling, HELP handling, and webhook behavior.

## Suggested URLs

Replace these once the website path is chosen:

| Page | Suggested URL |
| --- | --- |
| Privacy Policy | `https://[domain]/privacy` |
| Rewards Terms | `https://[domain]/rewards-terms` |
| QR Signup | `https://[domain]/rewards/join?source=boudin-rosenberg-qr-counter` |

## Suggested Policy Version

Use this as the first stored version unless the published copy changes:

```text
boudin-rewards-2026-05-06-v1
```

## Sources Checked

- Telnyx messaging documentation covers outbound SMS/MMS, inbound webhooks, delivery status webhooks, webhook verification, and 10DLC registration steps.
- Twilio A2P 10DLC campaign approval guidance emphasizes documented opt-in for marketing SMS, separate SMS consent, STOP/HELP instructions, confirmation messages, and public privacy/terms pages.
- Twilio A2P 10DLC documentation says U.S. A2P 10DLC registration includes brand and campaign details, including how users opt in, opt out, and get help.
- CTIA Messaging Principles and Best Practices state that A2P marketing messages should consider express written consent and the ability to revoke consent.
- FTC CAN-SPAM guidance covers commercial email requirements, including accurate sender/subject information and opt-out rights.
