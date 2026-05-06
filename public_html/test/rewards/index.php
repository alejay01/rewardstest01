<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$checks = [
    'Hosting target' => $app['deploy_path'],
    'Public test URL' => $app['base_url'],
    'SMS mode' => $app['sms_mode'],
    'PHP version' => PHP_VERSION,
    'Server time' => (new DateTimeImmutable())->format('Y-m-d H:i:s T'),
];

render_header('Status', 'Phase 1 scaffold');
?>
<section class="hero-panel">
  <div>
    <h1><?= h($app['name']) ?></h1>
    <p class="lead">
      Phase 1 is a database-free app shell for signup, wallet, staff redemption, and admin preview. It is ready for test hosting upload.
    </p>
    <div class="actions">
      <a class="button primary" href="join.php">Open Signup</a>
      <a class="button" href="redeem.php">Staff Redeem</a>
    </div>
  </div>
  <div class="hero-card" aria-label="Reward preview">
    <img src="assets/img/boudin-rewards-mark.svg" alt="" width="80" height="80">
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
</section>

<section class="panel">
  <h2>Phase 1 Routes</h2>
  <div class="card-grid">
    <a class="route-card" href="join.php">
      <strong>Customer Signup</strong>
      <span>QR/kiosk style opt-in form with consent preview.</span>
    </a>
    <a class="route-card" href="wallet.php">
      <strong>Customer Wallet</strong>
      <span>Mock points and coupon display for later database wiring.</span>
    </a>
    <a class="route-card" href="redeem.php">
      <strong>Staff Redemption</strong>
      <span>QR/manual short-code redemption preview.</span>
    </a>
    <a class="route-card" href="admin.php">
      <strong>Admin Preview</strong>
      <span>Launch gates, SMS mode, and next build tasks.</span>
    </a>
  </div>
</section>
<?php render_footer(); ?>
