-- The Boudin Company Rewards database structure
-- Last reviewed: 2026-05-06
-- Target: MySQL/MariaDB on HostGator cPanel
--
-- Use this on the selected cPanel database: cpanel_rewards.
-- This script is for a fresh setup. It drops existing rewards tables first.
-- Do not paste database passwords into this file.

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS push_subscriptions;
DROP TABLE IF EXISTS notification_deliveries;
DROP TABLE IF EXISTS message_templates;
DROP TABLE IF EXISTS coupon_redemptions;
DROP TABLE IF EXISTS customer_coupons;
DROP TABLE IF EXISTS coupons;
DROP TABLE IF EXISTS campaigns;
DROP TABLE IF EXISTS coupon_templates;
DROP TABLE IF EXISTS points_ledger;
DROP TABLE IF EXISTS visits;
DROP TABLE IF EXISTS reward_rules;
DROP TABLE IF EXISTS customer_consents;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS signup_sources;
DROP TABLE IF EXISTS admin_users;
DROP TABLE IF EXISTS business_settings;
DROP TABLE IF EXISTS locations;
DROP TABLE IF EXISTS businesses;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE businesses (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  legal_name VARCHAR(200) NULL,
  public_website_url VARCHAR(255) NULL,
  privacy_policy_url VARCHAR(255) NULL,
  terms_url VARCHAR(255) NULL,
  default_timezone VARCHAR(80) NOT NULL DEFAULT 'America/Chicago',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE locations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  address_line1 VARCHAR(160) NULL,
  address_line2 VARCHAR(160) NULL,
  city VARCHAR(80) NULL,
  state VARCHAR(40) NULL,
  postal_code VARCHAR(20) NULL,
  phone VARCHAR(30) NULL,
  timezone VARCHAR(80) NOT NULL DEFAULT 'America/Chicago',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_locations_business (business_id),
  CONSTRAINT fk_locations_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE business_settings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  setting_key VARCHAR(120) NOT NULL,
  setting_value TEXT NULL,
  is_secret TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_business_setting (business_id, setting_key),
  CONSTRAINT fk_business_settings_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admin_users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(160) NOT NULL,
  role ENUM('owner','manager','staff','readonly') NOT NULL DEFAULT 'staff',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_admin_email (business_id, email),
  KEY idx_admin_location (location_id),
  CONSTRAINT fk_admin_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_admin_location FOREIGN KEY (location_id) REFERENCES locations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE signup_sources (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NULL,
  code VARCHAR(80) NOT NULL,
  name VARCHAR(160) NOT NULL,
  source_type ENUM('qr','kiosk','web','receipt','staff','event') NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_signup_source_code (business_id, code),
  KEY idx_signup_location (location_id),
  CONSTRAINT fk_signup_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_signup_location FOREIGN KEY (location_id) REFERENCES locations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE customers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  primary_location_id BIGINT UNSIGNED NULL,
  signup_source_id BIGINT UNSIGNED NULL,
  phone_e164 VARCHAR(30) NULL,
  email VARCHAR(190) NULL,
  first_name VARCHAR(80) NULL,
  last_name VARCHAR(80) NULL,
  birthday_month TINYINT UNSIGNED NULL,
  birthday_day TINYINT UNSIGNED NULL,
  status ENUM('active','unsubscribed','merged','banned','deleted') NOT NULL DEFAULT 'active',
  points_balance INT NOT NULL DEFAULT 0,
  last_visit_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_customer_phone (business_id, phone_e164),
  UNIQUE KEY uq_customer_email (business_id, email),
  KEY idx_customer_status (business_id, status),
  KEY idx_customer_location (primary_location_id),
  KEY idx_customer_signup_source (signup_source_id),
  CONSTRAINT fk_customers_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_customers_location FOREIGN KEY (primary_location_id) REFERENCES locations(id),
  CONSTRAINT fk_customers_signup_source FOREIGN KEY (signup_source_id) REFERENCES signup_sources(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE customer_consents (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  channel ENUM('sms','email','push') NOT NULL,
  status ENUM('opted_in','opted_out','pending') NOT NULL,
  source ENUM('qr','kiosk','staff','web','sms_keyword','import','admin') NOT NULL,
  disclosure_text TEXT NULL,
  policy_version VARCHAR(80) NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  location_id BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_consent_customer_channel (customer_id, channel, created_at),
  KEY idx_consent_location (location_id),
  CONSTRAINT fk_consents_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_consents_location FOREIGN KEY (location_id) REFERENCES locations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reward_rules (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NULL,
  name VARCHAR(160) NOT NULL,
  rule_type ENUM('visit','spend','item','birthday','referral','streak','manual','game') NOT NULL,
  points_value INT NOT NULL DEFAULT 0,
  rule_config JSON NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  starts_at DATETIME NULL,
  ends_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_reward_rules_business (business_id, is_active),
  KEY idx_reward_rules_location (location_id),
  CONSTRAINT fk_reward_rules_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_reward_rules_location FOREIGN KEY (location_id) REFERENCES locations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE visits (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NOT NULL,
  admin_user_id BIGINT UNSIGNED NULL,
  reward_rule_id BIGINT UNSIGNED NULL,
  visit_date DATE NOT NULL,
  points_awarded INT NOT NULL DEFAULT 0,
  verification_method ENUM('staff_confirmed','qr_checkin','receipt','manual_adjustment') NOT NULL DEFAULT 'staff_confirmed',
  status ENUM('pending','approved','rejected','voided') NOT NULL DEFAULT 'approved',
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_visits_customer (customer_id, visit_date),
  KEY idx_visits_location_date (location_id, visit_date),
  KEY idx_visits_admin (admin_user_id),
  KEY idx_visits_rule (reward_rule_id),
  CONSTRAINT fk_visits_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_visits_location FOREIGN KEY (location_id) REFERENCES locations(id),
  CONSTRAINT fk_visits_admin FOREIGN KEY (admin_user_id) REFERENCES admin_users(id),
  CONSTRAINT fk_visits_rule FOREIGN KEY (reward_rule_id) REFERENCES reward_rules(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE points_ledger (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NULL,
  admin_user_id BIGINT UNSIGNED NULL,
  reward_rule_id BIGINT UNSIGNED NULL,
  visit_id BIGINT UNSIGNED NULL,
  points_delta INT NOT NULL,
  balance_after INT NULL,
  reason ENUM('signup','visit','purchase','coupon_redemption','expiration','adjustment','referral','birthday','game') NOT NULL,
  reference_type VARCHAR(80) NULL,
  reference_id BIGINT UNSIGNED NULL,
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_points_customer (customer_id, created_at),
  KEY idx_points_visit (visit_id),
  KEY idx_points_location (location_id),
  KEY idx_points_admin (admin_user_id),
  KEY idx_points_rule (reward_rule_id),
  CONSTRAINT fk_points_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_points_location FOREIGN KEY (location_id) REFERENCES locations(id),
  CONSTRAINT fk_points_admin FOREIGN KEY (admin_user_id) REFERENCES admin_users(id),
  CONSTRAINT fk_points_rule FOREIGN KEY (reward_rule_id) REFERENCES reward_rules(id),
  CONSTRAINT fk_points_visit FOREIGN KEY (visit_id) REFERENCES visits(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE coupon_templates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  coupon_type ENUM('percent_off','amount_off','free_item','points_bonus','custom') NOT NULL,
  discount_value DECIMAL(10,2) NULL,
  discount_unit ENUM('percent','currency','points','item') NULL,
  default_valid_days INT NULL,
  min_purchase_amount DECIMAL(10,2) NULL,
  points_required INT NULL,
  per_customer_limit INT NOT NULL DEFAULT 1,
  campaign_limit INT NULL,
  template_config JSON NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_coupon_templates_business (business_id, is_active),
  CONSTRAINT fk_coupon_templates_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE campaigns (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NULL,
  name VARCHAR(160) NOT NULL,
  channel_mask SET('sms','email','push') NOT NULL,
  status ENUM('draft','scheduled','sending','sent','paused','canceled') NOT NULL DEFAULT 'draft',
  audience_config JSON NULL,
  scheduled_at DATETIME NULL,
  sent_at DATETIME NULL,
  created_by_admin_id BIGINT UNSIGNED NULL,
  approved_by_admin_id BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_campaign_business (business_id, status),
  KEY idx_campaign_location (location_id),
  KEY idx_campaign_creator (created_by_admin_id),
  KEY idx_campaign_approver (approved_by_admin_id),
  CONSTRAINT fk_campaign_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_campaign_location FOREIGN KEY (location_id) REFERENCES locations(id),
  CONSTRAINT fk_campaign_creator FOREIGN KEY (created_by_admin_id) REFERENCES admin_users(id),
  CONSTRAINT fk_campaign_approver FOREIGN KEY (approved_by_admin_id) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE coupons (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  coupon_template_id BIGINT UNSIGNED NULL,
  campaign_id BIGINT UNSIGNED NULL,
  code VARCHAR(40) NOT NULL,
  title VARCHAR(160) NOT NULL,
  description TEXT NULL,
  status ENUM('draft','active','issued','redeemed','expired','canceled','voided') NOT NULL DEFAULT 'active',
  coupon_type ENUM('percent_off','amount_off','free_item','points_bonus','custom') NOT NULL,
  discount_value DECIMAL(10,2) NULL,
  discount_unit ENUM('percent','currency','points','item') NULL,
  min_purchase_amount DECIMAL(10,2) NULL,
  starts_at DATETIME NULL,
  expires_at DATETIME NULL,
  max_uses INT NULL,
  total_uses INT NOT NULL DEFAULT 0,
  per_customer_limit INT NOT NULL DEFAULT 1,
  secure_token_hash CHAR(64) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_coupon_code (business_id, code),
  KEY idx_coupon_status (business_id, status),
  KEY idx_coupon_template (coupon_template_id),
  KEY idx_coupon_campaign (campaign_id),
  CONSTRAINT fk_coupons_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_coupons_template FOREIGN KEY (coupon_template_id) REFERENCES coupon_templates(id),
  CONSTRAINT fk_coupons_campaign FOREIGN KEY (campaign_id) REFERENCES campaigns(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE customer_coupons (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  coupon_id BIGINT UNSIGNED NOT NULL,
  status ENUM('issued','viewed','redeemed','expired','canceled') NOT NULL DEFAULT 'issued',
  issued_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  viewed_at DATETIME NULL,
  redeemed_at DATETIME NULL,
  expires_at DATETIME NULL,
  UNIQUE KEY uq_customer_coupon (customer_id, coupon_id),
  KEY idx_customer_coupon_status (customer_id, status),
  KEY idx_customer_coupon_coupon (coupon_id),
  CONSTRAINT fk_customer_coupons_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_customer_coupons_coupon FOREIGN KEY (coupon_id) REFERENCES coupons(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE coupon_redemptions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  coupon_id BIGINT UNSIGNED NOT NULL,
  customer_coupon_id BIGINT UNSIGNED NULL,
  customer_id BIGINT UNSIGNED NULL,
  location_id BIGINT UNSIGNED NULL,
  admin_user_id BIGINT UNSIGNED NULL,
  redemption_code VARCHAR(40) NULL,
  redemption_method ENUM('qr','short_code','manual_override') NOT NULL DEFAULT 'short_code',
  status ENUM('approved','rejected','voided') NOT NULL,
  rejection_reason VARCHAR(160) NULL,
  purchase_amount DECIMAL(10,2) NULL,
  note VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_redemptions_coupon (coupon_id, created_at),
  KEY idx_redemptions_customer_coupon (customer_coupon_id),
  KEY idx_redemptions_customer (customer_id, created_at),
  KEY idx_redemptions_location (location_id),
  KEY idx_redemptions_admin (admin_user_id),
  CONSTRAINT fk_redemptions_coupon FOREIGN KEY (coupon_id) REFERENCES coupons(id),
  CONSTRAINT fk_redemptions_customer_coupon FOREIGN KEY (customer_coupon_id) REFERENCES customer_coupons(id),
  CONSTRAINT fk_redemptions_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_redemptions_location FOREIGN KEY (location_id) REFERENCES locations(id),
  CONSTRAINT fk_redemptions_admin FOREIGN KEY (admin_user_id) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE message_templates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  channel ENUM('sms','email','push') NOT NULL,
  subject VARCHAR(190) NULL,
  body_text TEXT NOT NULL,
  body_html MEDIUMTEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_message_templates_business (business_id, channel, is_active),
  CONSTRAINT fk_message_templates_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notification_deliveries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  campaign_id BIGINT UNSIGNED NULL,
  customer_id BIGINT UNSIGNED NOT NULL,
  message_template_id BIGINT UNSIGNED NULL,
  channel ENUM('sms','email','push') NOT NULL,
  provider VARCHAR(80) NOT NULL,
  provider_message_id VARCHAR(190) NULL,
  destination VARCHAR(190) NOT NULL,
  status ENUM('queued','sent','delivered','failed','clicked','opened','opted_out','log_only') NOT NULL DEFAULT 'queued',
  error_message VARCHAR(255) NULL,
  queued_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  sent_at DATETIME NULL,
  delivered_at DATETIME NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_delivery_campaign (campaign_id, status),
  KEY idx_delivery_customer (customer_id, channel, queued_at),
  KEY idx_delivery_template (message_template_id),
  CONSTRAINT fk_deliveries_campaign FOREIGN KEY (campaign_id) REFERENCES campaigns(id),
  CONSTRAINT fk_deliveries_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_deliveries_template FOREIGN KEY (message_template_id) REFERENCES message_templates(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE push_subscriptions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NULL,
  provider VARCHAR(80) NOT NULL,
  provider_subscription_id VARCHAR(190) NOT NULL,
  device_label VARCHAR(160) NULL,
  browser VARCHAR(80) NULL,
  platform VARCHAR(80) NULL,
  status ENUM('active','revoked','expired') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_push_provider_id (provider, provider_subscription_id),
  KEY idx_push_customer (customer_id, status),
  CONSTRAINT fk_push_customer FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  admin_user_id BIGINT UNSIGNED NULL,
  action VARCHAR(120) NOT NULL,
  entity_type VARCHAR(80) NOT NULL,
  entity_id BIGINT UNSIGNED NULL,
  before_data JSON NULL,
  after_data JSON NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_audit_business (business_id, created_at),
  KEY idx_audit_entity (entity_type, entity_id),
  KEY idx_audit_admin (admin_user_id),
  CONSTRAINT fk_audit_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_audit_admin FOREIGN KEY (admin_user_id) REFERENCES admin_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
