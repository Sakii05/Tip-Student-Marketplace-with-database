<?php
require 'admin_guard.php';
include 'db.php';

// ── Handle delete ─────────────────────────────────────
if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);
    // Also delete image file if exists
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image_path FROM products WHERE id=$pid"));
    if ($r && !empty($r['image_path']) && file_exists($r['image_path'])) {
        unlink($r['image_path']);
    }
    mysqli_query($conn, "DELETE FROM products WHERE id=$pid");
    header("Location: admin_products.php?msg=deleted");
    exit();
}

// ── Handle mark available ─────────────────────────────
if (isset($_GET['restore'])) {
    $pid = intval($_GET['restore']);
    mysqli_query($conn, "UPDATE products SET status='Available' WHERE id=$pid");
    header("Location: admin_products.php?msg=restored");
    exit();
}

// ── Filter ────────────────────────────────────────────
$filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['q'])      ? mysqli_real_escape_string($conn, trim($_GET['q'])) : '';

$where_parts = [];
if ($filter === 'available') $where_parts[] = "p.status = 'Available'";
if ($filter === 'sold')      $where_parts[] = "p.status = 'Sold'";
if ($search)                 $where_parts[] = "(p.title LIKE '%$search%' OR u.full_name LIKE '%$search%')";
$where = count($where_parts) ? 'WHERE ' . implode(' AND ', $where_parts) : '';

$products = mysqli_query($conn,
  "SELECT p.*, u.full_name AS seller_name FROM products p
   LEFT JOIN users u ON p.seller_id = u.id
   $where ORDER BY p.id DESC"
);
$total = mysqli_num_rows($products);

// Counts for filter tabs
$cnt_all   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM products"))['c'];
$cnt_avail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM products WHERE status='Available'"))['c'];
$cnt_sold  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM products WHERE status='Sold'"))['c'];
?>
<?php include 'admin_header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 style="font-family:'Bebas Neue',sans-serif;font-size:1.7rem;letter-spacing:1px;margin:0;">
      <i class="fa-solid fa-box-open me-2" style="color:var(--tip-maroon)"></i>Product Management
    </h4>
    <small class="text-muted"><?php echo $total; ?> listing<?php echo $total !== 1 ? 's' : ''; ?> shown</small>
  </div>
</div>

<?php if (isset($_GET['msg'])): ?>
  <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="border-radius:10px;font-size:0.88rem;">
    <i class="fa-solid fa-circle-check"></i>
    <span><?php echo $_GET['msg'] === 'deleted' ? 'Product deleted.' : 'Product restored to Available.'; ?></span>
  </div>
<?php endif; ?>

<!-- Filter Tabs + Search -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
  <div class="d-flex gap-2 flex-wrap">
    <?php
    $tabs = [
      ''          => ['All', $cnt_all],
      'available' => ['Available', $cnt_avail],
      'sold'      => ['Sold', $cnt_sold],
    ];
    foreach ($tabs as $val => [$label, $cnt]):
      $active = $filter === $val;
    ?>
    <a href="admin_products.php?status=<?php echo $val; ?><?php echo $search ? '&q='.urlencode($search) : ''; ?>"
       style="padding:6px 16px;border-radius:20px;font-size:0.82rem;font-weight:700;text-decoration:none;
              background:<?php echo $active ? 'var(--tip-black)' : '#fff'; ?>;
              color:<?php echo $active ? 'var(--tip-gold)' : '#666'; ?>;
              border:1.5px solid <?php echo $active ? 'var(--tip-black)' : '#ddd'; ?>;">
      <?php echo $label; ?> <span style="opacity:0.6;"><?php echo $cnt; ?></span>
    </a>
    <?php endforeach; ?>
  </div>

  <form method="GET" class="d-flex gap-2">
    <input type="hidden" name="status" value="<?php echo htmlspecialchars($filter); ?>">
    <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($search); ?>"
           placeholder="Search title or seller…"
           style="border-radius:8px;font-size:0.88rem;border:1.5px solid #ddd;width:220px;">
    <button class="btn fw-bold" type="submit"
            style="background:var(--tip-black);color:var(--tip-gold);border-radius:8px;padding:6px 14px;border:none;">
      <i class="fa-solid fa-search"></i>
    </button>
    <?php if ($search): ?>
      <a href="admin_products.php?status=<?php echo urlencode($filter); ?>"
         class="btn btn-outline-secondary fw-bold" style="border-radius:8px;">Clear</a>
    <?php endif; ?>
  </form>
</div>

<!-- Table -->
<div class="admin-table-card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Photo</th>
          <th>Title</th>
          <th>Price</th>
          <th>Seller</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($total === 0): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-5">
              <i class="fa-solid fa-box fa-2x mb-2 d-block" style="color:#ddd;"></i>
              No products found
            </td>
          </tr>
        <?php else: ?>
          <?php while ($p = mysqli_fetch_assoc($products)): ?>
          <tr>
            <td style="color:#aaa;font-size:0.8rem;">#<?php echo $p['id']; ?></td>
            <td>
              <?php if (!empty($p['image_path']) && file_exists($p['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($p['image_path']); ?>"
                     style="width:46px;height:46px;object-fit:cover;border-radius:8px;border:2px solid #eee;">
              <?php else: ?>
                <div style="width:46px;height:46px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#bbb;">
                  <i class="fa-solid fa-image"></i>
                </div>
              <?php endif; ?>
            </td>
            <td>
              <div style="font-weight:700;font-size:0.88rem;"><?php echo htmlspecialchars($p['title']); ?></div>
              <div style="font-size:0.76rem;color:#aaa;"><?php echo htmlspecialchars(substr($p['description'],0,50)); ?><?php echo strlen($p['description'])>50?'…':''; ?></div>
            </td>
            <td style="color:var(--tip-maroon);font-weight:800;">₱<?php echo number_format($p['price'],2); ?></td>
            <td style="font-size:0.85rem;"><?php echo htmlspecialchars($p['seller_name'] ?? '—'); ?></td>
            <td>
              <?php if ($p['status'] === 'Available'): ?>
                <span class="badge-available"><i class="fa-solid fa-circle-dot me-1" style="font-size:0.6rem;"></i>Available</span>
              <?php else: ?>
                <span class="badge-sold"><i class="fa-solid fa-check me-1"></i>Sold</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="d-flex gap-1 flex-wrap">
                <?php if ($p['status'] === 'Sold'): ?>
                  <a href="admin_products.php?restore=<?php echo $p['id']; ?>"
                     class="btn-action btn-view"
                     onclick="return confirm('Restore this item to Available?')">
                    <i class="fa-solid fa-rotate-left"></i> Restore
                  </a>
                <?php endif; ?>
                <a href="admin_products.php?delete=<?php echo $p['id']; ?>"
                   class="btn-action btn-del"
                   onclick="return confirm('Permanently delete \'<?php echo htmlspecialchars($p['title']); ?>\'?')">
                  <i class="fa-solid fa-trash"></i> Delete
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'admin_footer.php'; ?>
