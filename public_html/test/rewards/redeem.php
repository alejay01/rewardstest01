<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$code = strtoupper(trim((string)($_POST['code'] ?? '')));
$method = (string)($_POST['method'] ?? 'short_code');

render_header('Redeem', 'Staff tool');
?>
<section class="form-layout">
  <div class="panel">
    <h1>Redeem Coupon</h1>
    <p class="lead">Staff can test QR or short-code redemption without touching a database.</p>

    <form method="post" class="stack">
      <fieldset class="segmented">
        <legend>Method</legend>
        <label><input type="radio" name="method" value="qr"<?= $method === 'qr' ? ' checked' : '' ?>> QR scan</label>
        <label><input type="radio" name="method" value="short_code"<?= $method !== 'qr' ? ' checked' : '' ?>> Short code</label>
      </fieldset>

      <label>
        Coupon code
        <input name="code" value="<?= h($code) ?>" placeholder="BC100PT" required>
      </label>

      <button class="button primary" type="submit">Preview Redemption</button>
    </form>
  </div>

  <aside class="panel side-panel">
    <h2>Staff Notes</h2>
    <p>This screen will later check coupon status, expiration, customer, location, and usage limit.</p>
    <p>Current mode: <code>preview_only</code></p>
  </aside>
</section>

<?php if ($submitted): ?>
  <section class="panel result-panel">
    <h2>Redemption Preview</h2>
    <dl class="status-grid">
      <div class="status-item"><dt>Code</dt><dd><?= h($code) ?></dd></div>
      <div class="status-item"><dt>Method</dt><dd><?= h($method) ?></dd></div>
      <div class="status-item"><dt>Status</dt><dd>Ready for database validation</dd></div>
      <div class="status-item"><dt>Audit action</dt><dd>Will record staff, location, timestamp, and result</dd></div>
    </dl>
  </section>
<?php endif; ?>
<?php render_footer(); ?>
