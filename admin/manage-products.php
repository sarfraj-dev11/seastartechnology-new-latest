<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
if (empty($_SESSION['mcc_admin'])) { header('Location: login.php'); exit; }

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $products = get_all_products();
    $slug = $_POST['slug'] ?? '';
    foreach ($products as &$p) {
        if ($p['slug'] === $slug) {
            $p['title']      = trim($_POST['title']      ?? $p['title']);
            $p['price']      = trim($_POST['price']      ?? $p['price']);
            $p['badge']      = trim($_POST['badge']      ?? '');
            $p['short_desc'] = trim($_POST['short_desc'] ?? $p['short_desc']);
            $p['long_desc']  = trim($_POST['long_desc']  ?? $p['long_desc']);

            // whats_included — one per line
            if (isset($_POST['whats_included'])) {
                $lines = array_map('trim', explode("\n", $_POST['whats_included']));
                $p['whats_included'] = array_values(array_filter($lines));
            }

            // problem_solved — one per line
            if (isset($_POST['problem_solved'])) {
                $lines = array_map('trim', explode("\n", $_POST['problem_solved']));
                $p['problem_solved'] = array_values(array_filter($lines));
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
                $p['specs'] = $specs;
            }

            break;
        }
    }
    unset($p);
    file_put_contents(DATA_PATH, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = 'Product updated successfully.';
}

$products = get_all_products();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Products — MCC Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="admin.css">
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

    <?php foreach ($products as $p): ?>
    <div class="product-row">
      <div class="product-row-header" onclick="this.nextElementSibling.classList.toggle('open')">
        <div>
          <h4><?php echo htmlspecialchars($p['title']); ?></h4>
          <div class="meta"><?php echo htmlspecialchars($p['category']); ?> — $<?php echo $p['price']; ?><?php echo $p['badge'] ? ' &nbsp;·&nbsp; ' . htmlspecialchars($p['badge']) : ''; ?></div>
        </div>
        <button class="toggle-btn" type="button"><i class="fas fa-pen"></i> Edit</button>
      </div>
      <div class="edit-form">
        <form method="POST" action="manage-products.php">
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
          <button type="submit" class="btn-admin" style="margin-top:.85rem">
            <i class="fas fa-floppy-disk"></i> Save Changes
          </button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </main>
</div>
</body>
</html>
