<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id  = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$item = mysqli_fetch_assoc($res);

if (!$item || $item['seller_id'] != $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}
?>
<?php include 'header.php'; ?>

<section class="py-5">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="section-title">Edit <span>Listing</span></h2>
      <div class="section-divider mx-auto"></div>
      <p class="text-muted" style="font-size:0.92rem;">Update the details of your posted item.</p>
    </div>

    <div class="form-card">
      <form action="update_process.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

        <div class="mb-3">
          <label class="form-label" for="title">
            <i class="fa-solid fa-tag me-1" style="color:var(--tip-maroon)"></i>Item Name
          </label>
          <input
            type="text"
            class="form-control"
            id="title"
            name="title"
            value="<?php echo htmlspecialchars($item['title']); ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label" for="description">
            <i class="fa-solid fa-align-left me-1" style="color:var(--tip-maroon)"></i>Description
          </label>
          <textarea
            class="form-control"
            id="description"
            name="description"
            rows="4"
          ><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>

        <div class="mb-4">
          <label class="form-label" for="price">
            <i class="fa-solid fa-peso-sign me-1" style="color:var(--tip-maroon)"></i>Price (₱)
          </label>
          <div class="input-group">
            <span class="input-group-text" style="background:var(--tip-black);color:var(--tip-gold);border-color:#ddd;">₱</span>
            <input
              type="number"
              class="form-control"
              id="price"
              name="price"
              value="<?php echo $item['price']; ?>"
              min="1" step="0.01" required
            >
          </div>
        </div>

        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-tip-primary flex-fill">
            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
          </button>
          <a href="index.php" class="btn btn-outline-secondary flex-fill" style="border-radius:8px;font-weight:700;">
            Cancel
          </a>
        </div>
      </form>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>
