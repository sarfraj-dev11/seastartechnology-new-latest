<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
if (empty($_SESSION['mcc_admin'])) { header('Location: login.php'); exit; }
$products = get_all_products();
$cats     = get_categories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — MCC Admin</title>
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
      <a href="dashboard.php" class="active"><i class="fas fa-gauge-high"></i> Dashboard</a>
      <a href="manage-products.php"><i class="fas fa-boxes-stacked"></i> Products</a>
      <hr class="sidebar-divider">
      <a href="../index.php" target="_blank"><i class="fas fa-arrow-up-right-from-square"></i> View Site</a>
      <a href="logout.php"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <h1>Dashboard</h1>
      <a href="manage-products.php" class="btn-admin"><i class="fas fa-pen-to-square"></i> Manage Products</a>
    </div>

    <div class="stat-cards">
      <div class="stat-card">
        <div class="stat-num"><?php echo count($products); ?></div>
        <div class="stat-label">Total Products</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo count($cats); ?></div>
        <div class="stat-label">Categories</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?php echo count(array_filter($products, fn($p) => !empty($p['badge']))); ?></div>
        <div class="stat-label">Featured / Badged</div>
      </div>
    </div>

    <div class="admin-table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Badge</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $i => $p): ?>
          <tr>
            <td style="color:var(--text-3);width:40px"><?php echo $i + 1; ?></td>
            <td>
              <div class="td-strong"><?php echo htmlspecialchars($p['title']); ?></div>
              <div class="td-muted"><?php echo htmlspecialchars($p['brand']); ?></div>
            </td>
            <td><?php echo htmlspecialchars($p['category']); ?></td>
            <td style="font-weight:600;color:var(--primary)">$<?php echo $p['price']; ?></td>
            <td><?php echo $p['badge'] ? htmlspecialchars($p['badge']) : '<span style="color:var(--text-3)">—</span>'; ?></td>
            <td>
              <a href="../product-details.php?slug=<?php echo $p['slug']; ?>" target="_blank" class="btn-admin btn-admin-outline" style="font-size:.76rem;padding:.28rem .7rem">
                <i class="fas fa-eye"></i> View
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
