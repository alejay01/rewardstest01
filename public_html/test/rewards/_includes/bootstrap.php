<?php
declare(strict_types=1);

date_default_timezone_set('America/Chicago');

$app = [
    'name' => 'The Boudin Company Rewards',
    'restaurant' => 'The Boudin Company',
    'location' => 'Rosenberg',
    'phone' => '713-561-5645',
    'address' => '28115 Southwest Freeway, Rosenberg, TX 77461',
    'base_url' => 'http://theboudincompany.com/test/rewards',
    'deploy_path' => 'public_html/test/rewards',
    'sms_mode' => 'log_only',
    'policy_version' => 'boudin-rewards-2026-05-06-v1',
];

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function current_page(): string
{
    return basename((string)($_SERVER['SCRIPT_NAME'] ?? 'index.php'));
}

function page_url(string $path = ''): string
{
    global $app;
    return rtrim($app['base_url'], '/') . ($path === '' ? '' : '/' . ltrim($path, '/'));
}

function nav_items(): array
{
    return [
        'index.php' => 'Status',
        'join.php' => 'Join',
        'wallet.php' => 'Wallet',
        'redeem.php' => 'Redeem',
        'admin.php' => 'Admin',
    ];
}

function render_header(string $title, string $section = ''): void
{
    global $app;
    $page = current_page();
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <meta name="theme-color" content="#1d6b63">
  <title><?= h($title . ' | ' . $app['name']) ?></title>
  <link rel="manifest" href="manifest.json">
  <link rel="icon" href="assets/img/boudin-rewards-mark.svg" type="image/svg+xml">
  <link rel="stylesheet" href="assets/css/app.css">
  <script src="assets/js/app.js" defer></script>
</head>
<body>
  <header class="site-header">
    <a class="brand" href="index.php" aria-label="<?= h($app['name']) ?>">
      <img src="assets/img/boudin-rewards-mark.svg" alt="" width="40" height="40">
      <span>
        <strong><?= h($app['restaurant']) ?></strong>
        <small>Rewards test app</small>
      </span>
    </a>
    <nav class="nav" aria-label="Primary">
      <?php foreach (nav_items() as $href => $label): ?>
        <a href="<?= h($href) ?>"<?= $page === $href ? ' aria-current="page"' : '' ?>><?= h($label) ?></a>
      <?php endforeach; ?>
    </nav>
  </header>
  <main class="shell">
    <?php if ($section !== ''): ?>
      <div class="eyebrow"><?= h($section) ?></div>
    <?php endif; ?>
    <?php
}

function render_footer(): void
{
    global $app;
    ?>
  </main>
  <footer class="site-footer">
    <span><?= h($app['address']) ?></span>
    <span><?= h($app['phone']) ?></span>
    <span>SMS mode: <strong><?= h($app['sms_mode']) ?></strong></span>
  </footer>
</body>
</html>
    <?php
}

function mock_coupon_code(string $seed): string
{
    $hash = strtoupper(substr(hash('sha256', $seed), 0, 8));
    return 'BC' . substr($hash, 0, 6);
}
