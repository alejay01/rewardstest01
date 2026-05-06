# The Boudin Company Rewards MySQL Import Instructions

Last reviewed: 2026-05-06

Use these instructions to create/update the first rewards database structure in HostGator cPanel.

## Database Details

| Item | Value |
| --- | --- |
| Host | `localhost` |
| Database | `cpanel_rewards` |
| Username | `cpanel_rewards_user` |
| App config file | `public_html/test/rewards/_includes/config.local.php` |
| Public test URL | `http://theboudincompany.com/test/rewards` |

Do not put the MySQL password in GitHub, SQL files, screenshots, or shared documents.

## Before Importing

1. Log in to HostGator cPanel.
2. Open phpMyAdmin.
3. Select the database named `cpanel_rewards`.
4. Confirm this is the test rewards database.
5. If there is important customer data already in the database, stop before importing. The current schema file is for fresh setup and drops/recreates rewards tables.

## Import Order

Run these files in this exact order:

1. `database/schema-draft.sql`
2. `database/seed-boudin-company.sql`

## Import `schema-draft.sql`

1. In phpMyAdmin, click the `Import` tab.
2. Choose `database/schema-draft.sql`.
3. Leave the format as SQL.
4. Click `Import` or `Go`.
5. Wait for the success message.

This file creates the table structure for:

- Business and location setup
- Customer signup and consent tracking
- Visit-based points
- Points ledger
- Coupons and redemptions by QR or short code
- Notification queue foundation
- Push subscriptions
- Audit logs

## Import `seed-boudin-company.sql`

1. Stay inside the same database: `cpanel_rewards`.
2. Click the `Import` tab again.
3. Choose `database/seed-boudin-company.sql`.
4. Click `Import` or `Go`.
5. Wait for the success message.

This file adds the starter data for The Boudin Company in Rosenberg, Texas.

## After Importing

1. Confirm these tables exist:
   - `businesses`
   - `locations`
   - `business_settings`
   - `customers`
   - `customer_consents`
   - `signup_sources`
   - `reward_rules`
   - `visits`
   - `points_ledger`
   - `coupon_templates`
   - `coupons`
   - `customer_coupons`
   - `coupon_redemptions`
   - `message_templates`
   - `notification_deliveries`
   - `push_subscriptions`
   - `audit_logs`
2. Open `http://theboudincompany.com/test/rewards`.
3. Confirm the Environment Check shows the database name.
4. Open `http://theboudincompany.com/test/rewards/admin.php`.
5. Confirm the Database Status section shows the connection as ready.

## Troubleshooting

If the app says the database config is missing, upload or create:

```text
public_html/test/rewards/_includes/config.local.php
```

If the app says the database connection is not ready:

1. Confirm the database name is `cpanel_rewards`.
2. Confirm the username is `cpanel_rewards_user`.
3. Confirm the real password is in `config.local.php`.
4. Confirm the database user has permissions on `cpanel_rewards`.
5. Confirm the MySQL host is `localhost`.

## Safety Notes

- Keep SMS in `log_only` mode until Telnyx, 10DLC, and legal approval are complete.
- Do not run `schema-draft.sql` over live production customer data unless you intentionally want to rebuild the database.
- Keep `config.local.php` off GitHub.
