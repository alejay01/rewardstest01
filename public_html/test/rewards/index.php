<?php
declare(strict_types=1);

$appName = 'The Boudin Company Rewards';
$appUrl = 'http://theboudincompany.com/test/rewards';
$deployPath = 'public_html/test/rewards';
$smsMode = 'log_only';
$phpVersion = PHP_VERSION;
$serverTime = (new DateTimeImmutable('now', new DateTimeZone('America/Chicago')))->format('Y-m-d H:i:s T');

$checks = [
    'Hosting target' => $deployPath,
    'Public test URL' => $appUrl,
    'SMS mode' => $smsMode,
    'PHP version' => $phpVersion,
    'Server time' => $serverTime,
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
  <main class="shell">
    <section class="panel">
      <div class="eyebrow">Test deployment</div>
      <h1><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="lead">
        The rewards app scaffold is installed at the test hosting path. Live SMS is blocked while Telnyx and 10DLC setup are pending.
      </p>

      <dl class="status-grid">
        <?php foreach ($checks as $label => $value): ?>
          <div class="status-item">
            <dt><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></dt>
            <dd><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>

      <div class="notice">
        <strong>Safe mode:</strong> SMS should remain in log-only mode until legal pages, Telnyx credentials, sender registration, and internal test sends are complete.
      </div>
    </section>

    <section class="tasks" aria-labelledby="next-steps">
      <h2 id="next-steps">Next Setup Steps</h2>
      <ol>
        <li>Enable SSL and switch the app URL to HTTPS.</li>
        <li>Create the MySQL database and user in cPanel.</li>
        <li>Import the schema and Boudin Company seed data.</li>
        <li>Create a non-public config file for database credentials.</li>
        <li>Confirm <code>sms.mode</code> remains <code>log_only</code>.</li>
      </ol>
    </section>
  </main>
</body>
</html>
