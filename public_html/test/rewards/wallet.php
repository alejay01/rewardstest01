<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$phone = trim((string)($_GET['phone'] ?? $_POST['phone'] ?? ''));
$customer = null;
$activity = [];
$lookupMessage = '';
$pointsBalance = 0;
$progressPercent = 0;
$pointsToReward = 100;

if ($phone !== '') {
    try {
        $pdo = db_connection();
        $customer = rewards_customer_by_phone($pdo, $phone);

        if ($customer !== null) {
            $activity = rewards_recent_points($pdo, (int)$customer['id']);
            $pointsBalance = (int)$customer['points_balance'];
            $progressPercent = min(100, max(0, $pointsBalance));
            $pointsToReward = max(0, 100 - $pointsBalance);
        } else {
            $lookupMessage = 'No customer was found for that phone number yet.';
        }
    } catch (Throwable $exception) {
        $lookupMessage = 'Wallet lookup is not ready: ' . $exception->getMessage();
    }
}

render_header('Wallet', 'Customer wallet');
?>
<section class="hero-panel compact">
  <div>
    <h1>Reward Wallet</h1>
    <p class="lead">Look up a customer by phone number to view real points and recent visit activity.</p>
  </div>
  <div class="points-badge">
    <strong><?= h((string)$pointsBalance) ?></strong>
    <span><?= $customer !== null ? 'points' : 'lookup' ?></span>
  </div>
</section>

<section class="panel">
  <h2>Find Wallet</h2>
  <form method="get" action="wallet.php" class="actions">
    <input name="phone" inputmode="tel" autocomplete="tel" value="<?= h($phone) ?>" placeholder="713-555-0100" required>
    <button class="button primary" type="submit">Find Wallet</button>
  </form>
  <?php if ($lookupMessage !== ''): ?>
    <div class="notice"><?= h($lookupMessage) ?></div>
  <?php endif; ?>
</section>

<?php if ($customer !== null): ?>
  <section class="card-grid">
    <article class="coupon-card">
      <span class="coupon-type">Customer</span>
      <h2><?= h((string)($customer['first_name'] ?: 'Rewards Member')) ?></h2>
      <p><?= h((string)$customer['phone_e164']) ?></p>
      <p><?= h((string)($customer['email'] ?: 'No email on file')) ?></p>
      <div class="coupon-code"><?= h((string)$pointsBalance) ?> pts</div>
    </article>
    <article class="coupon-card">
      <span class="coupon-type">Visit points</span>
      <h2><?= h((string)$pointsToReward) ?> points to next reward</h2>
      <p>At 10 points per visit, 100 points unlocks the pilot reward.</p>
      <div class="progress"><span style="width:<?= h((string)$progressPercent) ?>%"></span></div>
    </article>
  </section>

  <section class="panel">
    <h2>Recent Activity</h2>
    <?php if ($activity === []): ?>
      <p>No point activity yet. Credit a staff-confirmed visit from Admin.</p>
    <?php else: ?>
      <dl class="status-grid">
        <?php foreach ($activity as $item): ?>
          <div class="status-item">
            <dt><?= h((string)$item['created_at']) ?></dt>
            <dd><?= h((string)$item['points_delta']) ?> pts, <?= h((string)$item['reason']) ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>
    <?php endif; ?>
  </section>
<?php endif; ?>
<?php render_footer(); ?>
