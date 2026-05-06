# Compliance Checklist

Last reviewed: 2026-05-06

This is an implementation checklist, not legal advice. A restaurant should have counsel review marketing language, privacy policy, and terms before sending automated marketing texts or emails.

Draft legal copy has been prepared in [Legal Draft Package](legal/README.md). Treat those files as the source drafts for the SMS opt-in flow, privacy policy page, and rewards terms page.

## SMS Consent

For U.S. marketing SMS, the system should require clear consent before sending promotional texts.

During development, SMS may run in `log_only` mode. That mode may store intended message records and simulate inbound consent or opt-out events, but it must not send real marketing texts.

Store:

- Customer phone number.
- Consent channel: web form, QR, kiosk, staff, SMS keyword, import.
- Consent text shown to the customer.
- Checkbox state or confirmation action.
- Timestamp.
- IP address or device/kiosk identifier where applicable.
- Location/source QR code.
- Privacy policy and terms version.

## 10DLC Registration

U.S. application-to-person messaging using local 10-digit numbers requires 10DLC registration.

10DLC registration is a live SMS launch gate, not a blocker for building signup, consent storage, coupon generation, campaign previews, or logged message queues.

Registration normally includes:

- Legal business name.
- EIN or tax ID.
- Business address.
- Website.
- Brand information.
- Campaign/use case.
- Sample messages.
- Opt-in flow description.
- Opt-out/help handling.
- Message volume estimate.

Telnyx and Twilio both document 10DLC registration. Telnyx notes that unregistered 10DLC traffic is blocked; Twilio says registration uses Brand and Campaign information and includes opt-in, opt-out, and help details.

## Opt-In Language Template

Use the approved language from [SMS Opt-In Language](legal/sms-opt-in-language.md). Current draft signup language:

```text
Yes, I agree to receive recurring automated marketing text messages from The Boudin Company Rewards at the mobile number I provide. Message frequency varies, up to 4 messages per month. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help. I agree to the Rewards Terms and Privacy Policy.
```

Use an unchecked checkbox. Do not pre-check marketing consent.

## SMS Message Template

Example:

```text
The Boudin Company: Your reward is ready: [Offer]. Show code [Code] by [Date]. Reply STOP to opt out.
```

For transactional messages, avoid promotional copy unless the customer gave marketing consent.

## Opt-Out Requirements

The system should:

- Recognize STOP, STOPALL, UNSUBSCRIBE, CANCEL, END, and QUIT where supported.
- Immediately mark SMS consent as opted out.
- Send a final opt-out confirmation if provider rules allow.
- Suppress future marketing SMS.
- Keep transactional SMS separate and conservative.
- Store the opt-out event.

## HELP Handling

Inbound HELP should return:

```text
The Boudin Company Rewards help: call 713-561-5645 or visit [SupportUrl]. Msg&data rates may apply. Msg freq varies. Reply STOP to cancel.
```

## Email Compliance

Marketing email should include:

- Business name.
- Physical mailing address.
- Unsubscribe link.
- Suppression list.
- Bounce handling.
- Complaint handling.

Use a provider such as Brevo for marketing emails instead of raw hosting email.

## Push Notification Consent

Push notifications require browser/OS permission. Store:

- Push subscription token or provider ID.
- Browser/device type if available.
- Permission timestamp.
- Associated customer if logged in.
- Unsubscribe/delete date.

## Privacy Policy Requirements

The privacy policy should disclose:

- What data is collected.
- Why it is collected.
- Messaging providers used.
- How customers opt out.
- How to request deletion.
- Data retention practices.
- Coupon/reward tracking.
- Analytics/cookies if used.
- A statement that SMS opt-in data and mobile phone numbers are not sold or shared with third parties for their own marketing or promotional purposes.

## Data Retention

Recommended retention:

| Data | Recommendation |
| --- | --- |
| Consent events | Keep indefinitely or as legally advised. |
| Message logs | Keep 2-4 years or as legally advised. |
| Coupon redemptions | Keep for accounting/reporting needs. |
| Deleted customer profile | Anonymize where possible; retain consent proof if needed. |
| Audit logs | Keep at least 1-2 years. |

## Staff Training

Staff should know:

- Do not add customers to marketing SMS without clear consent.
- Do not promise a discount outside program rules.
- Do not bypass redemption warnings without manager approval.
- Respect opt-outs immediately.
- Never share customer phone/email lists outside the system.
