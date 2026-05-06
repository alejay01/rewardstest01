# SMS Opt-In Language

Last reviewed: 2026-05-06

This is draft copy for The Boudin Company Rewards. Do not send marketing SMS until the business has reviewed the copy, published the linked privacy policy and terms, and confirmed Telnyx registration and opt-out handling.

## Primary Signup Form Copy

Use this on QR, kiosk, website, and staff-assisted signup forms.

### Headline

```text
Join The Boudin Company Rewards
```

### Supporting Copy

```text
Get reward updates, coupons, and special offers from The Boudin Company.
```

### SMS Marketing Checkbox

This checkbox must be optional and unchecked by default.

```text
Yes, I agree to receive recurring automated marketing text messages from The Boudin Company Rewards at the mobile number I provide. Message frequency varies, up to 4 messages per month. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help. I agree to the Rewards Terms and Privacy Policy.
```

### Link Labels

```text
Rewards Terms
Privacy Policy
```

## Short QR Sign Copy

Use on printed QR signage where space is limited.

```text
Scan to join The Boudin Company Rewards. Optional SMS offers available with consent. Msg & data rates may apply. Reply STOP to opt out. Terms and Privacy Policy apply.
```

## Kiosk Consent Reminder

Use near the submit button on a public tablet/kiosk.

```text
SMS offers are optional. You can join rewards without signing up for marketing texts.
```

## Staff-Assisted Signup Script

Best practice: have the customer complete the opt-in form themselves on their phone or the kiosk. Staff should not check the SMS marketing box on behalf of a customer.

If staff enters a customer's phone number, use a double opt-in message before marketing texts:

```text
The Boudin Company Rewards: Reply YES to receive recurring marketing texts with rewards, coupons, and offers. Msg freq varies, up to 4/mo. Msg&data rates may apply. Consent not required to buy. Reply STOP to cancel, HELP for help. Terms: [TermsUrl] Privacy: [PrivacyUrl]
```

Only mark SMS marketing consent as opted in after the customer replies `YES`.

## SMS Keyword Signup Flow

If Telnyx supports SMS keyword signup for the selected sender number, publish this instruction wherever the keyword is advertised:

```text
Text JOIN to [Telnyx Number] to join The Boudin Company Rewards texts. Msg freq varies, up to 4/mo. Msg&data rates may apply. Reply STOP to cancel, HELP for help. Terms and Privacy Policy apply.
```

Auto-reply after `JOIN`:

```text
The Boudin Company Rewards: Reply YES to receive recurring marketing texts with rewards, coupons, and offers. Msg freq varies, up to 4/mo. Msg&data rates may apply. Consent not required to buy. Reply STOP to cancel, HELP for help. Terms: [TermsUrl] Privacy: [PrivacyUrl]
```

Confirmation after `YES`:

```text
The Boudin Company Rewards: You are subscribed. Msg freq varies, up to 4/mo. Msg&data rates may apply. Reply HELP for help or STOP to cancel.
```

## Standard Confirmation Message

Send immediately after web, QR, or kiosk opt-in.

```text
The Boudin Company Rewards: You are subscribed. Msg freq varies, up to 4/mo. Msg&data rates may apply. Reply HELP for help or STOP to cancel.
```

## HELP Response

```text
The Boudin Company Rewards help: call 713-561-5645 or visit [SupportUrl]. Msg&data rates may apply. Msg freq varies. Reply STOP to cancel.
```

## STOP Response

```text
The Boudin Company Rewards: You are unsubscribed and will receive no more marketing texts. Reply HELP for help.
```

## Sample Marketing Text

```text
The Boudin Company: Your reward is ready: [Offer]. Show code [Code] by [Date]. Reply STOP to opt out.
```

## Consent Records To Store

For each SMS opt-in, store:

- Customer phone number in normalized format.
- Exact disclosure text shown.
- Checkbox state or handset reply.
- Policy version.
- Terms version.
- Signup source, such as QR, kiosk, web, SMS keyword, or staff-assisted.
- Timestamp.
- IP address or kiosk/device identifier where available.
- User agent where available.
- Location/source code.

## Prohibited Patterns

Do not use these patterns for marketing SMS:

- Pre-checked SMS consent boxes.
- Bundling SMS marketing consent into general terms acceptance only.
- Making SMS marketing consent required to buy food or join basic rewards.
- Sending marketing texts after STOP, UNSUBSCRIBE, CANCEL, END, or QUIT.
- Importing customer phone numbers into marketing SMS without documented consent.
