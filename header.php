<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php
$unread_count = 0;
if (isset($_SESSION['user_id'])) {
    if (!isset($conn)) { include_once 'db.php'; }
    $uid = intval($_SESSION['user_id']);
    $unread_res = mysqli_query($conn, "SELECT COUNT(*) AS c FROM messages WHERE receiver_id=$uid AND is_read=0");
    if ($unread_res) $unread_count = (int) mysqli_fetch_assoc($unread_res)['c'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIP Student Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --tip-gold:   #F5C400;
            --tip-maroon: #7B1C2E;
            --tip-black:  #111111;
            --tip-dark:   #1A1A1A;
            --tip-gray:   #F4F4F4;
            --tip-muted:  #6c757d;
        }
        body { font-family:'Nunito',sans-serif; background-color:var(--tip-gray); color:var(--tip-black); }

        /* NAVBAR */
        .tip-navbar { background:var(--tip-black); border-bottom:3px solid var(--tip-gold); padding:0.6rem 1rem; }
        .tip-navbar .navbar-brand { font-family:'Bebas Neue',sans-serif; font-size:1.6rem; letter-spacing:1px; color:var(--tip-gold)!important; display:flex; align-items:center; gap:10px; }
        .tip-navbar .navbar-brand .brand-badge { background:var(--tip-maroon); color:#fff; font-size:0.55rem; font-family:'Nunito',sans-serif; font-weight:800; letter-spacing:2px; text-transform:uppercase; padding:3px 7px; border-radius:4px; line-height:1.4; }
        .tip-navbar .nav-link { color:rgba(255,255,255,0.8)!important; font-weight:600; font-size:0.9rem; padding:0.5rem 0.85rem!important; border-radius:6px; transition:background 0.2s,color 0.2s; }
        .tip-navbar .nav-link:hover,.tip-navbar .nav-link.active { background:rgba(245,196,0,0.12); color:var(--tip-gold)!important; }
        .tip-navbar .btn-nav-login { background:transparent; border:1.5px solid var(--tip-gold); color:var(--tip-gold)!important; font-weight:700; font-size:0.85rem; padding:6px 16px; border-radius:6px; transition:all 0.2s; text-decoration:none; }
        .tip-navbar .btn-nav-login:hover { background:var(--tip-gold); color:var(--tip-black)!important; }
        .tip-navbar .btn-nav-sell { background:var(--tip-gold); border:none; color:var(--tip-black)!important; font-weight:800; font-size:0.85rem; padding:6px 18px; border-radius:6px; transition:all 0.2s; text-decoration:none; }
        .tip-navbar .btn-nav-sell:hover { background:#e0b000; }
        .tip-navbar .navbar-toggler { border-color:rgba(245,196,0,0.5); }
        .tip-navbar .navbar-toggler-icon { filter:invert(1); }
        .msg-badge { background:var(--tip-maroon); color:#fff; font-size:0.6rem; font-weight:800; min-width:17px; height:17px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; padding:0 4px; margin-left:3px; vertical-align:middle; }

        /* HERO */
        .tip-hero { background:linear-gradient(135deg,var(--tip-black) 60%,var(--tip-maroon) 100%); color:#fff; padding:56px 0 44px; border-bottom:4px solid var(--tip-gold); }
        .tip-hero h1 { font-family:'Bebas Neue',sans-serif; font-size:clamp(2.4rem,5vw,3.8rem); letter-spacing:2px; color:var(--tip-gold); }
        .tip-hero p { font-size:1.05rem; color:rgba(255,255,255,0.75); }

        /* PRODUCT CARDS */
        .product-card { border:none; border-radius:12px; overflow:hidden; box-shadow:0 2px 14px rgba(0,0,0,0.08); transition:transform 0.22s,box-shadow 0.22s; background:#fff; }
        .product-card:hover { transform:translateY(-5px); box-shadow:0 8px 32px rgba(0,0,0,0.14); }
        .product-card .card-body { padding:1.1rem 1.2rem 1rem; }
        .product-card .card-title { font-weight:800; font-size:1rem; margin-bottom:4px; color:var(--tip-black); }
        .product-card .price-badge { background:var(--tip-gold); color:var(--tip-black); font-weight:800; font-size:1rem; padding:4px 12px; border-radius:20px; display:inline-block; margin-bottom:8px; }
        .product-card .seller-info { font-size:0.8rem; color:var(--tip-muted); margin-bottom:10px; }
        .product-card .btn-buy { background:var(--tip-maroon); color:#fff; border:none; font-weight:700; border-radius:8px; padding:8px 0; width:100%; transition:background 0.2s; display:block; text-align:center; text-decoration:none; font-size:0.88rem; }
        .product-card .btn-buy:hover { background:#5e1522; color:#fff; }
        .product-card .btn-msg { background:rgba(245,196,0,0.1); color:var(--tip-black); border:1.5px solid var(--tip-gold); font-weight:700; border-radius:8px; padding:7px 0; width:100%; transition:all 0.2s; display:block; text-align:center; text-decoration:none; font-size:0.85rem; }
        .product-card .btn-msg:hover { background:var(--tip-gold); color:var(--tip-black); }

        /* AUTH */
        .auth-wrapper { min-height:90vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--tip-black) 0%,#2c0d16 100%); padding:40px 16px; }
        .auth-card { background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.35); width:100%; max-width:420px; overflow:hidden; }
        .auth-card .auth-header { background:var(--tip-black); border-bottom:3px solid var(--tip-gold); padding:28px 28px 20px; text-align:center; }
        .auth-card .auth-header h2 { font-family:'Bebas Neue',sans-serif; font-size:1.9rem; color:var(--tip-gold); letter-spacing:1.5px; margin:0; }
        .auth-card .auth-header p { color:rgba(255,255,255,0.6); font-size:0.85rem; margin:4px 0 0; }
        .auth-card .auth-body { padding:28px; }
        .auth-card .form-label { font-weight:700; font-size:0.85rem; color:#444; }
        .auth-card .form-control { border-radius:8px; padding:10px 14px; border:1.5px solid #ddd; font-size:0.92rem; }
        .auth-card .form-control:focus { border-color:var(--tip-gold); box-shadow:0 0 0 3px rgba(245,196,0,0.15); }
        .auth-card .input-group-text { background:var(--tip-black); color:var(--tip-gold); border-color:#ddd; }
        .btn-tip-primary { background:var(--tip-maroon); color:#fff; border:none; font-weight:800; border-radius:8px; padding:11px; font-size:0.95rem; width:100%; transition:background 0.2s; }
        .btn-tip-primary:hover { background:#5e1522; color:#fff; }

        /* SECTION */
        .section-title { font-family:'Bebas Neue',sans-serif; font-size:1.9rem; letter-spacing:1.5px; color:var(--tip-black); }
        .section-title span { color:var(--tip-maroon); }
        .section-divider { height:3px; width:60px; background:var(--tip-gold); border-radius:2px; margin-bottom:24px; }

        /* FORM CARD */
        .form-card { background:#fff; border-radius:14px; box-shadow:0 4px 24px rgba(0,0,0,0.09); padding:36px; max-width:600px; margin:0 auto; }
        .form-card .form-label { font-weight:700; font-size:0.88rem; color:#444; }
        .form-card .form-control,.form-card .form-select { border-radius:8px; padding:10px 14px; border:1.5px solid #ddd; font-size:0.92rem; }
        .form-card .form-control:focus { border-color:var(--tip-gold); box-shadow:0 0 0 3px rgba(245,196,0,0.15); }

        /* ABOUT */
        .about-hero { background:linear-gradient(135deg,var(--tip-black),var(--tip-maroon)); color:#fff; padding:60px 0; border-bottom:4px solid var(--tip-gold); }
        .dev-card { background:#fff; border-radius:14px; padding:28px 20px; text-align:center; box-shadow:0 4px 20px rgba(0,0,0,0.09); transition:transform 0.2s; }
        .dev-card:hover { transform:translateY(-4px); }
        .dev-avatar { width:80px; height:80px; border-radius:50%; background:var(--tip-black); color:var(--tip-gold); font-size:2rem; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; border:3px solid var(--tip-gold); }
        .feature-icon { width:54px; height:54px; border-radius:12px; background:var(--tip-black); color:var(--tip-gold); font-size:1.4rem; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; }

        /* FOOTER */
        .tip-footer { background:var(--tip-black); border-top:3px solid var(--tip-gold); color:rgba(255,255,255,0.65); padding:36px 0 24px; margin-top:60px; }
        .tip-footer .footer-brand { font-family:'Bebas Neue',sans-serif; font-size:1.5rem; color:var(--tip-gold); letter-spacing:1px; }
        .tip-footer a { color:rgba(255,255,255,0.6); text-decoration:none; font-size:0.88rem; transition:color 0.2s; }
        .tip-footer a:hover { color:var(--tip-gold); }
        .tip-footer .footer-heading { color:var(--tip-gold); font-weight:800; font-size:0.8rem; letter-spacing:2px; text-transform:uppercase; margin-bottom:12px; }
        .tip-footer .footer-divider { border-color:rgba(255,255,255,0.1); margin:24px 0 18px; }

        /* EMPTY STATE */
        .empty-state { text-align:center; padding:60px 20px; color:var(--tip-muted); }
        .empty-state i { font-size:3.5rem; color:#ddd; margin-bottom:16px; }

        /* ALERTS */
        .alert { border-radius:10px; font-weight:600; font-size:0.9rem; }

        /* PROFILE */
        .profile-avatar { width:110px; height:110px; border-radius:50%; object-fit:cover; border:4px solid var(--tip-gold); }
        .profile-avatar-placeholder { width:110px; height:110px; border-radius:50%; background:var(--tip-black); color:var(--tip-gold); font-size:2.8rem; display:flex; align-items:center; justify-content:center; border:4px solid var(--tip-gold); margin:0 auto; }

        /* CHAT BUBBLES */
        .chat-bubble-wrap { display:flex; margin-bottom:16px; gap:10px; align-items:flex-end; }
        .chat-bubble-wrap.mine { flex-direction:row-reverse; }
        .chat-avatar { width:36px; height:36px; border-radius:50%; background:var(--tip-black); color:var(--tip-gold); display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:800; flex-shrink:0; overflow:hidden; }
        .chat-avatar img { width:100%; height:100%; object-fit:cover; }
        .chat-bubble { max-width:68%; padding:10px 14px; border-radius:18px; font-size:0.9rem; line-height:1.55; }
        .chat-bubble.theirs { background:#fff; border:1.5px solid #eee; border-bottom-left-radius:4px; color:#222; }
        .chat-bubble.mine { background:var(--tip-maroon); color:#fff; border-bottom-right-radius:4px; }
        .chat-time { font-size:0.68rem; opacity:0.5; margin-top:5px; display:block; }
        .chat-bubble.mine .chat-time { text-align:right; color:rgba(255,255,255,0.7); opacity:1; }

        /* INBOX */
        .convo-item { display:flex; align-items:center; gap:14px; padding:14px 18px; border-bottom:1px solid #f0f0f0; text-decoration:none; color:inherit; transition:background 0.15s; }
        .convo-item:hover { background:#fafafa; }
        .convo-item.unread { background:#fffdf0; }
        .convo-avatar { width:46px; height:46px; border-radius:50%; background:var(--tip-black); color:var(--tip-gold); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem; flex-shrink:0; overflow:hidden; border:2px solid transparent; }
        .convo-avatar img { width:100%; height:100%; object-fit:cover; }
        .convo-item.unread .convo-avatar { border-color:var(--tip-gold); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg tip-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fa-solid fa-store"></i>TIP Marketplace<span class="brand-badge">Beta</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><i class="fa-solid fa-circle-info me-1"></i>About</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="inbox.php">
            <i class="fa-solid fa-envelope me-1"></i>Messages
            <?php if ($unread_count > 0): ?>
              <span class="msg-badge"><?php echo $unread_count > 9 ? '9+' : $unread_count; ?></span>
            <?php endif; ?>
          </a>
        </li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php
            if (!isset($conn)) include_once 'db.php';
            $me = mysqli_fetch_assoc(mysqli_query($conn,"SELECT profile_image FROM users WHERE id=".intval($_SESSION['user_id'])));
          ?>
          <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>"
             class="d-flex align-items-center gap-2 text-decoration-none"
             style="color:rgba(255,255,255,0.8);font-size:0.85rem;font-weight:600;padding:4px 8px;border-radius:6px;transition:background 0.2s;"
             onmouseover="this.style.background='rgba(245,196,0,0.1)'" onmouseout="this.style.background='transparent'">
            <?php if ($me && !empty($me['profile_image']) && file_exists($me['profile_image'])): ?>
              <img src="<?php echo htmlspecialchars($me['profile_image']); ?>"
                   style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid var(--tip-gold);">
            <?php else: ?>
              <i class="fa-solid fa-user-circle" style="color:var(--tip-gold);font-size:1.25rem;"></i>
            <?php endif; ?>
            <?php echo htmlspecialchars($_SESSION['full_name']); ?>
          </a>
          <a href="upload.php" class="btn-nav-sell"><i class="fa-solid fa-plus me-1"></i>Sell Item</a>
          <a href="logout.php" class="btn-nav-login"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn-nav-login"><i class="fa-solid fa-right-to-bracket me-1"></i>Login</a>
          <a href="register.php" class="btn-nav-sell"><i class="fa-solid fa-user-plus me-1"></i>Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
