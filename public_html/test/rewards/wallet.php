<?php
declare(strict_types=1);
require __DIR__ . '/_includes/bootstrap.php';

render_header('Wallet', 'Customer wallet');
?>
<section class="hero-panel compact">
  <div>
    <h1>Reward Wallet</h1>
    <p class="lead">Mock customer view for points, coupons, and visit progress. Database-backed balances come later.</p>
  </div>
  <div class="points-badge">
    <strong>35</strong>
    <span>mock points</span>
  </div>
</section>

<section class="card-grid">
  <article class="coupon-card">
    <span class="coupon-type">Welcome reward</span>
    <h2>Signup offer ready</h2>
    <p>Show this mock coupon to staff during testing.</p>
    <div class="coupon-code">BC100PT</div>
  </article>
  <article class="coupon-card">
    <span class="coupon-type">Visit points</span>
    <h2>7 visits to next reward</h2>
    <p>At 10 points per visit, 100 points unlocks the pilot reward.</p>
    <div class="progress"><span style="width:35%"></span></div>
  </article>
</section>
<?php render_footer(); ?>
