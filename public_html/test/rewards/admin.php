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
  <h2>Campaign Safety</h2>
  <p>Campaign creation can be built now, but outbound SMS stays in <code><?= h($app['sms_mode']) ?></code> mode until launch gates clear.</p>
  <div class="notice">
    Marketing SMS must remain blocked until Telnyx, 10DLC, privacy policy, rewards terms, and owner/legal approval are complete.
  </div>
</section>
<?php render_footer(); ?>
