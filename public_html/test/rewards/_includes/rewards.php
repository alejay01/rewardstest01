<?php
declare(strict_types=1);

function normalize_phone_e164(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';

    if (strlen($digits) === 10) {
        return '+1' . $digits;
    }

    if (strlen($digits) === 11 && str_starts_with($digits, '1')) {
        return '+' . $digits;
    }

    return $phone;
}

function rewards_business(PDO $pdo): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM businesses WHERE name = :name ORDER BY id LIMIT 1');
    $stmt->execute(['name' => 'The Boudin Company']);
    $business = $stmt->fetch();

    return is_array($business) ? $business : null;
}

function rewards_location(PDO $pdo, int $businessId): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM locations WHERE business_id = :business_id AND is_active = 1 ORDER BY id LIMIT 1');
    $stmt->execute(['business_id' => $businessId]);
    $location = $stmt->fetch();

    return is_array($location) ? $location : null;
}

function rewards_signup_source(PDO $pdo, int $businessId, string $sourceCode): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM signup_sources WHERE business_id = :business_id AND code = :code ORDER BY id LIMIT 1');
    $stmt->execute([
        'business_id' => $businessId,
        'code' => $sourceCode,
    ]);
    $source = $stmt->fetch();

    return is_array($source) ? $source : null;
}

function rewards_customer_by_phone(PDO $pdo, string $phone): ?array
{
    $normalizedPhone = normalize_phone_e164($phone);
    if ($normalizedPhone === '') {
        return null;
    }

    $business = rewards_business($pdo);
    if ($business === null) {
        return null;
    }

    $stmt = $pdo->prepare(
        'SELECT c.*, l.name AS location_name
         FROM customers c
         LEFT JOIN locations l ON l.id = c.primary_location_id
         WHERE c.business_id = :business_id AND c.phone_e164 = :phone
         ORDER BY c.id
         LIMIT 1'
    );
    $stmt->execute([
        'business_id' => (int)$business['id'],
        'phone' => $normalizedPhone,
    ]);
    $customer = $stmt->fetch();

    return is_array($customer) ? $customer : null;
}

function rewards_recent_points(PDO $pdo, int $customerId, int $limit = 6): array
{
    $stmt = $pdo->prepare(
        'SELECT points_delta, balance_after, reason, note, created_at
         FROM points_ledger
         WHERE customer_id = :customer_id
         ORDER BY created_at DESC, id DESC
         LIMIT ' . max(1, min(20, $limit))
    );
    $stmt->execute(['customer_id' => $customerId]);

    return $stmt->fetchAll();
}

function rewards_visit_rule(PDO $pdo, int $businessId, ?int $locationId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT *
         FROM reward_rules
         WHERE business_id = :business_id
           AND rule_type = \'visit\'
           AND is_active = 1
           AND (location_id IS NULL OR location_id = :location_id)
           AND (starts_at IS NULL OR starts_at <= CURRENT_TIMESTAMP)
           AND (ends_at IS NULL OR ends_at >= CURRENT_TIMESTAMP)
         ORDER BY location_id DESC, id
         LIMIT 1'
    );
    $stmt->execute([
        'business_id' => $businessId,
        'location_id' => $locationId,
    ]);
    $rule = $stmt->fetch();

    return is_array($rule) ? $rule : null;
}

function rewards_credit_visit(string $phone, string $note = ''): array
{
    $normalizedPhone = normalize_phone_e164($phone);
    if ($normalizedPhone === '') {
        return [
            'ok' => false,
            'message' => 'Phone number is required.',
        ];
    }

    try {
        $pdo = db_connection();
        $customer = rewards_customer_by_phone($pdo, $normalizedPhone);

        if ($customer === null) {
            return [
                'ok' => false,
                'message' => 'Customer was not found. Add the customer record first.',
            ];
        }

        $businessId = (int)$customer['business_id'];
        $locationId = $customer['primary_location_id'] !== null ? (int)$customer['primary_location_id'] : null;
        if ($locationId === null) {
            $location = rewards_location($pdo, $businessId);
            $locationId = $location !== null ? (int)$location['id'] : null;
        }

        if ($locationId === null) {
            return [
                'ok' => false,
                'message' => 'No active location was found for visit credit.',
            ];
        }

        $rule = rewards_visit_rule($pdo, $businessId, $locationId);
        $points = $rule !== null ? (int)$rule['points_value'] : 10;
        $ruleId = $rule !== null ? (int)$rule['id'] : null;

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO visits (
                customer_id,
                location_id,
                reward_rule_id,
                visit_date,
                points_awarded,
                verification_method,
                status,
                note
            ) VALUES (
                :customer_id,
                :location_id,
                :reward_rule_id,
                CURRENT_DATE,
                :points_awarded,
                \'staff_confirmed\',
                \'approved\',
                NULLIF(:note, \'\')
            )'
        );
        $stmt->execute([
            'customer_id' => (int)$customer['id'],
            'location_id' => $locationId,
            'reward_rule_id' => $ruleId,
            'points_awarded' => $points,
            'note' => $note,
        ]);
        $visitId = (int)$pdo->lastInsertId();

        $newBalance = (int)$customer['points_balance'] + $points;

        $stmt = $pdo->prepare(
            'UPDATE customers
             SET points_balance = :points_balance,
                 last_visit_at = CURRENT_TIMESTAMP,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :customer_id'
        );
        $stmt->execute([
            'points_balance' => $newBalance,
            'customer_id' => (int)$customer['id'],
        ]);

        $stmt = $pdo->prepare(
            'INSERT INTO points_ledger (
                customer_id,
                location_id,
                reward_rule_id,
                visit_id,
                points_delta,
                balance_after,
                reason,
                reference_type,
                reference_id,
                note
            ) VALUES (
                :customer_id,
                :location_id,
                :reward_rule_id,
                :visit_id,
                :points_delta,
                :balance_after,
                \'visit\',
                \'visits\',
                :reference_id,
                NULLIF(:note, \'\')
            )'
        );
        $stmt->execute([
            'customer_id' => (int)$customer['id'],
            'location_id' => $locationId,
            'reward_rule_id' => $ruleId,
            'visit_id' => $visitId,
            'points_delta' => $points,
            'balance_after' => $newBalance,
            'reference_id' => $visitId,
            'note' => $note,
        ]);

        $pdo->commit();

        return [
            'ok' => true,
            'message' => 'Visit credited. ' . $points . ' points added.',
            'phone' => $normalizedPhone,
            'points_added' => $points,
            'points_balance' => $newBalance,
            'visit_id' => $visitId,
        ];
    } catch (Throwable $exception) {
        if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'ok' => false,
            'message' => 'Visit was not credited: ' . $exception->getMessage(),
        ];
    }
}

function rewards_create_customer(array $input): array
{
    global $app;

    $firstName = trim((string)($input['first_name'] ?? ''));
    $phone = normalize_phone_e164(trim((string)($input['phone'] ?? '')));
    $email = trim((string)($input['email'] ?? ''));
    $sourceCode = trim((string)($input['source'] ?? 'boudin-rosenberg-qr-counter'));
    $smsConsent = (bool)($input['sms_consent'] ?? false);
    $sourceType = (string)($input['source_type'] ?? 'web');

    if ($phone === '') {
        return [
            'ok' => false,
            'message' => 'Phone number is required.',
        ];
    }

    try {
        $pdo = db_connection();
        $business = rewards_business($pdo);

        if ($business === null) {
            return [
                'ok' => false,
                'message' => 'Business seed data was not found. Import seed-boudin-company.sql.',
            ];
        }

        $businessId = (int)$business['id'];
        $source = rewards_signup_source($pdo, $businessId, $sourceCode);
        $location = $source !== null && $source['location_id'] !== null
            ? ['id' => (int)$source['location_id']]
            : rewards_location($pdo, $businessId);
        $locationId = $location !== null ? (int)$location['id'] : null;
        $sourceId = $source !== null ? (int)$source['id'] : null;

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO customers (
                business_id,
                primary_location_id,
                signup_source_id,
                phone_e164,
                email,
                first_name,
                status
            ) VALUES (
                :business_id,
                :location_id,
                :signup_source_id,
                :phone,
                NULLIF(:email, \'\'),
                NULLIF(:first_name, \'\'),
                \'active\'
            )
            ON DUPLICATE KEY UPDATE
                primary_location_id = COALESCE(VALUES(primary_location_id), primary_location_id),
                signup_source_id = COALESCE(VALUES(signup_source_id), signup_source_id),
                email = COALESCE(VALUES(email), email),
                first_name = COALESCE(VALUES(first_name), first_name),
                status = IF(status = \'deleted\', \'active\', status),
                updated_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([
            'business_id' => $businessId,
            'location_id' => $locationId,
            'signup_source_id' => $sourceId,
            'phone' => $phone,
            'email' => $email,
            'first_name' => $firstName,
        ]);

        $stmt = $pdo->prepare('SELECT * FROM customers WHERE business_id = :business_id AND phone_e164 = :phone ORDER BY id LIMIT 1');
        $stmt->execute([
            'business_id' => $businessId,
            'phone' => $phone,
        ]);
        $customer = $stmt->fetch();

        if (!is_array($customer)) {
            throw new RuntimeException('Customer record could not be loaded after save.');
        }

        if ($smsConsent) {
            $stmt = $pdo->prepare(
                'INSERT INTO customer_consents (
                    customer_id,
                    channel,
                    status,
                    source,
                    disclosure_text,
                    policy_version,
                    ip_address,
                    user_agent,
                    location_id
                ) VALUES (
                    :customer_id,
                    \'sms\',
                    \'opted_in\',
                    :source_type,
                    :disclosure_text,
                    :policy_version,
                    :ip_address,
                    :user_agent,
                    :location_id
                )'
            );
            $stmt->execute([
                'customer_id' => (int)$customer['id'],
                'source_type' => $sourceType,
                'disclosure_text' => 'Customer agreed to receive recurring automated marketing text messages from The Boudin Company Rewards. Message frequency varies, up to 4 messages per month. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help.',
                'policy_version' => $app['policy_version'],
                'ip_address' => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
                'user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
                'location_id' => $locationId,
            ]);
        }

        $pdo->commit();

        return [
            'ok' => true,
            'message' => 'Customer record saved. SMS remains log-only; no text message was sent.',
            'customer' => $customer,
            'phone' => $phone,
            'source_found' => $source !== null,
        ];
    } catch (Throwable $exception) {
        if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'ok' => false,
            'message' => 'Customer record was not saved: ' . $exception->getMessage(),
        ];
    }
}
