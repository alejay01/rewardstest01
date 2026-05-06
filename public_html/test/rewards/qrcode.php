<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$sourceOptions = [
    'boudin-rosenberg-qr-counter' => 'Counter QR',
    'boudin-rosenberg-qr-table' => 'Table QR',
    'boudin-rosenberg-kiosk' => 'Kiosk QR',
];
$selectedSource = trim((string)($_GET['source'] ?? 'boudin-rosenberg-qr-counter'));
if (!array_key_exists($selectedSource, $sourceOptions)) {
    $selectedSource = 'boudin-rosenberg-qr-counter';
}

$signupUrl = page_url('join.php?source=' . rawurlencode($selectedSource));
$qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=12&data=' . rawurlencode($signupUrl);

render_header('QR Code', 'Signup QR generator');
?>
<section class="hero-panel compact">
  <div>
    <h1>Signup QR Code</h1>
    <p class="lead">Generate a scannable signup code for the counter, table tents, kiosk, or printed material.</p>
  </div>
  <div class="points-badge muted">
    <strong>QR</strong>
    <span>signup source</span>
  </div>
</section>

<section class="form-layout">
  <div class="panel">
    <h2>Generate Code</h2>
    <form method="get" action="qrcode.php" class="stack">
      <fieldset class="segmented">
        <legend>Signup source</legend>
        <?php foreach ($sourceOptions as $sourceCode => $label): ?>
          <label>
            <input type="radio" name="source" value="<?= h($sourceCode) ?>"<?= $selectedSource === $sourceCode ? ' checked' : '' ?>>
            <?= h($label) ?>
          </label>
        <?php endforeach; ?>
      </fieldset>
      <div class="form-actions">
        <button class="button primary" type="submit">Generate QR</button>
        <a class="button" href="<?= h($signupUrl) ?>">Open Signup Link</a>
      </div>
    </form>
  </div>

  <aside class="panel side-panel qr-preview">
    <h2><?= h($sourceOptions[$selectedSource]) ?></h2>
    <img src="<?= h($qrImageUrl) ?>" alt="Signup QR code for <?= h($sourceOptions[$selectedSource]) ?>" width="320" height="320">
    <p><code><?= h($selectedSource) ?></code></p>
    <p class="small-text"><?= h($signupUrl) ?></p>
  </aside>
</section>
<?php render_footer(); ?>
