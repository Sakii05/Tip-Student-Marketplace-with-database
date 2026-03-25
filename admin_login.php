<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — TIP Marketplace</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --tip-gold:   #F5C400;
      --tip-maroon: #7B1C2E;
      --tip-black:  #111111;
    }
    body {
      font-family: 'Nunito', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0a0a0a 0%, #1a0810 50%, #0a0a0a 100%);
      padding: 20px;
    }
    .admin-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 30px 80px rgba(0,0,0,0.5);
      width: 100%;
      max-width: 400px;
      overflow: hidden;
    }
    .admin-header {
      background: var(--tip-black);
      border-bottom: 4px solid var(--tip-gold);
      padding: 32px 28px 24px;
      text-align: center;
    }
    .admin-header .shield-icon {
      width: 64px; height: 64px;
      background: var(--tip-maroon);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 14px;
      font-size: 1.7rem;
      color: var(--tip-gold);
      border: 3px solid var(--tip-gold);
    }
    .admin-header h2 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2rem;
      color: var(--tip-gold);
      letter-spacing: 2px;
      margin: 0;
    }
    .admin-header p { color: rgba(255,255,255,0.5); font-size: 0.82rem; margin: 4px 0 0; }
    .admin-body { padding: 30px 28px; }
    .form-label { font-weight: 700; font-size: 0.85rem; color: #444; }
    .form-control { border-radius: 8px; padding: 10px 14px; border: 1.5px solid #ddd; font-size: 0.92rem; }
    .form-control:focus { border-color: var(--tip-gold); box-shadow: 0 0 0 3px rgba(245,196,0,0.15); }
    .input-group-text { background: var(--tip-black); color: var(--tip-gold); border-color: #ddd; }
    .btn-admin { background: var(--tip-maroon); color: #fff; border: none; font-weight: 800;
                 border-radius: 8px; padding: 12px; font-size: 0.95rem; width: 100%;
                 transition: background 0.2s; }
    .btn-admin:hover { background: #5e1522; color: #fff; }
    .back-link { display:block; text-align:center; margin-top:16px; font-size:0.85rem;
                 color: rgba(255,255,255,0.45); text-decoration:none; transition: color 0.2s; }
    .back-link:hover { color: var(--tip-gold); }
  </style>
</head>
<body>

<?php
session_start();
// If already logged in as admin, redirect
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<div>
  <div class="admin-card">
    <div class="admin-header">
      <div class="shield-icon"><i class="fa-solid fa-shield-halved"></i></div>
      <h2>Admin Panel</h2>
      <p>TIP Marketplace — Restricted Access</p>
    </div>
    <div class="admin-body">

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius:8px;font-size:0.88rem;">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>Invalid admin credentials. Try again.</span>
        </div>
      <?php endif; ?>

      <form action="admin_login_process.php" method="POST">
        <div class="mb-3">
          <label class="form-label"><i class="fa-solid fa-user-shield me-1" style="color:var(--tip-maroon)"></i>Admin Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" class="form-control" name="username" placeholder="admin" required autofocus>
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label"><i class="fa-solid fa-lock me-1" style="color:var(--tip-maroon)"></i>Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" name="password" placeholder="Admin password" required>
          </div>
        </div>
        <button type="submit" class="btn-admin">
          <i class="fa-solid fa-right-to-bracket me-2"></i>Login as Admin
        </button>
      </form>

    </div>
  </div>
  <a href="index.php" class="back-link">
    <i class="fa-solid fa-arrow-left me-1"></i>Back to Marketplace
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
