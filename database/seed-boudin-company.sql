-- Pilot seed data for The Boudin Company
-- Last reviewed: 2026-05-06
-- Target: MySQL/MariaDB on cPanel
-- Run after schema-draft.sql on a fresh database.

INSERT INTO businesses (
  name,
  legal_name,
  public_website_url,
  privacy_policy_url,
  terms_url,
  default_timezone
) VALUES (
  'The Boudin Company',
  NULL,
  NULL,
  NULL,
  NULL,
  'America/Chicago'
);

SET @business_id = LAST_INSERT_ID();

INSERT INTO locations (
  business_id,
  name,
  address_line1,
  city,
  state,
  postal_code,
  phone,
  timezone,
  is_active
) VALUES (
  @business_id,
  'Rosenberg',
  '28115 Southwest Freeway',
  'Rosenberg',
  'TX',
  '77461',
  '+17135615645',
  'America/Chicago',
  1
);

SET @location_id = LAST_INSERT_ID();

INSERT INTO signup_sources (
  business_id,
  location_id,
  code,
  name,
  source_type,
  is_active
) VALUES
  (@business_id, @location_id, 'boudin-rosenberg-qr-counter', 'Rosenberg counter QR', 'qr', 1),
  (@business_id, @location_id, 'boudin-rosenberg-qr-table', 'Rosenberg table QR', 'qr', 1),
  (@business_id, @location_id, 'boudin-rosenberg-kiosk', 'Rosenberg in-store kiosk', 'kiosk', 1);

INSERT INTO reward_rules (
  business_id,
  location_id,
  name,
  rule_type,
  points_value,
  rule_config,
  is_active
) VALUES
  (@business_id, @location_id, 'Manual visit credit', 'visit', 10, JSON_OBJECT('requires_staff_confirmation', true), 1),
  (@business_id, @location_id, 'Welcome bonus', 'manual', 25, JSON_OBJECT('trigger', 'signup'), 1);

INSERT INTO coupon_templates (
  business_id,
  name,
  coupon_type,
  discount_value,
  discount_unit,
  default_valid_days,
  min_purchase_amount,
  per_customer_limit,
  campaign_limit,
  template_config,
  is_active
) VALUES
  (
    @business_id,
    'Welcome reward',
    'custom',
    NULL,
    NULL,
    14,
    NULL,
    1,
    NULL,
    JSON_OBJECT('description', 'First signup offer; final restaurant-approved offer text pending.'),
    1
  ),
  (
    @business_id,
    '100 point reward',
    'amount_off',
    5.00,
    'currency',
    30,
    NULL,
    1,
    NULL,
    JSON_OBJECT('points_required', 100),
    1
  );

INSERT INTO message_templates (
  business_id,
  name,
  channel,
  subject,
  body_text,
  body_html,
  is_active
) VALUES
  (
    @business_id,
    'SMS welcome',
    'sms',
    NULL,
    'The Boudin Company: Welcome to rewards. Your first offer is ready: [Offer]. Show code [Code] by [Date]. Reply STOP to opt out.',
    NULL,
    1
  ),
  (
    @business_id,
    'SMS help',
    'sms',
    NULL,
    'The Boudin Company Rewards help: call 713-561-5645 or visit [SupportUrl]. Msg&data rates may apply. Msg freq varies. Reply STOP to cancel.',
    NULL,
    1
  ),
  (
    @business_id,
    'SMS opt-out confirmation',
    'sms',
    NULL,
    'The Boudin Company Rewards: You are unsubscribed and will receive no more marketing texts. Reply HELP for help.',
    NULL,
    1
  ),
  (
    @business_id,
    'SMS opt-in confirmation',
    'sms',
    NULL,
    'The Boudin Company Rewards: You are subscribed. Msg freq varies, up to 4/mo. Msg&data rates may apply. Reply HELP for help or STOP to cancel.',
    NULL,
    1
  );
