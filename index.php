<?php
include 'db.php';
session_start();
?>
<?php include 'header.php'; ?>

<<<<<<< HEAD
=======
<!-- HERO -->
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
<section class="tip-hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1><i class="fa-solid fa-store me-2"></i>TIP Student Marketplace</h1>
        <p class="mb-4">Buy and sell items within the TIP community — from gadgets and books to food and game accounts.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="register.php" class="btn btn-warning fw-bold me-2 px-4 py-2">
            <i class="fa-solid fa-user-plus me-2"></i>Get Started
          </a>
          <a href="login.php" class="btn btn-outline-light px-4 py-2">
            <i class="fa-solid fa-right-to-bracket me-2"></i>Login
          </a>
        <?php else: ?>
          <a href="upload.php" class="btn btn-warning fw-bold px-4 py-2">
            <i class="fa-solid fa-plus me-2"></i>List an Item
          </a>
        <?php endif; ?>
      </div>
      <div class="col-lg-5 d-none d-lg-flex justify-content-end">
        <div style="font-size:7rem;opacity:0.12;color:#fff;"><i class="fa-solid fa-bag-shopping"></i></div>
      </div>
    </div>
  </div>
</section>

<<<<<<< HEAD
=======
<!-- PRODUCTS -->
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
<section class="py-5">
  <div class="container">

    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
      <div>
        <h2 class="section-title mb-0">Available <span>Items</span></h2>
        <div class="section-divider mt-2"></div>
      </div>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="upload.php" class="btn btn-sm fw-bold px-3"
           style="background:var(--tip-gold);color:#111;border-radius:8px;">
          <i class="fa-solid fa-plus me-1"></i>Sell Something
        </a>
      <?php endif; ?>
    </div>

    <?php
    $query = "SELECT products.*, users.full_name AS seller_name, users.id AS seller_uid,
                     users.profile_image AS seller_avatar
              FROM products
              LEFT JOIN users ON products.seller_id = users.id
              WHERE products.status = 'Available'
              ORDER BY products.id DESC";
    $result = mysqli_query($conn, $query);
    $count  = mysqli_num_rows($result);
    ?>

    <?php if ($count === 0): ?>
      <div class="empty-state">
        <i class="fa-solid fa-box-open d-block"></i>
        <h5 class="fw-bold">No items available yet</h5>
        <p>Be the first to list something for sale!</p>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="upload.php" class="btn btn-warning fw-bold mt-2">
            <i class="fa-solid fa-plus me-2"></i>Post an Item
          </a>
        <?php endif; ?>
      </div>
    <?php else: ?>
    <div class="row g-4">
      <?php while ($row = mysqli_fetch_assoc($result)):
<<<<<<< HEAD

        $img_path  = trim($row['image_path'] ?? '');
        // Always check against absolute disk path
        $has_image = !empty($img_path) && file_exists(__DIR__ . '/' . $img_path);

=======
        $has_image = !empty($row['image_path']) && file_exists($row['image_path']);
        $img_src   = $has_image
                       ? htmlspecialchars($row['image_path'])
                       : 'https://picsum.photos/seed/' . ($row['id'] * 7 + 13) . '/300/200';
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
        $is_owner  = isset($_SESSION['user_id']) && $row['seller_id'] == $_SESSION['user_id'];
      ?>
      <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="product-card card h-100">

<<<<<<< HEAD
          <div style="position:relative;overflow:hidden;height:190px;background:#ececec;">
            <?php if ($has_image): ?>
              <img src="<?php echo htmlspecialchars($img_path); ?>"
                   alt="<?php echo htmlspecialchars($row['title']); ?>"
                   loading="lazy"
                   style="width:100%;height:190px;object-fit:cover;">
=======
          <!-- Product Image -->
          <div style="position:relative;overflow:hidden;height:190px;background:#f0f0f0;">
            <img src="<?php echo $img_src; ?>"
                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                 loading="lazy"
                 style="width:100%;height:190px;object-fit:cover;">
            <?php if ($has_image): ?>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
              <span style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,0.55);
                           color:#fff;font-size:0.68rem;font-weight:700;
                           padding:3px 8px;border-radius:20px;letter-spacing:.5px;">
                <i class="fa-solid fa-camera me-1"></i>PHOTO
              </span>
<<<<<<< HEAD
            <?php else: ?>
              <div style="width:100%;height:190px;display:flex;flex-direction:column;
                          align-items:center;justify-content:center;gap:8px;">
                <i class="fa-solid fa-image" style="font-size:2.4rem;color:#ccc;"></i>
                <span style="font-size:0.75rem;color:#bbb;font-weight:600;">No Photo</span>
              </div>
=======
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
            <?php endif; ?>
          </div>

          <div class="card-body d-flex flex-column">
            <h6 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h6>
            <p class="text-muted small mb-2" style="font-size:0.82rem;flex-grow:1;">
              <?php echo htmlspecialchars(substr($row['description'],0,70)) . (strlen($row['description'])>70?'…':''); ?>
            </p>
            <div class="price-badge mb-2">₱<?php echo number_format($row['price'],2); ?></div>

<<<<<<< HEAD
            <div class="seller-info mb-3">
              <?php
                $s_avatar  = trim($row['seller_avatar'] ?? '');
                $s_has_av  = !empty($s_avatar) && file_exists(__DIR__ . '/' . $s_avatar);
              ?>
              <?php if ($s_has_av): ?>
                <img src="<?php echo htmlspecialchars($s_avatar); ?>"
                     style="width:18px;height:18px;border-radius:50%;object-fit:cover;
                            margin-right:4px;border:1.5px solid var(--tip-gold);">
=======
            <!-- Clickable seller name -->
            <div class="seller-info mb-3">
              <?php if (!empty($row['seller_avatar']) && file_exists($row['seller_avatar'])): ?>
                <img src="<?php echo htmlspecialchars($row['seller_avatar']); ?>"
                     style="width:18px;height:18px;border-radius:50%;object-fit:cover;margin-right:4px;border:1.5px solid var(--tip-gold);">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
              <?php else: ?>
                <i class="fa-solid fa-user-circle me-1"></i>
              <?php endif; ?>
              <a href="profile.php?id=<?php echo $row['seller_uid']; ?>"
                 style="color:var(--tip-muted);font-weight:700;text-decoration:none;font-size:0.8rem;"
<<<<<<< HEAD
                 onmouseover="this.style.color='var(--tip-maroon)'"
                 onmouseout="this.style.color='var(--tip-muted)'">
=======
                 onmouseover="this.style.color='var(--tip-maroon)'" onmouseout="this.style.color='var(--tip-muted)'">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
                <?php echo htmlspecialchars($row['seller_name'] ?? 'Unknown'); ?>
              </a>
            </div>

<<<<<<< HEAD
=======
            <!-- Action buttons -->
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
            <?php if ($is_owner): ?>
              <div class="d-flex gap-2">
                <a href="edit_post.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-sm fw-bold flex-fill"
                   style="background:#f0f0f0;color:#333;border-radius:8px;">
                  <i class="fa-solid fa-pencil me-1"></i>Edit
                </a>
                <a href="delete_post.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-sm fw-bold flex-fill"
                   style="background:#fce4e4;color:#a00;border-radius:8px;"
                   onclick="return confirm('Delete this item?')">
                  <i class="fa-solid fa-trash me-1"></i>Delete
                </a>
              </div>

            <?php elseif (isset($_SESSION['user_id'])): ?>
              <div class="d-flex flex-column gap-2">
                <a href="buy.php?id=<?php echo $row['id']; ?>"
                   class="btn-buy"
                   onclick="return confirm('Confirm purchase of \'<?php echo addslashes(htmlspecialchars($row['title'])); ?>\'?')">
                  <i class="fa-solid fa-cart-shopping me-1"></i>Buy Now
                </a>
                <a href="chat.php?seller_id=<?php echo $row['seller_uid']; ?>&product_id=<?php echo $row['id']; ?>"
                   class="btn-msg">
                  <i class="fa-solid fa-comment me-1"></i>Message Seller
                </a>
              </div>

            <?php else: ?>
              <a href="login.php" class="btn-buy" style="background:#888;">
                <i class="fa-solid fa-lock me-2"></i>Login to Buy
              </a>
            <?php endif; ?>

          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<<<<<<< HEAD
<?php include 'footer.php'; ?>
=======
<?php include 'footer.php'; ?>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
