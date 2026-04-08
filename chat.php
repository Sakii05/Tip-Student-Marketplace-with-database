<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$my_id      = intval($_SESSION['user_id']);
$seller_id  = isset($_GET['seller_id'])  ? intval($_GET['seller_id'])  : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if (!$seller_id)           { header("Location: inbox.php");  exit(); }
if ($seller_id === $my_id) { header("Location: index.php");  exit(); }

$other = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$seller_id"));
if (!$other) { header("Location: inbox.php"); exit(); }

$other_avatar   = $other['profile_image'] ?? '';
$other_location = $other['location']      ?? '';

// Product context (don't restrict to seller_id so buyer can also see their own product)
$product = null;
if ($product_id) {
    $product = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM products WHERE id=$product_id"));
}

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message_text'] ?? ''))) {
    $text = mysqli_real_escape_string($conn, trim($_POST['message_text']));
    $pid  = $product_id ? $product_id : 'NULL';
    mysqli_query($conn,
        "INSERT INTO messages (sender_id, receiver_id, product_id, message_text)
         VALUES ($my_id, $seller_id, $pid, '$text')"
    );
    header("Location: chat.php?seller_id=$seller_id" . ($product_id ? "&product_id=$product_id" : ''));
    exit();
}

// Mark messages from the other person as read (only if column exists)
@mysqli_query($conn,
    "UPDATE messages SET is_read=1 WHERE sender_id=$seller_id AND receiver_id=$my_id");

// Load conversation — uses created_at (matches your DB)
$messages = mysqli_query($conn,
    "SELECT m.*, u.full_name AS sender_name, u.profile_image AS sender_avatar
     FROM messages m
     JOIN users u ON m.sender_id = u.id
     WHERE (m.sender_id=$my_id     AND m.receiver_id=$seller_id)
        OR (m.sender_id=$seller_id AND m.receiver_id=$my_id)
     ORDER BY m.id ASC"
);

$me_row    = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT profile_image, full_name FROM users WHERE id=$my_id"));
$me_avatar = $me_row['profile_image'] ?? '';
?>
<?php include 'header.php'; ?>

<section class="py-4">
  <div class="container" style="max-width:720px;">

    <!-- Chat Header -->
    <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-white rounded-3 shadow-sm">
      <a href="inbox.php" style="color:#888;text-decoration:none;font-size:1.1rem;padding:6px;">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <?php if (!empty($other_avatar) && file_exists($other_avatar)): ?>
        <img src="<?php echo htmlspecialchars($other_avatar); ?>"
             style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid var(--tip-gold);flex-shrink:0;">
      <?php else: ?>
        <div style="width:44px;height:44px;border-radius:50%;background:var(--tip-black);color:var(--tip-gold);
                    display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;
                    border:2px solid var(--tip-gold);flex-shrink:0;">
          <?php echo strtoupper(substr($other['full_name'], 0, 1)); ?>
        </div>
      <?php endif; ?>
      <div class="flex-grow-1" style="min-width:0;">
        <a href="profile.php?id=<?php echo $seller_id; ?>"
           style="font-weight:800;text-decoration:none;color:var(--tip-black);font-size:1rem;display:block;">
          <?php echo htmlspecialchars($other['full_name']); ?>
        </a>
        <?php if (!empty($other_location)): ?>
          <div style="font-size:0.75rem;color:#888;">
            <i class="fa-solid fa-location-dot me-1"></i><?php echo htmlspecialchars($other_location); ?>
          </div>
        <?php endif; ?>
      </div>
      <a href="profile.php?id=<?php echo $seller_id; ?>"
         class="btn btn-sm fw-bold"
         style="background:var(--tip-gold);color:#111;border-radius:7px;border:none;white-space:nowrap;">
        <i class="fa-solid fa-user me-1"></i>Profile
      </a>
    </div>

    <!-- Product Context Banner -->
    <?php if ($product): ?>
    <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3 flex-wrap"
         style="background:linear-gradient(135deg,var(--tip-black),#2a0d17);border-left:4px solid var(--tip-gold);">
      <?php
        $p_img = (!empty($product['image_path']) && file_exists($product['image_path']))
          ? htmlspecialchars($product['image_path'])
          : 'https://picsum.photos/seed/' . ($product['id'] * 7 + 13) . '/60/60';
      ?>
      <img src="<?php echo $p_img; ?>"
           style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:2px solid var(--tip-gold);flex-shrink:0;">
      <div class="flex-grow-1">
        <div style="color:rgba(255,255,255,0.5);font-size:0.68rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
          Asking About
        </div>
        <div style="color:#fff;font-weight:800;font-size:0.92rem;">
          <?php echo htmlspecialchars($product['title']); ?>
        </div>
        <div style="color:var(--tip-gold);font-weight:800;">
          ₱<?php echo number_format($product['price'], 2); ?>
        </div>
      </div>
      <?php if ($product['status'] === 'Available' && $product['seller_id'] == $seller_id): ?>
        <a href="buy.php?id=<?php echo $product['id']; ?>"
           class="btn btn-sm fw-bold"
           style="background:var(--tip-maroon);color:#fff;border-radius:7px;border:none;white-space:nowrap;"
           onclick="return confirm('Buy this item now?')">
          <i class="fa-solid fa-cart-shopping me-1"></i>Buy Now
        </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Messages Area -->
    <div id="chatArea"
         style="background:#fff;border-radius:14px;padding:20px 16px;min-height:320px;
                max-height:460px;overflow-y:auto;box-shadow:0 2px 14px rgba(0,0,0,0.07);
                margin-bottom:16px;">
      <?php if (mysqli_num_rows($messages) === 0): ?>
        <div class="text-center text-muted py-5">
          <i class="fa-solid fa-comment-dots fa-2x mb-3 d-block" style="color:#e0e0e0;"></i>
          <p class="mb-0" style="font-size:0.9rem;">No messages yet — say hello! 👋</p>
        </div>
      <?php else: ?>
        <?php while ($msg = mysqli_fetch_assoc($messages)):
          $is_mine  = (int)$msg['sender_id'] === $my_id;
          $av_src   = $is_mine ? $me_avatar : $other_avatar;
          $initials = $is_mine
            ? strtoupper(substr($me_row['full_name'], 0, 1))
            : strtoupper(substr($other['full_name'],  0, 1));
          // Use created_at (your actual column name)
          $msg_time = !empty($msg['created_at'])
            ? date('M d, g:i A', strtotime($msg['created_at'])) : '';
        ?>
        <div class="chat-bubble-wrap <?php echo $is_mine ? 'mine' : ''; ?>">
          <div class="chat-avatar">
            <?php if (!empty($av_src) && file_exists($av_src)): ?>
              <img src="<?php echo htmlspecialchars($av_src); ?>" alt="">
            <?php else: ?>
              <?php echo $initials; ?>
            <?php endif; ?>
          </div>
          <div class="chat-bubble <?php echo $is_mine ? 'mine' : 'theirs'; ?>">
            <?php echo nl2br(htmlspecialchars($msg['message_text'])); ?>
            <span class="chat-time"><?php echo $msg_time; ?></span>
          </div>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <!-- Message Input -->
    <form method="POST" class="d-flex gap-2 align-items-end">
      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
      <textarea name="message_text"
                rows="2"
                placeholder="Type a message… (Enter to send, Shift+Enter for new line)"
                required
                style="flex:1;border-radius:12px;border:1.5px solid #ddd;padding:10px 14px;
                       font-family:'Nunito',sans-serif;font-size:0.92rem;resize:none;
                       outline:none;transition:border-color 0.2s;line-height:1.5;"
                onfocus="this.style.borderColor='var(--tip-gold)'"
                onblur="this.style.borderColor='#ddd'"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();this.closest('form').submit();}">
      </textarea>
      <button type="submit"
              style="background:var(--tip-maroon);color:#fff;border:none;border-radius:12px;
                     padding:12px 18px;font-size:1.1rem;cursor:pointer;transition:background 0.2s;flex-shrink:0;"
              onmouseover="this.style.background='#5e1522'"
              onmouseout="this.style.background='var(--tip-maroon)'">
        <i class="fa-solid fa-paper-plane"></i>
      </button>
    </form>
    <small class="text-muted d-block mt-1" style="font-size:0.75rem;">
      Press Enter to send · Shift+Enter for new line
    </small>

  </div>
</section>

<script>
  const ca = document.getElementById('chatArea');
  if (ca) ca.scrollTop = ca.scrollHeight;
</script>

<?php include 'footer.php'; ?>
