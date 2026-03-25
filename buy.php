<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$product_id = intval($_GET['id']);
$buyer_id   = intval($_SESSION['user_id']);

// Get product details + seller info before marking sold
$product = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT p.*, u.full_name AS seller_name, u.id AS seller_uid
     FROM products p
     JOIN users u ON p.seller_id = u.id
     WHERE p.id = $product_id AND p.status = 'Available'"
));

// If product not found or already sold
if (!$product) {
    header("Location: index.php");
    exit();
}

// 1. Mark product as Sold
$updateSql = "UPDATE products SET status='Sold' WHERE id=$product_id";
// 2. Insert transaction record
$transSql  = "INSERT INTO transactions (product_id, buyer_id) VALUES ($product_id, $buyer_id)";

$success = mysqli_query($conn, $updateSql) && mysqli_query($conn, $transSql);

// Get the transaction ID just inserted
$transaction_id = $success ? mysqli_insert_id($conn) : null;
?>
<?php include 'header.php'; ?>

<section class="py-5" style="min-height:calc(100vh - 70px);">
  <div class="container" style="max-width:540px;">

    <?php if ($success): ?>

      <!-- Success Icon -->
      <div class="text-center mb-4">
        <div style="width:80px;height:80px;background:#28a745;border-radius:50%;
                    display:flex;align-items:center;justify-content:center;
                    margin:0 auto 20px;box-shadow:0 6px 24px rgba(40,167,69,0.3);">
          <i class="fa-solid fa-check" style="font-size:2.2rem;color:#fff;"></i>
        </div>
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:2.4rem;letter-spacing:2px;margin-bottom:8px;">
          Purchase Successful!
        </h2>
        <p class="text-muted" style="font-size:0.95rem;">
          Your transaction has been recorded. Coordinate with the seller to complete the meet-up.
        </p>
      </div>

      <!-- Receipt Card -->
      <div class="bg-white rounded-3 shadow-sm mb-4 overflow-hidden">

        <!-- Receipt Header -->
        <div style="background:linear-gradient(135deg,var(--tip-black),#2a0d17);
                    padding:16px 24px;border-bottom:3px solid var(--tip-gold);">
          <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;font-weight:700;
                      letter-spacing:2px;text-transform:uppercase;">Official Receipt</div>
          <div style="color:var(--tip-gold);font-family:'Bebas Neue',sans-serif;
                      font-size:1.3rem;letter-spacing:1px;">
            TIP Student Marketplace
          </div>
        </div>

        <!-- Receipt Body -->
        <div style="padding:24px;">

          <!-- Order ID -->
          <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
               style="border-bottom:1px dashed #eee;">
            <span style="font-size:0.82rem;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              Order ID
            </span>
            <span style="font-family:monospace;font-weight:800;font-size:1rem;color:var(--tip-black);">
              #<?php echo $transaction_id; ?>
            </span>
          </div>

          <!-- Item Name -->
          <div class="d-flex justify-content-between align-items-start mb-3 pb-3"
               style="border-bottom:1px dashed #eee;">
            <span style="font-size:0.82rem;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              Item Purchased
            </span>
            <span style="font-weight:800;font-size:0.95rem;color:var(--tip-black);
                         text-align:right;max-width:60%;">
              <?php echo htmlspecialchars($product['title']); ?>
            </span>
          </div>

          <!-- Seller -->
          <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
               style="border-bottom:1px dashed #eee;">
            <span style="font-size:0.82rem;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              Seller
            </span>
            <a href="profile.php?id=<?php echo $product['seller_uid']; ?>"
               style="font-weight:700;font-size:0.92rem;color:var(--tip-maroon);text-decoration:none;">
              <?php echo htmlspecialchars($product['seller_name']); ?>
            </a>
          </div>

          <!-- Total Price -->
          <div class="d-flex justify-content-between align-items-center">
            <span style="font-size:0.82rem;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              Total Paid
            </span>
            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.8rem;
                         color:var(--tip-maroon);letter-spacing:1px;">
              ₱<?php echo number_format($product['price'], 2); ?>
            </span>
          </div>

        </div>

        <!-- Receipt Footer -->
        <div style="background:#f9f9f9;padding:12px 24px;border-top:1px solid #eee;">
          <div style="font-size:0.75rem;color:#aaa;text-align:center;">
            <i class="fa-solid fa-circle-check me-1" style="color:#28a745;"></i>
            Transaction #<?php echo $transaction_id; ?> recorded on
            <?php echo date('F d, Y \a\t g:i A'); ?>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex flex-column gap-3">
        <a href="chat.php?seller_id=<?php echo $product['seller_uid']; ?>&product_id=<?php echo $product_id; ?>"
           class="btn fw-bold py-3"
           style="background:var(--tip-maroon);color:#fff;border-radius:10px;border:none;font-size:1rem;">
          <i class="fa-solid fa-comment me-2"></i>Message Seller Now
        </a>
        <a href="index.php"
           class="btn fw-bold py-3"
           style="background:var(--tip-gold);color:#111;border-radius:10px;border:none;font-size:1rem;">
          <i class="fa-solid fa-store me-2"></i>Back to Marketplace
        </a>
      </div>

    <?php else: ?>

      <!-- Error State -->
      <div class="text-center py-5">
        <div style="width:80px;height:80px;background:#dc3545;border-radius:50%;
                    display:flex;align-items:center;justify-content:center;
                    margin:0 auto 20px;">
          <i class="fa-solid fa-xmark" style="font-size:2.2rem;color:#fff;"></i>
        </div>
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:2rem;letter-spacing:1px;">
          Transaction Failed
        </h2>
        <div class="alert alert-danger d-flex align-items-center gap-2 mt-3">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>An error occurred. The item may already be sold.</span>
        </div>
        <a href="index.php" class="btn fw-bold px-5 mt-3"
           style="background:var(--tip-gold);color:#111;border-radius:10px;border:none;">
          <i class="fa-solid fa-arrow-left me-2"></i>Go Back
        </a>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php include 'footer.php'; ?>