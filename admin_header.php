<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — TIP Marketplace</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --tip-gold:    #F5C400;
      --tip-maroon:  #7B1C2E;
      --tip-black:   #111111;
      --tip-sidebar: #16161e;
      --tip-gray:    #F4F4F6;
    }
    * { box-sizing: border-box; }
    body { font-family: 'Nunito', sans-serif; background: var(--tip-gray); margin: 0; }

    /* ── SIDEBAR ── */
    .admin-sidebar {
      width: 240px;
      min-height: 100vh;
      background: var(--tip-sidebar);
      border-right: 3px solid var(--tip-gold);
      position: fixed;
      top: 0; left: 0;
      display: flex;
      flex-direction: column;
      z-index: 1000;
      transition: transform 0.3s;
    }
    .sidebar-brand {
      padding: 22px 20px 16px;
      border-bottom: 1px solid rgba(245,196,0,0.15);
    }
    .sidebar-brand .brand-name {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.35rem;
      color: var(--tip-gold);
      letter-spacing: 1.5px;
      display: flex; align-items: center; gap: 10px;
    }
    .sidebar-brand .brand-sub {
      font-size: 0.7rem;
      color: rgba(255,255,255,0.35);
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-top: 2px;
      padding-left: 34px;
    }
    .sidebar-nav { flex: 1; padding: 14px 0; }
    .nav-section-title {
      font-size: 0.65rem;
      font-weight: 800;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.25);
      padding: 12px 20px 6px;
    }
    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 20px;
      color: rgba(255,255,255,0.6);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.88rem;
      border-left: 3px solid transparent;
      transition: all 0.18s;
    }
    .sidebar-link:hover {
      background: rgba(245,196,0,0.07);
      color: var(--tip-gold);
      border-left-color: var(--tip-gold);
    }
    .sidebar-link.active {
      background: rgba(245,196,0,0.12);
      color: var(--tip-gold);
      border-left-color: var(--tip-gold);
    }
    .sidebar-link i { width: 18px; text-align: center; font-size: 0.95rem; }
    .sidebar-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(245,196,0,0.15);
    }
    .sidebar-footer .admin-name {
      font-size: 0.8rem;
      color: rgba(255,255,255,0.5);
      margin-bottom: 8px;
    }
    .sidebar-footer .admin-name span { color: var(--tip-gold); font-weight: 700; }
    .btn-logout-side {
      display: flex; align-items: center; gap: 8px;
      background: rgba(123,28,46,0.3);
      border: 1px solid rgba(123,28,46,0.5);
      color: #ff8a8a;
      border-radius: 7px;
      padding: 7px 14px;
      font-size: 0.82rem;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
      width: 100%;
      justify-content: center;
    }
    .btn-logout-side:hover { background: var(--tip-maroon); color: #fff; border-color: var(--tip-maroon); }

    /* ── MAIN CONTENT ── */
    .admin-main {
      margin-left: 240px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .admin-topbar {
      background: #fff;
      border-bottom: 2px solid #eee;
      padding: 14px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .topbar-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.45rem;
      letter-spacing: 1px;
      color: var(--tip-black);
    }
    .topbar-title span { color: var(--tip-maroon); }
    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .topbar-badge {
      background: var(--tip-black);
      color: var(--tip-gold);
      font-size: 0.72rem;
      font-weight: 800;
      padding: 4px 10px;
      border-radius: 20px;
      letter-spacing: 1px;
    }
    .content-area { padding: 28px; flex: 1; }

    /* ── STAT CARDS ── */
    .stat-card {
      background: #fff;
      border-radius: 14px;
      padding: 22px 24px;
      box-shadow: 0 2px 14px rgba(0,0,0,0.06);
      border-left: 5px solid var(--tip-gold);
      display: flex;
      align-items: center;
      gap: 18px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px rgba(0,0,0,0.1); }
    .stat-card.maroon { border-left-color: var(--tip-maroon); }
    .stat-card.green  { border-left-color: #28a745; }
    .stat-card.blue   { border-left-color: #0d6efd; }
    .stat-icon {
      width: 52px; height: 52px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }
    .stat-icon.gold   { background: rgba(245,196,0,0.12);  color: #c9a000; }
    .stat-icon.maroon { background: rgba(123,28,46,0.1);   color: var(--tip-maroon); }
    .stat-icon.green  { background: rgba(40,167,69,0.1);   color: #28a745; }
    .stat-icon.blue   { background: rgba(13,110,253,0.1);  color: #0d6efd; }
    .stat-value { font-size: 1.9rem; font-weight: 800; line-height: 1; color: var(--tip-black); }
    .stat-label { font-size: 0.8rem; color: #888; font-weight: 600; margin-top: 3px; }

    /* ── DATA TABLE ── */
    .admin-table-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 2px 14px rgba(0,0,0,0.06);
      overflow: hidden;
    }
    .table-header {
      padding: 18px 22px;
      border-bottom: 1px solid #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }
    .table-header h5 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.2rem;
      letter-spacing: 1px;
      color: var(--tip-black);
      margin: 0;
    }
    .table { margin: 0; font-size: 0.88rem; }
    .table thead th {
      background: #fafafa;
      font-weight: 800;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #666;
      border-bottom: 2px solid #eee;
      padding: 12px 16px;
      white-space: nowrap;
    }
    .table tbody td { padding: 12px 16px; vertical-align: middle; border-color: #f5f5f5; }
    .table tbody tr:hover { background: #fafafa; }

    /* ── BADGES ── */
    .badge-available { background: rgba(40,167,69,0.12); color: #1a7a35; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; }
    .badge-sold      { background: rgba(108,117,125,0.1); color: #555; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; }
    .badge-admin     { background: rgba(245,196,0,0.15); color: #8a6e00; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; }
    .badge-user      { background: rgba(13,110,253,0.1); color: #0d6efd; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; }

    /* ── ACTION BUTTONS ── */
    .btn-action { padding: 5px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.15s; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
    .btn-del  { background: #fce4e4; color: #a00; }
    .btn-del:hover  { background: #a00; color: #fff; }
    .btn-view { background: #e8f0ff; color: #0d6efd; }
    .btn-view:hover { background: #0d6efd; color: #fff; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .admin-sidebar { transform: translateX(-100%); }
      .admin-sidebar.show { transform: translateX(0); }
      .admin-main { margin-left: 0; }
      .content-area { padding: 16px; }
    }
  </style>
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <div class="brand-name">
      <i class="fa-solid fa-shield-halved" style="color:var(--tip-maroon)"></i>
      Admin Panel
    </div>
    <div class="brand-sub">TIP Marketplace</div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-title">Overview</div>
    <a href="admin_dashboard.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>

    <div class="nav-section-title">Manage</div>
    <a href="admin_users.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin_users.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-users"></i> Users
    </a>
    <a href="admin_products.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin_products.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-box-open"></i> Products
    </a>
    <a href="admin_transactions.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin_transactions.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-receipt"></i> Transactions
    </a>

    <div class="nav-section-title">Site</div>
    <a href="index.php" class="sidebar-link" target="_blank">
      <i class="fa-solid fa-arrow-up-right-from-square"></i> View Marketplace
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-name">Logged in as <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span></div>
    <a href="admin_logout.php" class="btn-logout-side">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>

<!-- ══ MAIN WRAPPER ══ -->
<div class="admin-main">
  <div class="admin-topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"
              style="background:var(--tip-black);color:var(--tip-gold);border:none;border-radius:7px;padding:6px 10px;">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="topbar-title">
        TIP <span>Marketplace</span>
      </div>
    </div>
    <div class="topbar-right">
      <span class="topbar-badge"><i class="fa-solid fa-shield-halved me-1"></i>ADMIN</span>
      <span style="font-size:0.82rem;color:#888;">
        <?php echo date('M d, Y'); ?>
      </span>
    </div>
  </div>

  <div class="content-area">
<!-- Content injected by each admin page below this -->
