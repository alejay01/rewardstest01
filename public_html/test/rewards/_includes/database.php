<?php
declare(strict_types=1);

function db_config_path(): string
{
    return __DIR__ . '/config.local.php';
}

function db_example_config_path(): string
{
    return __DIR__ . '/config.example.php';
}

function db_config_exists(): bool
{
    return is_file(db_config_path());
}

function db_example_config(): array
{
    $config = require db_example_config_path();
    return is_array($config) ? $config : [];
}

function db_local_config(): array
{
    if (!db_config_exists()) {
        return [];
    }

    try {
        $config = require db_config_path();
        return is_array($config) ? $config : [];
    } catch (Throwable $exception) {
        return [
            '_load_error' => $exception->getMessage(),
        ];
    }
}

function db_public_settings(): array
{
    $local = db_local_config();
    $example = db_example_config();
    $database = array_replace($example['database'] ?? [], $local['database'] ?? []);

    return [
        'host' => (string)($database['host'] ?? ''),
        'database' => (string)($database['database'] ?? ''),
        'username' => (string)($database['username'] ?? ''),
        'charset' => (string)($database['charset'] ?? 'utf8mb4'),
    ];
}

function db_password_is_placeholder(string $password): bool
{
    return $password === '' || strtoupper($password) === 'CHANGE_ME';
}

function db_connection(): PDO
{
    $config = db_local_config();
    if (isset($config['_load_error'])) {
        throw new RuntimeException('Database config could not be loaded: ' . $config['_load_error']);
    }

    $database = $config['database'] ?? [];

    $host = (string)($database['host'] ?? '');
    $name = (string)($database['database'] ?? '');
    $username = (string)($database['username'] ?? '');
    $password = (string)($database['password'] ?? '');
    $charset = (string)($database['charset'] ?? 'utf8mb4');

    if ($host === '' || $name === '' || $username === '' || db_password_is_placeholder($password)) {
        throw new RuntimeException('Database config is missing a real host, database, username, or password.');
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $name, $charset);

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

function db_status(): array
{
    $settings = db_public_settings();
    $status = [
        'configured' => db_config_exists(),
        'connected' => false,
        'host' => $settings['host'],
        'database' => $settings['database'],
        'username' => $settings['username'],
        'message' => 'Create _includes/config.local.php from config.example.php and add the real password on the server.',
    ];

    if (!$status['configured']) {
        return $status;
    }

    try {
        $pdo = db_connection();
        $pdo->query('SELECT 1');
        $status['connected'] = true;
        $status['message'] = 'Database connection is ready.';
    } catch (Throwable $exception) {
        $status['message'] = 'Database connection is not ready: ' . $exception->getMessage();
    }

    return $status;
}
