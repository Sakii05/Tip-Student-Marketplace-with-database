<?php
include 'db.php';
session_start();

$profile_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$profile_id) { header("Location: index.php"); exit(); }

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$profile_id"));
if (!$user) { header("Location: index.php"); exit(); }

$user_bio      = $user['bio']            ?? '';
$user_location = $user['location']       ?? '';
$user_avatar   = $user['profile_image']  ?? '';
$user_banner   = $user['banner_image']   ?? '';

$listings = mysqli_query($conn,
    "SELECT * FROM products WHERE seller_id=$profile_id AND status='Available' ORDER BY id DESC");
$listing_count = mysqli_num_rows($listings);

$sold_res   = mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM products WHERE seller_id=$profile_id AND status='Sold'");
$sold_count = $sold_res ? (int)mysqli_fetch_assoc($sold_res)['c'] : 0;

$is_own_profile   = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $profile_id;
$msg_table_exists = (bool) @mysqli_query($conn, "SELECT 1 FROM messages LIMIT 1");
?>
<?php include 'header.php'; ?>

<section class="py-5">
  <div class="container" style="max-width:900px;">

    <!-- Profile Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden;">

      <!-- BANNER -->
      <?php
        $has_banner = !empty($user_banner) && file_exists(__DIR__ . '/' . $user_banner);
      ?>
      <div style="height:160px;overflow:hidden;position:relative;
                  background:linear-gradient(135deg,var(--tip-black),var(--tip-maroon));">
        <?php if ($has_banner): ?>
          <img src="<?php echo htmlspecialchars($user_banner); ?>"
               style="width:100%;height:160px;object-fit:cover;display:block;">
        <?php endif; ?>
        <?php if ($is_own_profile): ?>
          <a href="edit_profile.php"
             style="position:absolute;bottom:10px;right:12px;
                    background:rgba(0,0,0,0.55);color:#fff;
                    font-size:0.75rem;font-weight:700;padding:5px 12px;
                    border-radius:20px;text-decoration:none;
                    transition:background 0.2s;"
             onmouseover="this.style.background='rgba(0,0,0,0.8)'"
             onmouseout="this.style.background='rgba(0,0,0,0.55)'">
            <i class="fa-solid fa-image me-1"></i>Edit Banner
          </a>
        <?php endif; ?>
      </div>

      <!-- PROFILE BODY — avatar sits BELOW banner, no overlap -->
      <div class="card-body px-4 pb-4 pt-3">
        <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3 mb-3">

          <!-- Avatar — normal flow, not absolutely positioned -->
          <?php
            $has_avatar = !empty($user_avatar) && file_exists(__DIR__ . '/' . $user_avatar);
          ?>
          <?php if ($has_avatar): ?>
            <img src="<?php echo htmlspecialchars($user_avatar); ?>"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                        border:4px solid var(--tip-gold);flex-shrink:0;
                        box-shadow:0 4px 16px rgba(0,0,0,0.15);">
          <?php else: ?>
            <div style="width:90px;height:90px;border-radius:50%;background:var(--tip-black);
                        color:var(--tip-gold);font-size:2.2rem;display:flex;align-items:center;
                        justify-content:center;border:4px solid var(--tip-gold);flex-shrink:0;
                        box-shadow:0 4px 16px rgba(0,0,0,0.15);">
              <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
          <?php endif; ?>

          <!-- Name + Info -->
          <div class="flex-grow-1 text-center text-sm-start">
            <h4 style="font-weight:800;margin-bottom:4px;font-size:1.3rem;">
              <?php echo htmlspecialchars($user['full_name']); ?>
            </h4>
            <div class="text-muted" style="font-size:0.85rem;">
              <i class="fa-solid fa-id-card me-1"></i><?php echo htmlspecialchars($user['student_id']); ?>
              <?php if (!empty($user_location)): ?>
                &nbsp;·&nbsp;<i class="fa-solid fa-location-dot me-1"></i><?php echo htmlspecialchars($user_location); ?>
              <?php endif; ?>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex gap-2 flex-wrap justify-content-center">
            <?php if ($is_own_profile): ?>
              <a href="edit_profile.php"
                 class="btn fw-bold px-4"
                 style="background:var(--tip-gold);color:#111;border-radius:8px;border:none;">
                <i class="fa-solid fa-pen me-1"></i>Edit Profile
              </a>
            <?php elseif (isset($_SESSION['user_id']) && $msg_table_exists): ?>
              <a href="chat.php?seller_id=<?php echo $profile_id; ?>"
                 class="btn fw-bold px-4"
                 style="background:var(--tip-maroon);color:#fff;border-radius:8px;border:none;">
                <i class="fa-solid fa-comment me-1"></i>Message
              </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Stats -->
        <div class="d-flex gap-4 mb-3">
          <div class="text-center">
            <div style="font-size:1.5rem;font-weight:800;"><?php echo $listing_count; ?></div>
            <div style="font-size:0.72rem;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Active</div>
          </div>
          <div style="width:1px;background:#eee;"></div>
          <div class="text-center">
            <div style="font-size:1.5rem;font-weight:800;"><?php echo $sold_count; ?></div>
            <div style="font-size:0.72rem;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Sold</div>
          </div>
        </div>

        <!-- Bio -->
        <?php if (!empty($user_bio)): ?>
          <p style="font-size:0.92rem;color:#444;margin:0;max-width:580px;line-height:1.65;">
            <?php echo nl2br(htmlspecialchars($user_bio)); ?>
          </p>
        <?php else: ?>
          <p style="font-size:0.88rem;color:#ccc;margin:0;font-style:italic;">
            <?php echo $is_own_profile
              ? 'No bio yet — <a href="edit_profile.php" style="color:var(--tip-maroon)">add one!</a>'
              : 'No bio yet.'; ?>
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Listings -->
    <h5 style="font-family:'Bebas Neue',sans-serif;font-size:1.4rem;letter-spacing:1px;margin-bottom:6px;">
      <?php echo $is_own_profile ? 'Your' : htmlspecialchars($user['full_name']) . "'s"; ?>
      <span style="color:var(--tip-maroon);">Listings</span>
    </h5>
    <div style="height:3px;width:50px;background:var(--tip-gold);border-radius:2px;margin-bottom:20px;"></div>

    <?php if ($listing_count === 0): ?>
      <div class="empty-state" style="padding:40px 0;">
        <i class="fa-solid fa-box-open d-block"></i>
        <p class="fw-bold mb-1">No active listings<?php echo $is_own_profile ? ' yet' : ''; ?></p>
        <?php if ($is_own_profile): ?>
          <a href="upload.php" class="btn btn-warning fw-bold mt-2">
            <i class="fa-solid fa-plus me-1"></i>Post an Item
          </a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php while ($item = mysqli_fetch_assoc($listings)):
          $img_path  = trim($item['image_path'] ?? '');
          $has_img   = !empty($img_path) && file_exists(__DIR__ . '/' . $img_path);
          $src       = $has_img ? htmlspecialchars($img_path) : '';
        ?>
        <div class="col-sm-6 col-md-4">
          <div class="product-card card h-100">
            <div style="height:160px;overflow:hidden;background:#ececec;">
              <?php if ($has_img): ?>
                <img src="<?php echo $src; ?>"
                     style="width:100%;height:160px;object-fit:cover;" loading="lazy">
              <?php else: ?>
                <div style="width:100%;height:160px;display:flex;flex-direction:column;
                            align-items:center;justify-content:center;gap:6px;">
                  <i class="fa-solid fa-image" style="font-size:2rem;color:#ccc;"></i>
                  <span style="font-size:0.72rem;color:#bbb;font-weight:600;">No Photo</span>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-body d-flex flex-column">
              <h6 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h6>
              <p class="text-muted small mb-2" style="font-size:0.8rem;flex-grow:1;">
                <?php echo htmlspecialchars(substr($item['description'],0,60)) . (strlen($item['description'])>60?'…':''); ?>
              </p>
              <div class="price-badge mb-2">₱<?php echo number_format($item['price'],2); ?></div>

              <?php if ($is_own_profile): ?>
                <div class="d-flex gap-2">
                  <a href="edit_post.php?id=<?php echo $item['id']; ?>"
                     class="btn btn-sm fw-bold flex-fill"
                     style="background:#f0f0f0;color:#333;border-radius:7px;">
                    <i class="fa-solid fa-pencil me-1"></i>Edit
                  </a>
                  <a href="delete_post.php?id=<?php echo $item['id']; ?>"
                     class="btn btn-sm fw-bold flex-fill"
                     style="background:#fce4e4;color:#a00;border-radius:7px;"
                     onclick="return confirm('Delete this item?')">
                    <i class="fa-solid fa-trash me-1"></i>Delete
                  </a>
                </div>
              <?php else: ?>
                <div class="d-flex flex-column gap-2">
                  <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="buy.php?id=<?php echo $item['id']; ?>" class="btn-buy"
                       onclick="return confirm('Buy <?php echo addslashes(htmlspecialchars($item['title'])); ?>?')">
                      <i class="fa-solid fa-cart-shopping me-1"></i>Buy Now
                    </a>
                    <?php if ($msg_table_exists): ?>
                    <a href="chat.php?seller_id=<?php echo $profile_id; ?>&product_id=<?php echo $item['id']; ?>"
                       class="btn-msg">
                      <i class="fa-solid fa-comment me-1"></i>Message Seller
                    </a>
                    <?php endif; ?>
                  <?php else: ?>
                    <a href="login.php" class="btn-buy" style="background:#888;">
                      <i class="fa-solid fa-lock me-1"></i>Login to Buy
                    </a>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include 'footer.php'; ?>