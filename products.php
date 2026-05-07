<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
$page_title = 'Products & Services';
$page_desc  = 'Browse genuine antivirus software, storage drives, networking hardware, printers, and more from Seastar Technology — authorized US reseller.';
$all_products = get_all_products();
$categories   = get_categories();
$active_cat   = isset($_GET['cat']) ? urldecode($_GET['cat']) : 'All';
include 'includes/header.php';
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJT36XWT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<section class="page-hero page-hero--sm">
  <div class="container">
    <h1>Products &amp; Services</h1>
    <p>Genuine technology products from the world's most trusted brands — authorized US reseller with fast shipping and 30-day returns.</p>
  </div>
</section>

<section class="section" id="products-listing">
  <div class="container">
    <!-- Category Filter -->
    <div class="category-filters" id="category-filters">
      <button class="filter-btn <?php echo $active_cat==='All'?'active':''; ?>" data-cat="All">All</button>
      <?php foreach($categories as $cat): ?>
      <button class="filter-btn <?php echo $active_cat===$cat?'active':''; ?>" data-cat="<?php echo htmlspecialchars($cat); ?>">
        <?php echo htmlspecialchars($cat); ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Products Grid -->
    <div class="products-grid" id="products-grid">
      <?php foreach($all_products as $p): ?>
      <div class="product-card" data-category="<?php echo htmlspecialchars($p['category']); ?>">
        <?php if(!empty($p['badge'])): ?>
          <div class="product-badge"><?php echo htmlspecialchars($p['badge']); ?></div>
        <?php endif; ?>
        <div class="product-img-wrap">
          <img src="<?php echo htmlspecialchars($p['image']); ?>"
               alt="<?php echo htmlspecialchars($p['title']); ?>"
               onerror="this.src='assets/images/icons/product-placeholder.svg'" loading="lazy">
        </div>
        <div class="product-card-body">
          <div class="product-brand"><?php echo htmlspecialchars($p['brand']); ?></div>
          <h3 class="product-name"><?php echo htmlspecialchars($p['title']); ?></h3>
          <p class="product-short-desc"><?php echo htmlspecialchars($p['short_desc']); ?></p>
          <p class="product-reseller-note">Sold by SEASTAR TECHNOLOGIES LLC. Not affiliated with <?php echo htmlspecialchars($p['brand']); ?>.</p>
        </div>
        <div class="product-card-footer">
          <div>
            <div class="product-price">$<?php echo htmlspecialchars($p['price']); ?></div>
            <small class="product-stock-status"><i class="fas fa-circle-check" style="color:#22c55e;font-size:0.7rem;"></i> In Stock &middot; Ships 1–2 days</small>
          </div>
          <a href="product-details.php?slug=<?php echo urlencode($p['slug']); ?>" class="btn btn-primary btn-sm">View Details</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div id="no-results" class="no-results" style="display:none;">
      <i class="fas fa-box-open"></i>
      <p>No products found in this category.</p>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
