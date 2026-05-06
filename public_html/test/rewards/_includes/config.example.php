<?php
declare(strict_types=1);

return [
    'app' => [
        'environment' => 'production',
        'url' => 'http://theboudincompany.com/test/rewards',
        'timezone' => 'America/Chicago',
    ],
    'database' => [
        'host' => 'localhost',
        'database' => 'cpanel_rewards',
        'username' => 'cpanel_rewards_user',
        'password' => 'CHANGE_ME',
        'charset' => 'utf8mb4',
    ],
    'sms' => [
        'provider' => 'telnyx',
        'mode' => 'log_only',
        'live_send_enabled' => false,
        'telnyx_api_key' => '',
        'telnyx_messaging_profile_id' => '',
        'telnyx_sender_number' => '',
        'telnyx_10dlc_status' => 'pending',
    ],
];
