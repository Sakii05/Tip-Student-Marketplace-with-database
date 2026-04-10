<?php
require 'admin_guard.php';
include 'db.php';

<<<<<<< HEAD
// Handle delete
if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);

    // Delete in correct order to avoid foreign key constraint errors
    @mysqli_query($conn, "DELETE FROM messages    WHERE sender_id=$uid OR receiver_id=$uid");
    @mysqli_query($conn, "DELETE FROM transactions WHERE buyer_id=$uid");
    @mysqli_query($conn, "DELETE FROM products    WHERE seller_id=$uid");
    @mysqli_query($conn, "DELETE FROM users       WHERE id=$uid");

=======
// ── Handle delete ─────────────────────────────────────
if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $uid");
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
    header("Location: admin_users.php?msg=deleted");
    exit();
}

<<<<<<< HEAD
// Search
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, trim($_GET['q'])) : '';
$where  = $search ? "WHERE full_name LIKE '%$search%' OR student_id LIKE '%$search%'" : '';

$users = mysqli_query($conn,
    "SELECT u.*,
        (SELECT COUNT(*) FROM products     WHERE seller_id = u.id) AS listing_count,
        (SELECT COUNT(*) FROM transactions WHERE buyer_id  = u.id) AS purchase_count
     FROM users u $where ORDER BY u.id DESC"
);
$total = mysqli_num_rows($users);
?>
<?php include 'admin_header.php'; ?>

=======
// ── Search ────────────────────────────────────────────
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, trim($_GET['q'])) : '';
$where  = $search ? "WHERE full_name LIKE '%$search%' OR student_id LIKE '%$search%'" : '';

$users  = mysqli_query($conn, "SELECT u.*, 
    (SELECT COUNT(*) FROM products WHERE seller_id = u.id) AS listing_count,
    (SELECT COUNT(*) FROM transactions WHERE buyer_id = u.id) AS purchase_count
    FROM users u $where ORDER BY u.id DESC");
$total  = mysqli_num_rows($users);
?>
<?php include 'admin_header.php'; ?>

<!-- Page Title -->
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h4 style="font-family:'Bebas Neue',sans-serif;font-size:1.7rem;letter-spacing:1px;margin:0;">
      <i class="fa-solid fa-users me-2" style="color:var(--tip-maroon)"></i>User Management
    </h4>
<<<<<<< HEAD
    <small class="text-muted">
      <?php echo $total; ?> registered user<?php echo $total !== 1 ? 's' : ''; ?>
    </small>
=======
    <small class="text-muted"><?php echo $total; ?> registered user<?php echo $total !== 1 ? 's' : ''; ?></small>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
  </div>
</div>

<?php if (isset($_GET['msg'])): ?>
<<<<<<< HEAD
  <div class="alert alert-success d-flex align-items-center gap-2 mb-3"
       style="border-radius:10px;font-size:0.88rem;">
    <i class="fa-solid fa-circle-check"></i>
    <span>User and all related data deleted successfully.</span>
=======
  <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="border-radius:10px;font-size:0.88rem;">
    <i class="fa-solid fa-circle-check"></i>
    <span>User deleted successfully.</span>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
  </div>
<?php endif; ?>

<!-- Search -->
<form method="GET" class="mb-3 d-flex gap-2" style="max-width:400px;">
<<<<<<< HEAD
  <input type="text" class="form-control" name="q"
         value="<?php echo htmlspecialchars($search); ?>"
         placeholder="Search by name or student ID…"
         style="border-radius:8px;font-size:0.88rem;border:1.5px solid #ddd;">
  <button class="btn fw-bold" type="submit"
          style="background:var(--tip-black);color:var(--tip-gold);
                 border-radius:8px;padding:6px 16px;border:none;">
    <i class="fa-solid fa-search"></i>
  </button>
  <?php if ($search): ?>
    <a href="admin_users.php"
       class="btn btn-outline-secondary fw-bold"
       style="border-radius:8px;">Clear</a>
=======
  <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($search); ?>"
         placeholder="Search by name or student ID…"
         style="border-radius:8px;font-size:0.88rem;border:1.5px solid #ddd;">
  <button class="btn fw-bold" type="submit"
          style="background:var(--tip-black);color:var(--tip-gold);border-radius:8px;padding:6px 16px;border:none;">
    <i class="fa-solid fa-search"></i>
  </button>
  <?php if ($search): ?>
    <a href="admin_users.php" class="btn btn-outline-secondary fw-bold" style="border-radius:8px;">Clear</a>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
  <?php endif; ?>
</form>

<!-- Table -->
<div class="admin-table-card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Student ID</th>
          <th>Listings</th>
          <th>Purchases</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($total === 0): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-5">
              <i class="fa-solid fa-user-slash fa-2x mb-2 d-block" style="color:#ddd;"></i>
              No users found<?php echo $search ? " for \"$search\"" : ''; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php while ($u = mysqli_fetch_assoc($users)): ?>
          <tr>
            <td style="color:#aaa;font-size:0.8rem;">#<?php echo $u['id']; ?></td>
            <td>
              <div class="d-flex align-items-center gap-2">
<<<<<<< HEAD
                <?php
                  $av = $u['profile_image'] ?? '';
                  $av_ok = !empty($av) && file_exists(__DIR__ . '/' . $av);
                ?>
                <?php if ($av_ok): ?>
                  <img src="<?php echo htmlspecialchars($av); ?>"
                       style="width:34px;height:34px;border-radius:50%;object-fit:cover;
                              border:2px solid var(--tip-gold);">
                <?php else: ?>
                  <div style="width:34px;height:34px;border-radius:50%;
                              background:var(--tip-black);color:var(--tip-gold);
                              display:flex;align-items:center;justify-content:center;
                              font-size:0.85rem;font-weight:800;flex-shrink:0;">
                    <?php echo strtoupper(substr($u['full_name'], 0, 1)); ?>
                  </div>
                <?php endif; ?>
                <div>
                  <div style="font-weight:700;font-size:0.88rem;">
                    <?php echo htmlspecialchars($u['full_name']); ?>
                  </div>
                </div>
              </div>
            </td>
            <td style="font-family:monospace;font-size:0.85rem;color:#555;">
              <?php echo htmlspecialchars($u['student_id']); ?>
            </td>
            <td>
              <span style="background:#eef2ff;color:#3b5bdb;font-weight:700;
                           padding:3px 10px;border-radius:20px;font-size:0.78rem;">
=======
                <div style="width:34px;height:34px;border-radius:50%;background:var(--tip-black);
                            color:var(--tip-gold);display:flex;align-items:center;justify-content:center;
                            font-size:0.85rem;font-weight:800;flex-shrink:0;">
                  <?php echo strtoupper(substr($u['full_name'],0,1)); ?>
                </div>
                <div>
                  <div style="font-weight:700;font-size:0.88rem;"><?php echo htmlspecialchars($u['full_name']); ?></div>
                </div>
              </div>
            </td>
            <td style="font-family:monospace;font-size:0.85rem;color:#555;"><?php echo htmlspecialchars($u['student_id']); ?></td>
            <td>
              <span style="background:#eef2ff;color:#3b5bdb;font-weight:700;padding:3px 10px;border-radius:20px;font-size:0.78rem;">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
                <?php echo $u['listing_count']; ?> items
              </span>
            </td>
            <td>
<<<<<<< HEAD
              <span style="background:#e8f5e9;color:#2e7d32;font-weight:700;
                           padding:3px 10px;border-radius:20px;font-size:0.78rem;">
=======
              <span style="background:#e8f5e9;color:#2e7d32;font-weight:700;padding:3px 10px;border-radius:20px;font-size:0.78rem;">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
                <?php echo $u['purchase_count']; ?> bought
              </span>
            </td>
            <td>
              <a href="admin_users.php?delete=<?php echo $u['id']; ?>"
                 class="btn-action btn-del"
<<<<<<< HEAD
                 onclick="return confirm('Delete <?php echo htmlspecialchars($u['full_name']); ?> and ALL their data? This cannot be undone.')">
=======
                 onclick="return confirm('Delete user <?php echo htmlspecialchars($u['full_name']); ?>? This cannot be undone.')">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
                <i class="fa-solid fa-trash"></i> Delete
              </a>
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
