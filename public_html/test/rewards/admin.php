<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$dbStatus = db_status();
$databaseGate = $dbStatus['connected'] ? 'ready' : ($dbStatus['configured'] ? 'check' : 'pending');
$launchGates = [
    'MySQL database created' => $databaseGate,
    'Non-public config file' => $dbStatus['configured'] ? 'ready' : 'pending',
    'Privacy and terms URLs' => 'pending',
    'Telnyx API key' => 'deferred',
    '10DLC registration' => 'deferred',
    'SMS live send enabled' => 'blocked',
];
$manualSubmitted = $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'manual_customer_add';
$manualResult = null;
$manualFirstName = trim((string)($_POST['first_name'] ?? ''));
$manualPhone = trim((string)($_POST['phone'] ?? ''));
$manualEmail = trim((string)($_POST['email'] ?? ''));
$visitSubmitted = $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'credit_visit';
$visitResult = null;
$visitPhone = trim((string)($_POST['visit_phone'] ?? ''));
$visitNote = trim((string)($_POST['visit_note'] ?? ''));

if ($manualSubmitted) {
    $manualResult = rewards_create_customer([
        'first_name' => $manualFirstName,
        'phone' => $manualPhone,
        'email' => $manualEmail,
        'source' => 'boudin-rosenberg-kiosk',
        'source_type' => 'admin',
        'sms_consent' => isset($_POST['sms_consent']),
    ]);
}

if ($visitSubmitted) {
    $visitResult = rewards_credit_visit($visitPhone, $visitNote);
}

render_header('Admin', 'Admin preview');
?>
<section class="hero-panel compact">
  <div>
    <h1>Admin Preview</h1>
    <p class="lead">Operational dashboard shell for launch gates, campaign safety, and log-only SMS development.</p>
  </div>
  <div class="points-badge muted">
    <strong>0</strong>
    <span>live sends</span>
  </div>
</section>

<section class="panel">
  <h2>Launch Gates</h2>
  <div class="status-grid">
    <?php foreach ($launchGates as $label => $status): ?>
      <div class="status-item">
        <dt><?= h($label) ?></dt>
        <dd><span class="pill <?= h($status) ?>"><?= h($status) ?></span></dd>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="panel">
  <h2>Database Status</h2>
  <dl class="status-grid">
    <div class="status-item">
      <dt>Host</dt>
      <dd><?= h($dbStatus['host']) ?></dd>
    </div>
    <div class="status-item">
      <dt>Database</dt>
      <dd><?= h($dbStatus['database']) ?></dd>
    </div>
    <div class="status-item">
      <dt>User</dt>
      <dd><?= h($dbStatus['username']) ?></dd>
    </div>
    <div class="status-item">
      <dt>Connection</dt>
      <dd><span class="pill <?= $dbStatus['connected'] ? 'ready' : 'check' ?>"><?= h($dbStatus['connected'] ? 'ready' : 'check') ?></span></dd>
    </div>
  </dl>
  <div class="notice secondary">
    <?= h($dbStatus['message']) ?>
  </div>
</section>

<section class="panel">
  <h2>QR Signup</h2>
  <p>Generate QR codes for the counter, table tents, or kiosk. Each code routes customers to the signup form with a source tag.</p>
  <div class="form-actions">
    <a class="button primary" href="qrcode.php">Open QR Generator</a>
  </div>
</section>

<section class="panel">
  <h2>Add Customer Record</h2>
  <p>Use this for a phone-only signup or a staff-entered customer record. SMS remains log-only.</p>
  <?php if ($manualSubmitted && $manualResult !== null): ?>
    <div class="notice <?= $manualResult['ok'] ? 'success' : '' ?>">
      <?= h((string)$manualResult['message']) ?>
    </div>
  <?php endif; ?>
  <form method="post" action="admin.php" class="stack">
    <input type="hidden" name="action" value="manual_customer_add">
    <label>
      First name
      <input name="first_name" autocomplete="given-name" value="<?= h($manualFirstName) ?>" placeholder="Optional">
    </label>
    <label>
      Mobile number
      <input name="phone" inputmode="tel" autocomplete="tel" value="<?= h($manualPhone) ?>" placeholder="713-555-0100" required>
    </label>
    <label>
      Email
      <input name="email" type="email" autocomplete="email" value="<?= h($manualEmail) ?>" placeholder="Optional">
    </label>
    <label class="check-row">
      <input type="checkbox" name="sms_consent" value="1">
      <span>Customer gave permission to record SMS marketing consent for future log-only messaging. No SMS will be sent yet.</span>
    </label>
    <div class="form-actions">
      <button class="button primary" type="submit">Add Customer</button>
    </div>
  </form>
</section>

<section class="panel">
  <h2>Credit Visit Points</h2>
  <p>Use this after a staff-confirmed visit. The current pilot rule credits 10 points per visit.</p>
  <?php if ($visitSubmitted && $visitResult !== null): ?>
    <div class="notice <?= $visitResult['ok'] ? 'success' : '' ?>">
      <?= h((string)$visitResult['message']) ?>
      <?php if ($visitResult['ok'] && isset($visitResult['phone'])): ?>
        <div class="form-actions">
          <a class="button" href="wallet.php?phone=<?= h(urlencode((string)$visitResult['phone'])) ?>">Open Wallet</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <form method="post" action="admin.php" class="stack">
    <input type="hidden" name="action" value="credit_visit">
    <label>
      Customer mobile number
      <input name="visit_phone" inputmode="tel" autocomplete="tel" value="<?= h($visitPhone) ?>" placeholder="713-555-0100" required>
    </label>
    <label>
      Visit note
      <input name="visit_note" value="<?= h($visitNote) ?>" placeholder="Optional register, receipt, or staff note">
    </label>
    <div class="form-actions">
      <button class="button primary" type="submit">Credit Visit</button>
    </div>
  </form>
</section>

<section class="panel">
  <h2>Campaign Safety</h2>
  <p>Campaign creation can be built now, but outbound SMS stays in <code><?= h($app['sms_mode']) ?></code> mode until launch gates clear.</p>
  <div class="notice">
    Marketing SMS must remain blocked until Telnyx, 10DLC, privacy policy, rewards terms, and owner/legal approval are complete.
  </div>
</section>
<?php render_footer(); ?>
