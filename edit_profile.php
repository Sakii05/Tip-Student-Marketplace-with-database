<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$uid  = intval($_SESSION['user_id']);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));
if (!$user) { header("Location: logout.php"); exit(); }

$user_bio      = $user['bio']            ?? '';
$user_location = $user['location']       ?? '';
$user_avatar   = $user['profile_image']  ?? '';
$user_banner   = $user['banner_image']   ?? '';
?>
<?php include 'header.php'; ?>

<section class="py-5">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="section-title">Edit <span>Profile</span></h2>
      <div class="section-divider mx-auto"></div>
    </div>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success d-flex align-items-center gap-2 mb-4"
           style="max-width:600px;margin:0 auto 1.5rem;">
        <i class="fa-solid fa-circle-check fa-lg"></i>
        <span><strong>Profile updated!</strong> Your changes have been saved.</span>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2 mb-4"
           style="max-width:600px;margin:0 auto 1.5rem;">
        <i class="fa-solid fa-circle-exclamation fa-lg"></i>
        <span>Could not save changes. Please try again.</span>
      </div>
    <?php endif; ?>

    <div class="form-card" style="max-width:640px;">
      <form action="edit_profile_process.php" method="POST" enctype="multipart/form-data">

        <!-- BANNER UPLOAD -->
        <div class="mb-4">
          <label class="form-label">
            <i class="fa-solid fa-panorama me-1" style="color:var(--tip-maroon)"></i>Profile Banner
            <span class="text-muted fw-normal" style="font-size:0.8rem;">(optional — JPG, PNG, WEBP — recommended 1200×300)</span>
          </label>

          <!-- Banner Preview -->
          <div id="bannerBox"
               onclick="document.getElementById('banner_image').click()"
               style="width:100%;height:140px;border-radius:12px;overflow:hidden;
                      cursor:pointer;position:relative;margin-bottom:8px;
                      border:2.5px dashed #ddd;transition:border-color 0.2s;">
            <?php if (!empty($user_banner) && file_exists(__DIR__ . '/' . $user_banner)): ?>
              <img id="bannerPreview"
                   src="<?php echo htmlspecialchars($user_banner); ?>"
                   style="width:100%;height:140px;object-fit:cover;display:block;">
            <?php else: ?>
              <div id="bannerPlaceholder"
                   style="width:100%;height:100%;background:linear-gradient(135deg,var(--tip-black),var(--tip-maroon));
                          display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                <i class="fa-solid fa-image" style="font-size:2rem;color:rgba(245,196,0,0.5);"></i>
                <span style="color:rgba(255,255,255,0.4);font-size:0.8rem;font-weight:600;">
                  Click to upload banner
                </span>
              </div>
              <img id="bannerPreview" src="" style="width:100%;height:140px;object-fit:cover;display:none;">
            <?php endif; ?>
            <!-- Overlay hint -->
            <div style="position:absolute;inset:0;background:rgba(0,0,0,0);
                        display:flex;align-items:center;justify-content:center;
                        transition:background 0.2s;"
                 onmouseover="this.style.background='rgba(0,0,0,0.35)'"
                 onmouseout="this.style.background='rgba(0,0,0,0)'">
              <span style="color:#fff;font-weight:700;font-size:0.85rem;opacity:0;transition:opacity 0.2s;"
                    onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                <i class="fa-solid fa-camera me-1"></i>Change Banner
              </span>
            </div>
          </div>
          <input type="file" id="banner_image" name="banner_image"
                 accept="image/jpeg,image/png,image/webp"
                 style="display:none;" onchange="previewBanner(this)">
          <small class="text-muted">Click the banner above to change it</small>
        </div>

        <hr class="my-4">

        <!-- AVATAR -->
        <div class="mb-4 text-center">
          <div class="mb-3">
            <?php if (!empty($user_avatar) && file_exists(__DIR__ . '/' . $user_avatar)): ?>
              <img id="avatarPreview"
                   src="<?php echo htmlspecialchars($user_avatar); ?>"
                   class="profile-avatar"
                   style="width:100px;height:100px;margin:0 auto;">
            <?php else: ?>
              <div id="avatarPlaceholder"
                   class="profile-avatar-placeholder"
                   style="width:100px;height:100px;font-size:2.2rem;margin:0 auto;">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
              </div>
              <img id="avatarPreview" src=""
                   style="width:100px;height:100px;border-radius:50%;object-fit:cover;
                          border:4px solid var(--tip-gold);display:none;margin:0 auto;">
            <?php endif; ?>
          </div>
          <label class="form-label">
            <i class="fa-solid fa-camera me-1" style="color:var(--tip-maroon)"></i>Profile Picture
          </label>
          <input type="file" class="form-control" name="profile_image" id="profile_image"
                 accept="image/jpeg,image/png,image/webp"
                 onchange="previewAvatar(this)">
          <small class="text-muted">JPG, PNG, or WEBP — square photo recommended</small>
        </div>

        <!-- FULL NAME (read only) -->
        <div class="mb-3">
          <label class="form-label">
            <i class="fa-solid fa-user me-1" style="color:var(--tip-maroon)"></i>Full Name
          </label>
          <input type="text" class="form-control"
                 value="<?php echo htmlspecialchars($user['full_name']); ?>"
                 disabled style="background:#f5f5f5;color:#888;">
          <small class="text-muted">Name cannot be changed after registration.</small>
        </div>

        <!-- BIO -->
        <div class="mb-3">
          <label class="form-label" for="bio">
            <i class="fa-solid fa-align-left me-1" style="color:var(--tip-maroon)"></i>Bio
          </label>
          <textarea class="form-control" id="bio" name="bio" rows="3"
                    placeholder="Tell other students about yourself…"><?php echo htmlspecialchars($user_bio); ?></textarea>
        </div>

        <!-- CAMPUS -->
        <div class="mb-4">
          <label class="form-label" for="location">
            <i class="fa-solid fa-location-dot me-1" style="color:var(--tip-maroon)"></i>Campus
          </label>
          <select class="form-select" id="location" name="location">
            <option value="">— Select Campus —</option>
            <?php foreach (['QC Campus','Manila Campus'] as $c): ?>
              <option value="<?php echo $c; ?>"
                      <?php echo $user_location === $c ? 'selected' : ''; ?>>
                <?php echo $c; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-tip-primary flex-fill">
            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
          </button>
          <a href="profile.php?id=<?php echo $uid; ?>"
             class="btn btn-outline-secondary flex-fill"
             style="border-radius:8px;font-weight:700;">
            Cancel
          </a>
        </div>

      </form>
    </div>
  </div>
</section>

<script>
function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const preview     = document.getElementById('avatarPreview');
    const placeholder = document.getElementById('avatarPlaceholder');
    preview.src           = e.target.result;
    preview.style.display = 'block';
    if (placeholder) placeholder.style.display = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}

function previewBanner(input) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const preview     = document.getElementById('bannerPreview');
    const placeholder = document.getElementById('bannerPlaceholder');
    preview.src           = e.target.result;
    preview.style.display = 'block';
    if (placeholder) placeholder.style.display = 'none';
    document.getElementById('bannerBox').style.borderColor = 'var(--tip-gold)';
  };
  reader.readAsDataURL(input.files[0]);
}
</script>

<?php include 'footer.php'; ?>