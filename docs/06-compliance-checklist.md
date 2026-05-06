# Compliance Checklist

Last reviewed: 2026-05-06

This is an implementation checklist, not legal advice. A restaurant should have counsel review marketing language, privacy policy, and terms before sending automated marketing texts or emails.

## SMS Consent

For U.S. marketing SMS, the system should require clear consent before sending promotional texts.

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

Example signup language:

```text
By checking this box, I agree to receive recurring automated marketing text messages from [Restaurant Name] at the phone number provided. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help. See Terms and Privacy Policy.
```

Use an unchecked checkbox. Do not pre-check marketing consent.

## SMS Message Template

Example:

```text
[Restaurant Name]: Your reward is ready: [Offer]. Show code [Code] by [Date]. Reply STOP to opt out.
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
[Restaurant Name] rewards help: visit [support URL] or call [phone]. Reply STOP to opt out. Msg/data rates may apply.
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
