<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'header.php'; ?>

<section class="py-5">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="section-title">List an <span>Item</span></h2>
      <div class="section-divider mx-auto"></div>
      <p class="text-muted" style="font-size:0.92rem;">Fill in the details below to post your item for sale.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success d-flex align-items-center gap-2 mb-4"
           style="max-width:600px;margin:0 auto 1.5rem;">
        <i class="fa-solid fa-circle-check fa-lg"></i>
        <span><strong>Item posted!</strong> Your listing is now live on the marketplace.</span>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2 mb-4"
           style="max-width:600px;margin:0 auto 1.5rem;">
        <i class="fa-solid fa-circle-exclamation fa-lg"></i>
        <span>Something went wrong. Please try again.</span>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <form action="upload_process.php" method="POST" enctype="multipart/form-data">

        <!-- IMAGE UPLOAD -->
        <div class="mb-4">
          <label class="form-label">
            <i class="fa-solid fa-image me-1" style="color:var(--tip-maroon)"></i>Product Photo
            <span class="text-muted fw-normal" style="font-size:0.8rem;">(optional — JPG, PNG, WEBP)</span>
          </label>

          <div id="dropZone"
               onclick="document.getElementById('image').click()"
               style="
                 border: 2.5px dashed #ddd;
                 border-radius: 12px;
                 padding: 32px 20px;
                 text-align: center;
                 cursor: pointer;
                 background: #fafafa;
                 transition: border-color 0.2s, background 0.2s;
                 position: relative;
               ">

            <div id="dropPlaceholder">
              <div style="font-size:2.8rem;color:#ccc;margin-bottom:10px;">
                <i class="fa-solid fa-cloud-arrow-up"></i>
              </div>
              <p class="mb-1 fw-bold" style="color:#555;font-size:0.95rem;">Click to upload or drag &amp; drop</p>
              <p class="mb-0 text-muted" style="font-size:0.8rem;">JPG, PNG, WEBP accepted</p>
            </div>

            <div id="previewWrapper" style="display:none;">
              <img id="previewImg" src="" alt="Preview"
                   style="max-height:260px;max-width:100%;border-radius:10px;
                          object-fit:contain;box-shadow:0 4px 18px rgba(0,0,0,0.12);">
              <p id="previewName" class="mt-2 mb-0 text-muted" style="font-size:0.8rem;"></p>
              <button type="button"
                      onclick="removeImage(event)"
                      style="position:absolute;top:10px;right:12px;
                             background:rgba(0,0,0,0.6);color:#fff;
                             border:none;border-radius:50%;width:30px;height:30px;
                             font-size:0.8rem;cursor:pointer;
                             display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>

          </div>

          <input type="file"
                 id="image"
                 name="image"
                 accept="image/jpeg,image/png,image/webp,image/gif"
                 style="display:none;"
                 onchange="previewImage(this)">
        </div>

        <div class="mb-3">
          <label class="form-label" for="title">
            <i class="fa-solid fa-tag me-1" style="color:var(--tip-maroon)"></i>Item Name
          </label>
          <input type="text" class="form-control" id="title" name="title"
                 placeholder="e.g. Calculus Textbook, ML Account, Adobo Meal" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="description">
            <i class="fa-solid fa-align-left me-1" style="color:var(--tip-maroon)"></i>Description
          </label>
          <textarea class="form-control" id="description" name="description" rows="4"
                    placeholder="Describe the item — condition, details, what's included, etc."></textarea>
        </div>

        <div class="mb-4">
          <label class="form-label" for="price">
            <i class="fa-solid fa-peso-sign me-1" style="color:var(--tip-maroon)"></i>Price (₱)
          </label>
          <div class="input-group">
            <span class="input-group-text"
                  style="background:var(--tip-black);color:var(--tip-gold);border-color:#ddd;">₱</span>
            <input type="number" class="form-control" id="price" name="price"
                   placeholder="0.00" min="1" step="0.01" required>
          </div>
        </div>

        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-tip-primary flex-fill">
            <i class="fa-solid fa-upload me-2"></i>Post Item
          </button>
          <a href="index.php" class="btn btn-outline-secondary flex-fill"
             style="border-radius:8px;font-weight:700;">Cancel</a>
        </div>

      </form>
    </div>

    <script>
      const dropZone = document.getElementById('dropZone');

      function previewImage(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        // NO size limit — PHP handles it via php.ini
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('previewImg').src = e.target.result;
          document.getElementById('previewName').textContent =
            file.name + ' (' +
            (file.size >= 1024 * 1024
              ? (file.size / 1024 / 1024).toFixed(2) + ' MB'
              : (file.size / 1024).toFixed(1) + ' KB') +
            ')';
          document.getElementById('dropPlaceholder').style.display = 'none';
          document.getElementById('previewWrapper').style.display  = 'block';
          dropZone.style.borderColor = 'var(--tip-gold)';
          dropZone.style.background  = '#fffdf0';
        };
        reader.readAsDataURL(file);
      }

      function removeImage(e) {
        e.stopPropagation();
        document.getElementById('image').value = '';
        document.getElementById('previewImg').src = '';
        document.getElementById('dropPlaceholder').style.display = 'block';
        document.getElementById('previewWrapper').style.display  = 'none';
        dropZone.style.borderColor = '#ddd';
        dropZone.style.background  = '#fafafa';
      }

      dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.style.borderColor = 'var(--tip-gold)';
        dropZone.style.background  = '#fffdf0';
      });
      dropZone.addEventListener('dragleave', e => {
        if (document.getElementById('previewWrapper').style.display === 'none') {
          dropZone.style.borderColor = '#ddd';
          dropZone.style.background  = '#fafafa';
        }
      });
      dropZone.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
          const dt    = new DataTransfer();
          dt.items.add(file);
          const input = document.getElementById('image');
          input.files = dt.files;
          previewImage(input);
        }
      });
    </script>

  </div>
</section>

<?php include 'footer.php'; ?>