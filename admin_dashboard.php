<?php
require 'admin_guard.php';
include 'db.php';

// Stats
$total_users        = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM users"))['c'];
$total_products     = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products"))['c'];
$available_products = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE status='Available'"))['c'];
$sold_products      = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE status='Sold'"))['c'];
$total_transactions = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM transactions"))['c'];

$rev_res = mysqli_query($conn,
    "SELECT SUM(p.price) AS total FROM transactions t JOIN products p ON t.product_id=p.id");
$revenue = mysqli_fetch_assoc($rev_res)['total'] ?? 0;

// Recent 5 transactions — uses purchase_date (your real column)
$recent_trans = mysqli_query($conn,
  "SELECT t.id, t.purchase_date, p.title, p.price, u.full_name AS buyer
   FROM transactions t
   JOIN products p ON t.product_id=p.id
   JOIN users u    ON t.buyer_id=u.id
   ORDER BY t.id DESC LIMIT 5"
);

// Recent 5 users
$recent_users = mysqli_query($conn,
  "SELECT id, full_name, student_id FROM users ORDER BY id DESC LIMIT 5"
);
?>
<?php include 'admin_header.php'; ?>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon gold"><i class="fa-solid fa-users"></i></div>
      <div><div class="stat-value"><?php echo $total_users; ?></div><div class="stat-label">Total Users</div></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card maroon">
      <div class="stat-icon maroon"><i class="fa-solid fa-box-open"></i></div>
      <div><div class="stat-value"><?php echo $total_products; ?></div><div class="stat-label">Total Listings</div></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card green">
      <div class="stat-icon green"><i class="fa-solid fa-store"></i></div>
      <div><div class="stat-value"><?php echo $available_products; ?></div><div class="stat-label">Available</div></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card blue">
      <div class="stat-icon blue"><i class="fa-solid fa-receipt"></i></div>
      <div><div class="stat-value"><?php echo $total_transactions; ?></div><div class="stat-label">Transactions</div></div>
    </div>
  </div>
</div>

<!-- REVENUE BANNER -->
<div class="mb-4 p-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
     style="background:linear-gradient(135deg,var(--tip-black),#2a0d17);border-left:5px solid var(--tip-gold);">
  <div>
    <div style="color:rgba(255,255,255,0.5);font-size:0.78rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;">
      Total Marketplace Revenue
    </div>
    <div style="font-family:'Bebas Neue',sans-serif;font-size:2.6rem;color:var(--tip-gold);letter-spacing:1px;line-height:1.1;">
      ₱<?php echo number_format($revenue, 2); ?>
    </div>
    <div style="color:rgba(255,255,255,0.4);font-size:0.8rem;">
      <?php echo $sold_products; ?> items sold · <?php echo $total_transactions; ?> transactions
    </div>
  </div>
  <i class="fa-solid fa-peso-sign" style="font-size:3.5rem;color:rgba(245,196,0,0.12);"></i>
</div>

<!-- TABLES -->
<div class="row g-4">

  <div class="col-lg-7">
    <div class="admin-table-card">
      <div class="table-header">
        <h5><i class="fa-solid fa-receipt me-2" style="color:var(--tip-maroon)"></i>Recent Transactions</h5>
        <a href="admin_transactions.php" class="btn-action btn-view">View All</a>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>#</th><th>Item</th><th>Buyer</th><th>Price</th><th>Date</th></tr></thead>
          <tbody>
            <?php if (mysqli_num_rows($recent_trans) === 0): ?>
              <tr><td colspan="5" class="text-center text-muted py-4">No transactions yet</td></tr>
            <?php else: ?>
              <?php while ($t = mysqli_fetch_assoc($recent_trans)): ?>
              <tr>
                <td><span style="color:#aaa;font-size:0.8rem;">#<?php echo $t['id']; ?></span></td>
                <td><strong><?php echo htmlspecialchars($t['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($t['buyer']); ?></td>
                <td><span style="color:var(--tip-maroon);font-weight:800;">₱<?php echo number_format($t['price'],2); ?></span></td>
                <td style="color:#aaa;font-size:0.8rem;">
                  <?php echo !empty($t['purchase_date']) ? date('M d', strtotime($t['purchase_date'])) : '—'; ?>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="admin-table-card">
      <div class="table-header">
        <h5><i class="fa-solid fa-users me-2" style="color:var(--tip-maroon)"></i>New Users</h5>
        <a href="admin_users.php" class="btn-action btn-view">View All</a>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>Name</th><th>Student ID</th></tr></thead>
          <tbody>
            <?php if (mysqli_num_rows($recent_users) === 0): ?>
              <tr><td colspan="2" class="text-center text-muted py-4">No users yet</td></tr>
            <?php else: ?>
              <?php while ($u = mysqli_fetch_assoc($recent_users)): ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div style="width:30px;height:30px;border-radius:50%;background:var(--tip-black);
                                color:var(--tip-gold);display:flex;align-items:center;justify-content:center;
                                font-size:0.75rem;font-weight:800;flex-shrink:0;">
                      <?php echo strtoupper(substr($u['full_name'],0,1)); ?>
                    </div>
                    <span style="font-weight:700;font-size:0.85rem;"><?php echo htmlspecialchars($u['full_name']); ?></span>
                  </div>
                </td>
                <td style="color:#888;font-size:0.82rem;"><?php echo htmlspecialchars($u['student_id']); ?></td>
              </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php include 'admin_footer.php'; ?>
