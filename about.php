<?php session_start(); ?>
<?php include 'header.php'; ?>

<!-- ════ ABOUT HERO ════ -->
<section class="about-hero">
  <div class="container text-center">
    <div style="font-size:3.5rem;color:var(--tip-gold);margin-bottom:14px;">
      <i class="fa-solid fa-people-group"></i>
    </div>
    <h1 class="fw-bold" style="font-family:'Bebas Neue',sans-serif;font-size:2.8rem;letter-spacing:2px;color:var(--tip-gold);">
      Meet the Developers
    </h1>
    <p class="mt-2" style="color:rgba(255,255,255,0.7);max-width:540px;margin:0 auto;">
      TIP Student Marketplace was built as a Platech Finals project by three Computer Science students from the Technological Institute of the Philippines.
    </p>
  </div>
</section>

<!-- ════ TEAM CARDS ════ -->
<section class="py-5">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="section-title">Our <span>Team</span></h2>
      <div class="section-divider mx-auto"></div>
    </div>

    <div class="row g-4 justify-content-center">

      <div class="col-sm-6 col-md-4">
        <div class="dev-card">
          <div class="dev-avatar"><i class="fa-solid fa-user"></i></div>
          <h5 class="fw-bold mb-1">Amiel Lapuz</h5>
          <p class="text-muted small mb-0">Full-Stack Developer</p>
          <hr class="my-2">
          <small class="text-muted">Database Design &amp; Backend Logic</small>
        </div>
      </div>

      <div class="col-sm-6 col-md-4">
        <div class="dev-card">
          <div class="dev-avatar"><i class="fa-solid fa-user"></i></div>
          <h5 class="fw-bold mb-1">Harold Bermudez</h5>
          <p class="text-muted small mb-0">Full-Stack Developer</p>
          <hr class="my-2">
          <small class="text-muted">Authentication &amp; CRUD Operations</small>
        </div>
      </div>

      <div class="col-sm-6 col-md-4">
        <div class="dev-card">
          <div class="dev-avatar"><i class="fa-solid fa-user"></i></div>
          <h5 class="fw-bold mb-1">Justin Tania</h5>
          <p class="text-muted small mb-0">Full-Stack Developer</p>
          <hr class="my-2">
          <small class="text-muted">UI Design &amp; AWS Deployment</small>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════ FEATURES ════ -->
<section class="py-5" style="background:#fff;">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="section-title">System <span>Features</span></h2>
      <div class="section-divider mx-auto"></div>
    </div>

    <div class="row g-4 text-center">

      <div class="col-sm-6 col-md-3">
        <div class="feature-icon"><i class="fa-solid fa-database"></i></div>
        <h6 class="fw-bold">MySQL Database</h6>
        <p class="text-muted small">Persistent storage for users, products, and transaction records.</p>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="feature-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
        <h6 class="fw-bold">CRUD Operations</h6>
        <p class="text-muted small">Full Create, Read, Update, and Delete functionality for listings.</p>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <h6 class="fw-bold">Secure Auth</h6>
        <p class="text-muted small">Session-based login with hashed passwords using PHP's <code>password_hash()</code>.</p>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="feature-icon"><i class="fa-brands fa-aws"></i></div>
        <h6 class="fw-bold">AWS Deployment</h6>
        <p class="text-muted small">Hosted on Amazon Web Services for reliable cloud access.</p>
      </div>

    </div>
  </div>
</section>

<!-- ════ BACK BUTTON ════ -->
<div class="text-center py-5">
  <a href="index.php" class="btn fw-bold px-5 py-2" style="background:var(--tip-gold);color:#111;border-radius:10px;font-size:1rem;">
    <i class="fa-solid fa-arrow-left me-2"></i>Back to Marketplace
  </a>
</div>

<?php include 'footer.php'; ?>
