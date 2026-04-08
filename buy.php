<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$product_id = intval($_GET['id']);
$buyer_id   = $_SESSION['user_id'];

// 1. Update product status to 'Sold'
$updateSql = "UPDATE products SET status = 'Sold' WHERE id = $product_id";
// 2. Insert record into transactions table
$transSql  = "INSERT INTO transactions (product_id, buyer_id) VALUES ($product_id, $buyer_id)";

$success = mysqli_query($conn, $updateSql) && mysqli_query($conn, $transSql);
?>
<?php include 'header.php'; ?>

<section class="py-5">
  <div class="container" style="max-width:520px;">

    <?php if ($success): ?>
      <div class="text-center py-5">
        <div style="font-size:4rem;color:#28a745;margin-bottom:18px;">
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2 class="fw-bold mb-2" style="font-family:'Bebas Neue',sans-serif;font-size:2.2rem;letter-spacing:1.5px;">
          Purchase Successful!
        </h2>
        <p class="text-muted mb-4">
          Your transaction has been recorded. The seller will reach out to you shortly.
        </p>
        <div class="alert alert-success d-flex align-items-center gap-2">
          <i class="fa-solid fa-circle-check"></i>
          <span>Item marked as <strong>Sold</strong> and transaction saved.</span>
        </div>
        <a href="index.php" class="btn fw-bold px-5 py-2 mt-3"
           style="background:var(--tip-gold);color:#111;border-radius:10px;">
          <i class="fa-solid fa-store me-2"></i>Back to Marketplace
        </a>
      </div>
    <?php else: ?>
      <div class="text-center py-5">
        <div style="font-size:4rem;color:#dc3545;margin-bottom:18px;">
          <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <h2 class="fw-bold mb-2">Transaction Failed</h2>
        <div class="alert alert-danger d-flex align-items-center gap-2">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>An error occurred: <?php echo mysqli_error($conn); ?></span>
        </div>
        <a href="index.php" class="btn btn-outline-secondary fw-bold px-5 mt-3" style="border-radius:10px;">
          <i class="fa-solid fa-arrow-left me-2"></i>Go Back
        </a>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include 'footer.php'; ?>
