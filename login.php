<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<?php include 'header.php'; ?>

<div class="auth-wrapper">
  <div class="auth-card">

    <!-- Card Header -->
    <div class="auth-header">
      <div style="font-size:2.4rem;color:var(--tip-gold);margin-bottom:8px;">
        <i class="fa-solid fa-store"></i>
      </div>
      <h2>Welcome Back</h2>
      <p>Sign in to your TIP Marketplace account</p>
    </div>

    <!-- Card Body -->
    <div class="auth-body">

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>Invalid Student ID or Password. Please try again.</span>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
          <i class="fa-solid fa-circle-check"></i>
          <span>Registration successful! You can now log in.</span>
        </div>
      <?php endif; ?>

      <form action="login_process.php" method="POST">

        <div class="mb-3">
          <label class="form-label" for="student_id">
            <i class="fa-solid fa-id-card me-1" style="color:var(--tip-maroon)"></i>Student ID
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
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
              placeholder="Your password"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn btn-tip-primary">
          <i class="fa-solid fa-right-to-bracket me-2"></i>Login
        </button>

      </form>

      <hr class="my-3">
      <p class="text-center text-muted mb-0" style="font-size:0.88rem;">
        Don't have an account?
        <a href="register.php" class="fw-bold" style="color:var(--tip-maroon);">Register here</a>
      </p>
    </div>

  </div>
</div>

<?php include 'footer.php'; ?>
