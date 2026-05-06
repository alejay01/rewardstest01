<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$firstName = trim((string)($_POST['first_name'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$source = trim((string)($_GET['source'] ?? $_POST['source'] ?? 'boudin-rosenberg-qr-counter'));
$smsConsent = isset($_POST['sms_consent']);
$mockCode = $submitted ? mock_coupon_code($phone . $email . $source) : 'BC100PT';
$saveResult = null;

if ($submitted) {
    $saveResult = rewards_create_customer([
        'first_name' => $firstName,
        'phone' => $phone,
        'email' => $email,
        'source' => $source,
        'source_type' => 'qr',
        'sms_consent' => $smsConsent,
    ]);
}

render_header('Join Rewards', 'Customer signup');
?>
<section class="form-layout">
  <div class="panel">
    <h1>Join <?= h($app['restaurant']) ?> Rewards</h1>
    <p class="lead">Earn points by visit, receive coupons, and keep rewards ready on your phone.</p>

    <form method="post" action="join.php?source=<?= h($source) ?>" class="stack" data-preview-form>
      <input type="hidden" name="source" value="<?= h($source) ?>">

      <label>
        First name
        <input name="first_name" autocomplete="given-name" value="<?= h($firstName) ?>" placeholder="First name">
      </label>

      <label>
        Mobile number
        <input name="phone" inputmode="tel" autocomplete="tel" value="<?= h($phone) ?>" placeholder="713-555-0100" required>
      </label>

      <label>
        Email
        <input name="email" type="email" autocomplete="email" value="<?= h($email) ?>" placeholder="name@example.com">
      </label>

      <label class="check-row">
        <input type="checkbox" name="sms_consent" value="1"<?= $smsConsent ? ' checked' : '' ?>>
        <span>Yes, I agree to receive recurring automated marketing text messages from The Boudin Company Rewards at the mobile number I provide. Message frequency varies, up to 4 messages per month. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to opt out and HELP for help. I agree to the Rewards Terms and Privacy Policy.</span>
      </label>

      <div class="form-actions">
        <button class="button primary" type="submit">Join Rewards</button>
        <a class="button" href="wallet.php">View Wallet Mock</a>
      </div>
    </form>
  </div>

  <aside class="panel side-panel">
    <h2>Signup Source</h2>
    <p><code><?= h($source) ?></code></p>
    <h2>Reward Rule</h2>
    <p>10 points per staff-confirmed visit.</p>
    <h2>SMS Status</h2>
    <p><code><?= h($app['sms_mode']) ?></code></p>
  </aside>
</section>

<?php if ($submitted): ?>
  <section class="panel result-panel" tabindex="-1">
    <h2>Signup Result</h2>
    <div class="notice <?= $saveResult !== null && $saveResult['ok'] ? 'success' : '' ?>">
      <?= h((string)($saveResult['message'] ?? 'Signup was submitted.')) ?>
    </div>
    <dl class="status-grid">
      <div class="status-item"><dt>Name</dt><dd><?= h($firstName ?: 'Not provided') ?></dd></div>
      <div class="status-item"><dt>Phone</dt><dd><?= h((string)($saveResult['phone'] ?? $phone)) ?></dd></div>
      <div class="status-item"><dt>Email</dt><dd><?= h($email ?: 'Not provided') ?></dd></div>
      <div class="status-item"><dt>SMS consent</dt><dd><?= $smsConsent ? 'Recorded for later log-only confirmation' : 'Not opted in' ?></dd></div>
      <div class="status-item"><dt>Mock coupon</dt><dd><?= h($mockCode) ?></dd></div>
      <div class="status-item"><dt>Policy version</dt><dd><?= h($app['policy_version']) ?></dd></div>
    </dl>
  </section>
<?php endif; ?>
<?php render_footer(); ?>
