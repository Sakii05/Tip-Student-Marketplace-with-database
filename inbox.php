<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$my_id = intval($_SESSION['user_id']);

<<<<<<< HEAD
// Mark all read
=======
// ── Mark all read (only if is_read column exists) ──────────────────────
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
if (isset($_GET['mark_read'])) {
    @mysqli_query($conn, "UPDATE messages SET is_read=1 WHERE receiver_id=$my_id");
    header("Location: inbox.php"); exit();
}

<<<<<<< HEAD
// Get all unique conversation partners
=======
// ── Get all unique conversation partners ──────────────────────────────
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
$partner_ids = [];
$res = mysqli_query($conn,
    "SELECT DISTINCT IF(sender_id=$my_id, receiver_id, sender_id) AS pid
     FROM messages
<<<<<<< HEAD
     WHERE sender_id=$my_id OR receiver_id=$my_id");
=======
     WHERE sender_id=$my_id OR receiver_id=$my_id"
);
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
while ($r = mysqli_fetch_assoc($res)) {
    $partner_ids[] = (int)$r['pid'];
}

<<<<<<< HEAD
// Build conversation list
=======
// ── Build conversation list ────────────────────────────────────────────
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
$convo_rows   = [];
$total_unread = 0;

foreach ($partner_ids as $pid) {
<<<<<<< HEAD
    // Latest message — uses created_at (your real column)
=======
    // Latest message — uses created_at
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
    $last = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM messages
         WHERE (sender_id=$my_id AND receiver_id=$pid)
            OR (sender_id=$pid   AND receiver_id=$my_id)
         ORDER BY id DESC LIMIT 1"
    ));

<<<<<<< HEAD
    // Unread count — safe if is_read column missing
=======
    // Unread count — safe: 0 if is_read column missing
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
    $unread_res = @mysqli_query($conn,
        "SELECT COUNT(*) AS c FROM messages
         WHERE sender_id=$pid AND receiver_id=$my_id AND is_read=0");
    $unread = $unread_res ? (int)mysqli_fetch_assoc($unread_res)['c'] : 0;

<<<<<<< HEAD
=======
    // Partner info
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
    $partner = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM users WHERE id=$pid"));
    if (!$partner || !$last) continue;

    $convo_rows[] = [
<<<<<<< HEAD
        'partner_id'  => $pid,
        'partner_name'=> $partner['full_name'],
        'partner_img' => $partner['profile_image'] ?? '',
        'last_text'   => $last['message_text'],
        'last_time'   => $last['created_at'] ?? '',  // ← your real column
        'last_sender' => (int)$last['sender_id'],
        'unread'      => $unread,
        'last_id'     => (int)$last['id'],
=======
        'partner_id'   => $pid,
        'partner_name' => $partner['full_name'],
        'partner_img'  => $partner['profile_image'] ?? '',
        'last_text'    => $last['message_text'],
        'last_time'    => $last['created_at'] ?? '',   // ← your real column
        'last_sender'  => (int)$last['sender_id'],
        'unread'       => $unread,
        'last_id'      => (int)$last['id'],
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
    ];
    $total_unread += $unread;
}

// Sort newest first
usort($convo_rows, fn($a,$b) => $b['last_id'] - $a['last_id']);
?>
<?php include 'header.php'; ?>

<<<<<<< HEAD
<section class="py-5" style="min-height:calc(100vh - 70px);">
=======
<section class="py-5">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
  <div class="container" style="max-width:680px;">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <h2 class="section-title mb-0">
          <i class="fa-solid fa-envelope me-2" style="color:var(--tip-maroon)"></i>Inbox
          <?php if ($total_unread > 0): ?>
            <span style="background:var(--tip-maroon);color:#fff;font-size:0.75rem;font-weight:800;
                         padding:3px 10px;border-radius:20px;vertical-align:middle;font-family:'Nunito',sans-serif;">
              <?php echo $total_unread; ?> unread
            </span>
          <?php endif; ?>
        </h2>
        <div class="section-divider mt-2"></div>
      </div>
      <?php if ($total_unread > 0): ?>
        <a href="inbox.php?mark_read=1"
           class="btn btn-sm fw-bold"
           style="background:#f0f0f0;color:#555;border-radius:8px;border:none;font-size:0.82rem;">
          <i class="fa-solid fa-check-double me-1"></i>Mark all read
        </a>
      <?php endif; ?>
    </div>

    <div class="bg-white rounded-3 shadow-sm overflow-hidden">
      <?php if (empty($convo_rows)): ?>
        <div class="empty-state py-5">
          <i class="fa-solid fa-envelope-open d-block"></i>
          <h6 class="fw-bold mb-2">No conversations yet</h6>
          <p style="font-size:0.88rem;color:#aaa;">
            Browse the marketplace and tap <strong>Message Seller</strong> to start chatting.
          </p>
          <a href="index.php" class="btn fw-bold"
             style="background:var(--tip-gold);color:#111;border-radius:8px;border:none;">
            <i class="fa-solid fa-store me-1"></i>Browse Marketplace
          </a>
        </div>
      <?php else: ?>
        <?php foreach ($convo_rows as $c):
          $has_unread = $c['unread'] > 0;
          $preview    = htmlspecialchars(substr($c['last_text'], 0, 55)) . (strlen($c['last_text'])>55?'…':'');
          $is_mine    = $c['last_sender'] === $my_id;
          $time_str   = !empty($c['last_time']) ? date('M d', strtotime($c['last_time'])) : '';
        ?>
        <a href="chat.php?seller_id=<?php echo $c['partner_id']; ?>"
           class="convo-item <?php echo $has_unread ? 'unread' : ''; ?>">

          <div class="convo-avatar">
<<<<<<< HEAD
            <?php
              $pimg      = $c['partner_img'];
              $pimg_full = !empty($pimg) && file_exists(__DIR__ . '/' . $pimg);
            ?>
            <?php if ($pimg_full): ?>
              <img src="<?php echo htmlspecialchars($pimg); ?>" alt="">
=======
            <?php if (!empty($c['partner_img']) && file_exists($c['partner_img'])): ?>
              <img src="<?php echo htmlspecialchars($c['partner_img']); ?>" alt="">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
            <?php else: ?>
              <?php echo strtoupper(substr($c['partner_name'], 0, 1)); ?>
            <?php endif; ?>
          </div>

          <div class="flex-grow-1" style="min-width:0;">
            <div class="d-flex align-items-center justify-content-between gap-2">
<<<<<<< HEAD
              <span style="font-weight:<?php echo $has_unread?'800':'700'; ?>;
                           font-size:0.92rem;color:var(--tip-black);">
=======
              <span style="font-weight:<?php echo $has_unread?'800':'700'; ?>;font-size:0.92rem;color:var(--tip-black);">
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
                <?php echo htmlspecialchars($c['partner_name']); ?>
              </span>
              <span style="font-size:0.72rem;color:#bbb;white-space:nowrap;"><?php echo $time_str; ?></span>
            </div>
            <div style="font-size:0.82rem;color:<?php echo $has_unread?'#333':'#999'; ?>;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              <?php if ($is_mine): ?><span style="color:#bbb;">You: </span><?php endif; ?>
              <?php echo $preview; ?>
            </div>
          </div>

          <?php if ($has_unread): ?>
            <span style="background:var(--tip-maroon);color:#fff;font-size:0.7rem;font-weight:800;
                         min-width:20px;height:20px;border-radius:10px;display:flex;
                         align-items:center;justify-content:center;padding:0 5px;flex-shrink:0;">
              <?php echo $c['unread'] > 9 ? '9+' : $c['unread']; ?>
            </span>
          <?php else: ?>
            <i class="fa-solid fa-chevron-right" style="color:#e0e0e0;font-size:0.75rem;flex-shrink:0;"></i>
          <?php endif; ?>

        </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>

<<<<<<< HEAD
<?php include 'footer.php'; ?>
=======
<?php include 'footer.php'; ?>
>>>>>>> 8a3d08d84a37941360a00543a24ebbd2047121ad
