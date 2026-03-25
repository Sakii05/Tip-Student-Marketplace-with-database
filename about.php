<?php session_start(); ?>
<?php include 'header.php'; ?>

<!-- ABOUT HERO -->
<section class="about-hero">
  <div class="container text-center">
    <div style="font-size:3.5rem;color:var(--tip-gold);margin-bottom:14px;">
      <i class="fa-solid fa-people-group"></i>
    </div>
    <h1 class="fw-bold" style="font-family:'Bebas Neue',sans-serif;font-size:2.8rem;
        letter-spacing:2px;color:var(--tip-gold);">
      Meet the Developers
    </h1>
    <p class="mt-2" style="color:rgba(255,255,255,0.7);max-width:540px;margin:0 auto;">
      TIP Student Marketplace was built as a finals project for Information Management and 
      Platform Technologies by three Bachelor of Science in Information Technology (BSIT) students from the Technological Institute of the Philippines..
    </p>
  </div>
</section>

<!-- TEAM CARDS -->
<section class="py-5">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="section-title">Our <span>Team</span></h2>
      <div class="section-divider mx-auto"></div>
    </div>

    <div class="row g-4 justify-content-center">

      <!-- Amiel -->
      <div class="col-sm-6 col-md-4">
        <div class="dev-card">
          <?php
            $amiel = file_exists(__DIR__.'/uploads/avatars/amiel.jpg')
              ? 'uploads/avatars/amiel.jpg'
              : (file_exists(__DIR__.'/uploads/avatars/amiel.jpeg')
              ? 'uploads/avatars/amiel.jpeg' : '');
          ?>
          <?php if ($amiel): ?>
            <img src="<?php echo $amiel; ?>"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                        border:3px solid var(--tip-gold);margin:0 auto 14px;display:block;">
          <?php else: ?>
            <div class="dev-avatar"><i class="fa-solid fa-user"></i></div>
          <?php endif; ?>
          <h5 class="fw-bold mb-1">Amiel Lapuz</h5>
          <p class="text-muted small mb-0">DevOps & Cloud Engineer</p>
          <hr class="my-2">
          <small class="text-muted">Cloud Infrastructure &amp; AWS Deployment</small>
        </div>
      </div>

      <!-- Harold -->
      <div class="col-sm-6 col-md-4">
        <div class="dev-card">
          <?php
            $harold = file_exists(__DIR__.'/uploads/avatars/harold.jpg')
              ? 'uploads/avatars/harold.jpg'
              : (file_exists(__DIR__.'/uploads/avatars/harold.jpeg')
              ? 'uploads/avatars/harold.jpeg' : '');
          ?>
          <?php if ($harold): ?>
            <img src="<?php echo $harold; ?>"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                        border:3px solid var(--tip-gold);margin:0 auto 14px;display:block;">
          <?php else: ?>
            <div class="dev-avatar"><i class="fa-solid fa-user"></i></div>
          <?php endif; ?>
          <h5 class="fw-bold mb-1">Harold Bermudez</h5>
          <p class="text-muted small mb-0">Lead Full-Stack Developer</p>
          <hr class="my-2">
          <small class="text-muted">System Architecture &amp;  Database Implementation &amp; CRUD Operations</small>
        </div>
      </div>

      <!-- Justin -->
      <div class="col-sm-6 col-md-4">
        <div class="dev-card">
          <?php
            $justin = file_exists(__DIR__.'/uploads/avatars/justin.jpg')
              ? 'uploads/avatars/justin.jpg'
              : (file_exists(__DIR__.'/uploads/avatars/justin.jpeg')
              ? 'uploads/avatars/justin.jpeg' : '');
          ?>
          <?php if ($justin): ?>
            <img src="<?php echo $justin; ?>"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                        border:3px solid var(--tip-gold);margin:0 auto 14px;display:block;">
          <?php else: ?>
            <div class="dev-avatar"><i class="fa-solid fa-user"></i></div>
          <?php endif; ?>
          <h5 class="fw-bold mb-1">Justin Tania</h5>
          <p class="text-muted small mb-0">Technical Documentation Lead</p>
          <hr class="my-2">
          <small class="text-muted">System Documentation &amp; Quality Assurance</small>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FEATURES -->
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

<!-- BACK BUTTON -->
<div class="text-center py-5">
  <a href="index.php" class="btn fw-bold px-5 py-2"
     style="background:var(--tip-gold);color:#111;border-radius:10px;font-size:1rem;border:none;">
    <i class="fa-solid fa-arrow-left me-2"></i>Back to Marketplace
  </a>
</div>

<?php include 'footer.php'; ?>