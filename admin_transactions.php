<?php
require 'admin_guard.php';
include 'db.php';

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, trim($_GET['q'])) : '';
$where  = $search
  ? "WHERE p.title LIKE '%$search%' OR buyer.full_name LIKE '%$search%' OR seller.full_name LIKE '%$search%'"
  : '';

<<<<<<< HEAD
// Uses purchase_date — your real column name
=======
// transactions table uses purchase_date (your real column)
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
$transactions = mysqli_query($conn,
  "SELECT t.id, t.purchase_date,
          p.title, p.price,
          buyer.full_name   AS buyer_name,
          buyer.student_id  AS buyer_sid,
          seller.full_name  AS seller_name
   FROM transactions t
   JOIN products p        ON t.product_id = p.id
   JOIN users buyer       ON t.buyer_id   = buyer.id
   LEFT JOIN users seller ON p.seller_id  = seller.id
   $where
   ORDER BY t.id DESC"
);
$total = mysqli_num_rows($transactions);

$rev_q   = $search
  ? "SELECT SUM(p.price) AS total FROM transactions t
     JOIN products p ON t.product_id=p.id
     JOIN users buyer ON t.buyer_id=buyer.id
     LEFT JOIN users seller ON p.seller_id=seller.id $where"
  : "SELECT SUM(p.price) AS total FROM transactions t JOIN products p ON t.product_id=p.id";
$revenue = mysqli_fetch_assoc(mysqli_query($conn, $rev_q))['total'] ?? 0;
?>
<?php include 'admin_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
  <div>
    <h4 style="font-family:'Bebas Neue',sans-serif;font-size:1.7rem;letter-spacing:1px;margin:0;">
      <i class="fa-solid fa-receipt me-2" style="color:var(--tip-maroon)"></i>Transaction Log
    </h4>
    <small class="text-muted">
      <?php echo $total; ?> transaction<?php echo $total !== 1 ? 's' : ''; ?> ·
      Total: <strong style="color:var(--tip-maroon)">₱<?php echo number_format($revenue, 2); ?></strong>
    </small>
  </div>
</div>

<!-- Search -->
<form method="GET" class="mb-3 d-flex gap-2" style="max-width:420px;">
<<<<<<< HEAD
  <input type="text" class="form-control" name="q"
         value="<?php echo htmlspecialchars($search); ?>"
         placeholder="Search item, buyer, or seller…"
         style="border-radius:8px;font-size:0.88rem;border:1.5px solid #ddd;">
  <button class="btn fw-bold" type="submit"
          style="background:var(--tip-black);color:var(--tip-gold);
                 border-radius:8px;padding:6px 16px;border:none;">
    <i class="fa-solid fa-search"></i>
  </button>
  <?php if ($search): ?>
    <a href="admin_transactions.php"
       class="btn btn-outline-secondary fw-bold"
       style="border-radius:8px;">Clear</a>
  <?php endif; ?>
</form>

<!-- Table -->
=======
  <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($search); ?>"
         placeholder="Search item, buyer, or seller…"
         style="border-radius:8px;font-size:0.88rem;border:1.5px solid #ddd;">
  <button class="btn fw-bold" type="submit"
          style="background:var(--tip-black);color:var(--tip-gold);border-radius:8px;padding:6px 16px;border:none;">
    <i class="fa-solid fa-search"></i>
  </button>
  <?php if ($search): ?>
    <a href="admin_transactions.php" class="btn btn-outline-secondary fw-bold" style="border-radius:8px;">Clear</a>
  <?php endif; ?>
</form>

>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
<div class="admin-table-card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
<<<<<<< HEAD
          <th>#</th>
          <th>Item</th>
          <th>Price</th>
          <th>Buyer</th>
          <th>Seller</th>
          <th>Date</th>
=======
          <th>#</th><th>Item</th><th>Price</th><th>Buyer</th><th>Seller</th><th>Date</th>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
        </tr>
      </thead>
      <tbody>
        <?php if ($total === 0): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-5">
              <i class="fa-solid fa-receipt fa-2x mb-2 d-block" style="color:#ddd;"></i>
              No transactions found<?php echo $search ? " for \"$search\"" : ''; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php while ($t = mysqli_fetch_assoc($transactions)): ?>
          <tr>
            <td style="color:#aaa;font-size:0.8rem;">#<?php echo $t['id']; ?></td>
<<<<<<< HEAD
            <td>
              <div style="font-weight:700;font-size:0.88rem;">
                <?php echo htmlspecialchars($t['title']); ?>
              </div>
            </td>
            <td>
              <span style="background:rgba(123,28,46,0.08);color:var(--tip-maroon);
                           font-weight:800;padding:3px 10px;border-radius:20px;font-size:0.82rem;">
=======
            <td><div style="font-weight:700;font-size:0.88rem;"><?php echo htmlspecialchars($t['title']); ?></div></td>
            <td>
              <span style="background:rgba(123,28,46,0.08);color:var(--tip-maroon);font-weight:800;
                           padding:3px 10px;border-radius:20px;font-size:0.82rem;">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
                ₱<?php echo number_format($t['price'], 2); ?>
              </span>
            </td>
            <td>
<<<<<<< HEAD
              <div style="font-weight:700;font-size:0.85rem;">
                <?php echo htmlspecialchars($t['buyer_name']); ?>
              </div>
              <div style="font-size:0.75rem;color:#aaa;font-family:monospace;">
                <?php echo htmlspecialchars($t['buyer_sid']); ?>
              </div>
            </td>
            <td style="font-size:0.85rem;color:#555;">
              <?php echo htmlspecialchars($t['seller_name'] ?? '—'); ?>
            </td>
            <td style="color:#aaa;font-size:0.8rem;white-space:nowrap;">
              <?php echo !empty($t['purchase_date'])
                ? date('M d, Y', strtotime($t['purchase_date'])) : '—'; ?>
=======
              <div style="font-weight:700;font-size:0.85rem;"><?php echo htmlspecialchars($t['buyer_name']); ?></div>
              <div style="font-size:0.75rem;color:#aaa;font-family:monospace;"><?php echo htmlspecialchars($t['buyer_sid']); ?></div>
            </td>
            <td style="font-size:0.85rem;color:#555;"><?php echo htmlspecialchars($t['seller_name'] ?? '—'); ?></td>
            <td style="color:#aaa;font-size:0.8rem;white-space:nowrap;">
              <!-- purchase_date is your real column -->
              <?php echo !empty($t['purchase_date']) ? date('M d, Y', strtotime($t['purchase_date'])) : '—'; ?>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
            </td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<<<<<<< HEAD
<?php include 'admin_footer.php'; ?>
=======
<?php include 'admin_footer.php'; ?>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
