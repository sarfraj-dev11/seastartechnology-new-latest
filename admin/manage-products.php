<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
if (empty($_SESSION['mcc_admin'])) { header('Location: login.php'); exit; }

function delete_product_image($imagePath, $allProducts, $excludeSlug = '') {
    if (empty($imagePath) || $imagePath === 'assets/images/icons/product-placeholder.svg') {
        return;
    }
    // Check if another product uses it
    foreach ($allProducts as $p) {
        if ($p['slug'] !== $excludeSlug && isset($p['image']) && $p['image'] === $imagePath) {
            return;
        }
    }
    $fullPath = '../' . $imagePath;
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}

function handle_image_upload($fileArray) {
    if (isset($fileArray) && $fileArray['error'] === UPLOAD_ERR_OK) {
        $name = basename($fileArray['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
            return false;
        }
        
        $targetDir = '../assets/images/products/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $targetFile = $targetDir . $name;
        // Optional: you could make sure not to overwrite blindly, but here it's fine.
        if (move_uploaded_file($fileArray['tmp_name'], $targetFile)) {
            return 'assets/images/products/' . $name;
        }
    }
    return false;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $slug = $_POST['slug'] ?? '';
    $p = get_product_by_slug($slug);
    
    if ($p) {
        $allProducts = get_all_products();
        delete_product_image($p['image'] ?? '', $allProducts, $slug);
        // Delete gallery images if any
        if (!empty($p['gallery_images']) && is_array($p['gallery_images'])) {
            foreach ($p['gallery_images'] as $gImg) {
                delete_product_image($gImg, $allProducts, $slug);
            }
        }
        delete_product($slug);
        $message = 'Product and associated image deleted successfully.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_product'])) {
    $title = trim($_POST['title'] ?? 'New Product');

    $new_product = [
        'title'        => $title,
        'brand'        => trim($_POST['brand'] ?? ''),
        'category'     => trim($_POST['category'] ?? 'Uncategorized'),
        'price'        => trim($_POST['price'] ?? '0.00'),
        'badge'        => trim($_POST['badge'] ?? ''),
        'short_desc'   => trim($_POST['short_desc'] ?? ''),
        'long_desc'    => trim($_POST['long_desc'] ?? ''),
        'description1' => trim($_POST['description1'] ?? ''),
        'description2' => trim($_POST['description2'] ?? ''),
        'editorDisc'   => trim($_POST['editorDisc'] ?? ''),
        'problem_solved'  => [],
        'whats_included'  => [],
        'specs'           => [],
        'image'           => 'assets/images/icons/product-placeholder.svg',
        'gallery_images'  => [],
        'related'         => [],
    ];

    // whats_included
    if (isset($_POST['whats_included'])) {
        $lines = array_map('trim', explode("\n", $_POST['whats_included']));
        $new_product['whats_included'] = array_values(array_filter($lines));
    }
    // problem_solved
    if (isset($_POST['problem_solved'])) {
        $lines = array_map('trim', explode("\n", $_POST['problem_solved']));
        $new_product['problem_solved'] = array_values(array_filter($lines));
    }
    // specs
    if (isset($_POST['specs'])) {
        $specs = [];
        foreach (explode("\n", $_POST['specs']) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $specs[trim($parts[0])] = trim($parts[1]);
            }
        }
        $new_product['specs'] = $specs;
    }

    // Image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $imgPath = handle_image_upload($_FILES['product_image']);
        if ($imgPath) {
            $new_product['image'] = $imgPath;
        }
    }

    // Gallery images upload
    $gallery_images = [];
    for ($i = 1; $i <= 4; $i++) {
        if (isset($_FILES['gallery_image_'.$i]) && $_FILES['gallery_image_'.$i]['error'] === UPLOAD_ERR_OK) {
            $gPath = handle_image_upload($_FILES['gallery_image_'.$i]);
            if ($gPath) {
                $gallery_images[] = $gPath;
            }
        }
    }
    $new_product['gallery_images'] = $gallery_images;

    $newId = create_product($new_product);
    if ($newId > 0) {
        $message = 'New product created successfully.';
    } else {
        $message = 'Error creating product. Please try again.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $slug = $_POST['slug'] ?? '';
    $current = get_product_by_slug($slug);

    if ($current) {
        $allProducts = null; // Lazy-loaded only if needed for image operations

        $updated = [
            'title'        => trim($_POST['title']      ?? $current['title']),
            'price'        => trim($_POST['price']      ?? $current['price']),
            'badge'        => trim($_POST['badge']      ?? ''),
            'short_desc'   => trim($_POST['short_desc'] ?? $current['short_desc']),
            'long_desc'    => trim($_POST['long_desc']  ?? $current['long_desc']),
            'description1' => trim($_POST['description1'] ?? ($current['description1'] ?? '')),
            'description2' => trim($_POST['description2'] ?? ($current['description2'] ?? '')),
            'editorDisc'   => trim($_POST['editorDisc'] ?? ($current['editorDisc'] ?? '')),
            'image'        => $current['image'],
            'problem_solved'  => $current['problem_solved'] ?? [],
            'whats_included'  => $current['whats_included'] ?? [],
            'specs'           => $current['specs'] ?? [],
            'gallery_images'  => $current['gallery_images'] ?? [],
            'related'         => $current['related'] ?? [],
        ];

        // whats_included — one per line
        if (isset($_POST['whats_included'])) {
            $lines = array_map('trim', explode("\n", $_POST['whats_included']));
            $updated['whats_included'] = array_values(array_filter($lines));
        }

        // problem_solved — one per line
        if (isset($_POST['problem_solved'])) {
            $lines = array_map('trim', explode("\n", $_POST['problem_solved']));
            $updated['problem_solved'] = array_values(array_filter($lines));
        }

        // specs — key: value per line
        if (isset($_POST['specs'])) {
            $specs = [];
            foreach (explode("\n", $_POST['specs']) as $line) {
                $line = trim($line);
                if ($line === '') continue;
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $specs[trim($parts[0])] = trim($parts[1]);
                }
            }
            $updated['specs'] = $specs;
        }

        // Image upload
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $imgPath = handle_image_upload($_FILES['product_image']);
            if ($imgPath) {
                $allProducts = get_all_products();
                delete_product_image($current['image'] ?? '', $allProducts, $slug);
                $updated['image'] = $imgPath;
            }
        }

        // Gallery images handling
        $current_gallery = $current['gallery_images'] ?? [];
        $updated_gallery = [];

        for ($i = 0; $i < 4; $i++) {
            $input_idx = $i + 1;
            $has_existing = isset($current_gallery[$i]);
            $existing_path = $has_existing ? $current_gallery[$i] : '';

            // Check if user wants to delete existing
            if ($has_existing && isset($_POST['delete_gallery_image_'.$input_idx])) {
                if ($allProducts === null) $allProducts = get_all_products();
                delete_product_image($existing_path, $allProducts, $slug);
                $existing_path = ''; // cleared
            }

            // Check if new file uploaded
            if (isset($_FILES['gallery_image_'.$input_idx]) && $_FILES['gallery_image_'.$input_idx]['error'] === UPLOAD_ERR_OK) {
                $new_path = handle_image_upload($_FILES['gallery_image_'.$input_idx]);
                if ($new_path) {
                    // Delete old one if replaced
                    if ($existing_path) {
                        if ($allProducts === null) $allProducts = get_all_products();
                        delete_product_image($existing_path, $allProducts, $slug);
                    }
                    $existing_path = $new_path;
                }
            }

            if ($existing_path) {
                $updated_gallery[] = $existing_path;
            }
        }
        $updated['gallery_images'] = array_values($updated_gallery);

        update_product($slug, $updated);
        $message = 'Product updated successfully.';
    }
}


$products = get_all_products();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Products — MCC Admin</title>
  
  <meta name="robots" content="noarchive,nocache">
  <meta name="googlebot" content="noarchive,nocache">
  <meta name="bingbot" content="noarchive,nocache">
  <meta name="adsbot-google" content="noarchive,nocache">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="admin.css">
  <!-- CKEditor 5 Classic CDN -->
  <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
  <style>
    .ck-editor__editable {
      min-height: 200px;
      color: #000 !important;
    }
  </style>
</head>
<body>
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="logo"><i class="fas fa-laptop-medical"></i> MCC Admin</div>
    <nav>
      <a href="dashboard.php"><i class="fas fa-gauge-high"></i> Dashboard</a>
      <a href="manage-products.php" class="active"><i class="fas fa-boxes-stacked"></i> Products</a>
      <hr class="sidebar-divider">
      <a href="../index.php" target="_blank"><i class="fas fa-arrow-up-right-from-square"></i> View Site</a>
      <a href="logout.php"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <h1>Manage Products</h1>
    </div>

    <?php if ($message): ?>
      <div class="msg-success"><i class="fas fa-circle-check"></i> <?php echo $message; ?></div>
    <?php endif; ?>

    <div class="product-row" style="border: 2px dashed var(--border-lt); background: var(--bg);">
      <div class="product-row-header" onclick="this.nextElementSibling.classList.toggle('open')">
        <div>
          <h4><i class="fas fa-plus" style="color:var(--color-teal);margin-right:0.5rem"></i> Add New Product</h4>
          <div class="meta" style="color:var(--text-3)">Click to expand and create a new product</div>
        </div>
        <button class="toggle-btn" type="button" style="pointer-events: none;"><i class="fas fa-chevron-down"></i> Expand</button>
      </div>
      <div class="edit-form">
        <form method="POST" action="manage-products.php" enctype="multipart/form-data">
          <input type="hidden" name="create_product" value="1">
          <div class="form-grid">
            <div class="form-group">
              <label>Title</label>
              <input type="text" name="title" required placeholder="Product Title">
            </div>
            <div class="form-group">
              <label>Brand</label>
              <input type="text" name="brand" placeholder="e.g. McAfee">
            </div>
            <div class="form-group">
              <label>Category</label>
              <input type="text" name="category" placeholder="e.g. Security Software" required>
            </div>
            <div class="form-group">
              <label>Price (USD)</label>
              <input type="text" name="price" placeholder="0.00" required>
            </div>
            <div class="form-group">
              <label>Badge (e.g. Best Seller)</label>
              <input type="text" name="badge" placeholder="Optional">
            </div>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Short Description</label>
            <textarea name="short_desc" rows="2"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Long Description (About This Product)</label>
            <textarea name="long_desc" rows="4"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Description 1 (Additional details)</label>
            <textarea name="description1" rows="4"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Description 2 (Additional details)</label>
            <textarea name="description2" rows="4"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Editor Description (editorDisc) - HTML WYSIWYG Editor</label>
            <textarea name="editorDisc" class="ckeditor-textarea" rows="6" placeholder="Write rich description here..."></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>What's Included <small style="font-weight:400;color:#888">(one item per line)</small></label>
            <textarea name="whats_included" rows="4"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Problems This Product Solves <small style="font-weight:400;color:#888">(one per line)</small></label>
            <textarea name="problem_solved" rows="4"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Specifications <small style="font-weight:400;color:#888">(Key: Value, one per line)</small></label>
            <textarea name="specs" rows="5"></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Main Product Image <small style="font-weight:400;color:#888">(Optional)</small></label>
            <input type="file" name="product_image" accept="image/*" style="display:block;margin-top:.3rem">
          </div>

          <div style="margin-top:1.5rem; border-top:1px solid var(--border-lt); padding-top:1rem;">
            <h5 style="margin-bottom:1rem; font-size:.9rem; color:var(--text);"><i class="fas fa-images"></i> Product Gallery (Up to 4 images)</h5>
            <div class="form-grid">
              <div class="form-group">
                <label>Gallery Image 1</label>
                <input type="file" name="gallery_image_1" accept="image/*" style="display:block;margin-top:.3rem">
              </div>
              <div class="form-group">
                <label>Gallery Image 2</label>
                <input type="file" name="gallery_image_2" accept="image/*" style="display:block;margin-top:.3rem">
              </div>
              <div class="form-group">
                <label>Gallery Image 3</label>
                <input type="file" name="gallery_image_3" accept="image/*" style="display:block;margin-top:.3rem">
              </div>
              <div class="form-group">
                <label>Gallery Image 4</label>
                <input type="file" name="gallery_image_4" accept="image/*" style="display:block;margin-top:.3rem">
              </div>
            </div>
          </div>

          <button type="submit" class="btn-admin" style="margin-top:1.2rem; background: var(--color-teal); border: none;">
            <i class="fas fa-plus"></i> Create Product
          </button>
        </form>
      </div>
    </div>

    <?php foreach ($products as $p): ?>
    <div class="product-row">
      <div class="product-row-header" onclick="this.nextElementSibling.classList.toggle('open')">
        <div style="display:flex; align-items:center; gap: 1rem;">
          <?php if (!empty($p['image'])): ?>
            <img src="../<?php echo htmlspecialchars($p['image']); ?>" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-lt);">
          <?php endif; ?>
          <div>
            <h4><?php echo htmlspecialchars($p['title']); ?></h4>
            <div class="meta"><?php echo htmlspecialchars($p['category']); ?> — $<?php echo $p['price']; ?><?php echo $p['badge'] ? ' &nbsp;·&nbsp; ' . htmlspecialchars($p['badge']) : ''; ?></div>
          </div>
        </div>
        <div style="display:flex; gap:0.5rem;">
          <a href="../product-details.php?slug=<?php echo urlencode($p['slug']); ?>" target="_blank" class="toggle-btn" style="text-decoration: none; color: inherit;"><i class="fas fa-eye"></i> Preview</a>
          <button class="toggle-btn" type="button"><i class="fas fa-pen"></i> Edit</button>
        </div>
      </div>
      <div class="edit-form">
        <form method="POST" action="manage-products.php" enctype="multipart/form-data">
          <input type="hidden" name="save_product" value="1">
          <input type="hidden" name="slug" value="<?php echo htmlspecialchars($p['slug']); ?>">
          <div class="form-grid">
            <div class="form-group">
              <label>Title</label>
              <input type="text" name="title" value="<?php echo htmlspecialchars($p['title']); ?>">
            </div>
            <div class="form-group">
              <label>Price (USD)</label>
              <input type="text" name="price" value="<?php echo htmlspecialchars($p['price']); ?>">
            </div>
            <div class="form-group">
              <label>Badge (e.g. Best Seller)</label>
              <input type="text" name="badge" value="<?php echo htmlspecialchars($p['badge'] ?? ''); ?>">
            </div>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Short Description</label>
            <textarea name="short_desc" rows="2"><?php echo htmlspecialchars($p['short_desc']); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Long Description (About This Product)</label>
            <textarea name="long_desc" rows="4"><?php echo htmlspecialchars($p['long_desc'] ?? ''); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Description 1 (Additional details)</label>
            <textarea name="description1" rows="4"><?php echo htmlspecialchars($p['description1'] ?? ''); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Description 2 (Additional details)</label>
            <textarea name="description2" rows="4"><?php echo htmlspecialchars($p['description2'] ?? ''); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Editor Description (editorDisc) - HTML WYSIWYG Editor</label>
            <textarea name="editorDisc" class="ckeditor-textarea" rows="6"><?php echo htmlspecialchars($p['editorDisc'] ?? ''); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>What's Included <small style="font-weight:400;color:#888">(one item per line)</small></label>
            <textarea name="whats_included" rows="4"><?php echo htmlspecialchars(implode("\n", $p['whats_included'] ?? [])); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Problems This Product Solves <small style="font-weight:400;color:#888">(one per line)</small></label>
            <textarea name="problem_solved" rows="4"><?php echo htmlspecialchars(implode("\n", $p['problem_solved'] ?? [])); ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Specifications <small style="font-weight:400;color:#888">(Key: Value, one per line)</small></label>
            <textarea name="specs" rows="5"><?php
              $specLines = [];
              foreach (($p['specs'] ?? []) as $k => $v) {
                  $specLines[] = $k . ': ' . $v;
              }
              echo htmlspecialchars(implode("\n", $specLines));
            ?></textarea>
          </div>
          <div class="form-group" style="margin-top:.75rem">
            <label>Main Product Image <small style="font-weight:400;color:#888">(Upload new to replace current)</small></label>
            <?php if (!empty($p['image'])): ?>
              <div style="margin-bottom:.5rem;">
                <img src="../<?php echo htmlspecialchars($p['image']); ?>" alt="Product Image" style="max-width: 120px; max-height: 120px; border-radius: 6px; border: 1px solid var(--border-lt); display: block; object-fit: contain; background: #fff;">
              </div>
            <?php endif; ?>
            <input type="file" name="product_image" accept="image/*" style="display:block;margin-top:.3rem">
          </div>

          <div style="margin-top:1.5rem; border-top:1px solid var(--border-lt); padding-top:1rem;">
            <h5 style="margin-bottom:1rem; font-size:.9rem; color:var(--text);"><i class="fas fa-images"></i> Product Gallery (Up to 4 images)</h5>
            <div class="form-grid">
              <?php 
                $gImages = isset($p['gallery_images']) && is_array($p['gallery_images']) ? $p['gallery_images'] : [];
                for ($i = 1; $i <= 4; $i++): 
                  $gImg = $gImages[$i-1] ?? null;
              ?>
              <div class="form-group" style="background:var(--surface); padding:1rem; border-radius:6px; border:1px solid var(--border-lt);">
                <label>Gallery Image <?php echo $i; ?></label>
                <?php if ($gImg): ?>
                  <div style="margin: .5rem 0;">
                    <img src="../<?php echo htmlspecialchars($gImg); ?>" alt="Gallery Image <?php echo $i; ?>" style="max-width: 80px; max-height: 80px; border-radius: 4px; border: 1px solid var(--border-lt); display: block; object-fit: contain; background: #fff;">
                  </div>
                  <label style="display:flex; align-items:center; gap:.4rem; font-weight:normal; font-size:.8rem; margin-bottom:.5rem; color:#ef4444; cursor:pointer;">
                    <input type="checkbox" name="delete_gallery_image_<?php echo $i; ?>" value="1"> Delete this image
                  </label>
                <?php endif; ?>
                <label style="font-size:.75rem; color:var(--text-3); font-weight:normal; display:block; margin-bottom:.3rem;">
                  <?php echo $gImg ? 'Replace with new:' : 'Upload image:'; ?>
                </label>
                <input type="file" name="gallery_image_<?php echo $i; ?>" accept="image/*" style="display:block;">
              </div>
              <?php endfor; ?>
            </div>
          </div>

          <div style="display:flex; gap:1rem; align-items:center; margin-top:1.2rem">
            <button type="submit" class="btn-admin">
              <i class="fas fa-floppy-disk"></i> Save Changes
            </button>
            <button type="button" class="btn-admin" style="background:#ef4444; border-color:#ef4444" onclick="if(confirm('Are you sure you want to delete this product? This action cannot be undone.')){ document.getElementById('delete-<?php echo htmlspecialchars($p['slug']); ?>').submit(); }">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </form>
        <form id="delete-<?php echo htmlspecialchars($p['slug']); ?>" method="POST" action="manage-products.php" style="display:none;">
          <input type="hidden" name="delete_product" value="1">
          <input type="hidden" name="slug" value="<?php echo htmlspecialchars($p['slug']); ?>">
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </main>
</div>
<script>
  document.querySelectorAll('.ckeditor-textarea').forEach(function(textarea) {
    ClassicEditor
      .create(textarea, {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
      })
      .catch(error => {
        console.error('Error initializing CKEditor:', error);
      });
  });
</script>
</body>
</html>
