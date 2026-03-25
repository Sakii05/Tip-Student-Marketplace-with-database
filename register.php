<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<?php include 'header.php'; ?>

<div class="auth-wrapper">
  <div class="auth-card" style="max-width:460px;">

    <!-- Card Header -->
    <div class="auth-header">
      <div style="font-size:2.4rem;color:var(--tip-gold);margin-bottom:8px;">
        <i class="fa-solid fa-user-plus"></i>
      </div>
      <h2>Create Account</h2>
      <p>Join the TIP Student Marketplace</p>
    </div>

    <!-- Card Body -->
    <div class="auth-body">

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>Registration failed. Student ID may already be taken.</span>
        </div>
      <?php endif; ?>

      <form action="register_process.php" method="POST">

        <div class="mb-3">
          <label class="form-label" for="student_id">
            <i class="fa-solid fa-id-card me-1" style="color:var(--tip-maroon)"></i>Student ID
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <input
              type="text"
              class="form-control"
              id="student_id"
              name="student_id"
              placeholder="e.g. 2021123456"
              required
              autofocus
            >
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="full_name">
            <i class="fa-solid fa-user me-1" style="color:var(--tip-maroon)"></i>Full Name
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input
              type="text"
              class="form-control"
              id="full_name"
              name="full_name"
              placeholder="Juan dela Cruz"
              required
            >
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label" for="password">
            <i class="fa-solid fa-lock me-1" style="color:var(--tip-maroon)"></i>Password
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              placeholder="Create a strong password"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn btn-tip-primary">
          <i class="fa-solid fa-user-plus me-2"></i>Create Account
        </button>

      </form>

      <hr class="my-3">
      <p class="text-center text-muted mb-0" style="font-size:0.88rem;">
        Already have an account?
        <a href="login.php" class="fw-bold" style="color:var(--tip-maroon);">Login here</a>
      </p>
    </div>

  </div>
</div>

<?php include 'footer.php'; ?>
