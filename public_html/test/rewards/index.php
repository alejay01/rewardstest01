<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$dbStatus = db_status();
$checks = [
    'Hosting target' => $app['deploy_path'],
    'Public test URL' => $app['base_url'],
    'SMS mode' => $app['sms_mode'],
    'Database config' => $dbStatus['configured'] ? 'Found' : 'Not created yet',
    'Database connection' => $dbStatus['connected'] ? 'Connected' : 'Not connected',
    'Database name' => $dbStatus['database'],
    'PHP version' => PHP_VERSION,
    'Server time' => (new DateTimeImmutable())->format('Y-m-d H:i:s T'),
];

render_header('Status', 'Rewards status');
?>
<section class="hero-panel">
  <div>
    <h1><?= h($app['name']) ?></h1>
    <p class="lead">
      The rewards test app is connected to MySQL for signup, wallet lookup, QR signup, and staff-confirmed visit points. SMS remains log-only until Telnyx is ready.
    </p>
    <div class="actions">
      <a class="button primary" href="join.php">Open Signup</a>
      <a class="button" href="redeem.php">Staff Redeem</a>
    </div>
  </div>
  <div class="hero-card" aria-label="Reward preview">
    <img src="assets/img/boudin-logo-icon.svg" alt="" width="80" height="80">
    <strong>10 points</strong>
    <span>per staff-confirmed visit</span>
  </div>
</section>

<section class="panel">
  <h2>Environment Check</h2>
  <dl class="status-grid">
    <?php foreach ($checks as $label => $value): ?>
      <div class="status-item">
        <dt><?= h($label) ?></dt>
        <dd><?= h($value) ?></dd>
      </div>
    <?php endforeach; ?>
  </dl>
  <div class="notice">
    <strong>Safe mode:</strong> SMS remains log-only until legal pages, Telnyx credentials, sender registration, and internal test sends are complete.
  </div>
  <div class="notice secondary">
    <strong>Database:</strong> <?= h($dbStatus['message']) ?>
  </div>
</section>

<section class="panel">
  <h2>App Routes</h2>
  <div class="card-grid">
    <a class="route-card" href="join.php">
      <strong>Customer Signup</strong>
      <span>QR/kiosk style signup form with optional SMS consent capture.</span>
    </a>
    <a class="route-card" href="wallet.php">
      <strong>Customer Wallet</strong>
      <span>Phone lookup for real points balance and recent visit activity.</span>
    </a>
    <a class="route-card" href="redeem.php">
      <strong>Staff Redemption</strong>
      <span>QR/manual short-code redemption preview.</span>
    </a>
    <a class="route-card" href="admin.php">
      <strong>Admin Preview</strong>
      <span>Launch gates, manual customer add, QR link, and visit point crediting.</span>
    </a>
  </div>
</section>
<?php render_footer(); ?>
