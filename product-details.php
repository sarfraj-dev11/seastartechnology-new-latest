<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$p    = $slug ? get_product_by_slug($slug) : null;

if (!$p) {
  header('Location: products.php');
  exit;
}

$related  = get_related_products($p['related'] ?? []);
$page_title = htmlspecialchars($p['title']);
$page_desc  = htmlspecialchars($p['short_desc']);
$extra_head = '<script type="application/ld+json">' . json_encode([
  '@context' => 'https://schema.org/',
  '@type'    => 'Product',
  'name'     => $p['title'],
  'image'    => SITE_URL . '/' . $p['image'],
  'description' => $p['short_desc'],
  'brand'    => ['@type' => 'Brand', 'name' => $p['brand']],
  'offers'   => [
    '@type'         => 'Offer',
    'url'           => SITE_URL . '/product-details.php?slug=' . urlencode($slug),
    'priceCurrency' => 'USD',
    'price'         => $p['price'],
    'availability'  => 'https://schema.org/InStock',
    'seller'        => ['@type' => 'Organization', 'name' => 'SEASTAR TECHNOLOGIES LLC'],
  ],
]) . '</script>';
include 'includes/header.php';
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJT36XWT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<section class="page-hero page-hero--sm">
  <div class="container breadcrumb-bar">
    <a href="index.php">Home</a> <i class="fas fa-chevron-right"></i>
    <a href="products.php">Products</a> <i class="fas fa-chevron-right"></i>
    <a href="products.php?cat=<?php echo urlencode($p['category']); ?>"><?php echo htmlspecialchars($p['category']); ?></a> <i class="fas fa-chevron-right"></i>
    <span><?php echo htmlspecialchars($p['title']); ?></span>
  </div>
</section>

<section class="section product-detail-section">
  <div class="container product-detail-grid">

    <!-- LEFT: Image -->
    <div class="pd-image-col">
      <div class="pd-image-wrap">
        <?php if (!empty($p['badge'])): ?>
          <div class="pd-badge"><?php echo htmlspecialchars($p['badge']); ?></div>
        <?php endif; ?>
        <img id="main-product-image" src="<?php echo htmlspecialchars($p['image'] ?? 'assets/images/icons/product-placeholder.svg'); ?>"
          alt="<?php echo htmlspecialchars($p['title']); ?>"
          onerror="this.src='assets/images/icons/product-placeholder.svg'">
      </div>

      <?php 
      $gallery = $p['gallery_images'] ?? [];
      $all_images = array_filter(array_merge([$p['image'] ?? 'assets/images/icons/product-placeholder.svg'], $gallery));
      if (count($all_images) > 1): 
      ?>
        <div class="pd-thumbnails">
          <?php foreach ($all_images as $index => $gImg): ?>
            <div class="pd-thumbnail-wrap <?php echo $index === 0 ? 'active' : ''; ?>" onclick="swapMainImage(this, '<?php echo htmlspecialchars($gImg, ENT_QUOTES, 'UTF-8'); ?>')">
              <img src="<?php echo htmlspecialchars($gImg, ENT_QUOTES, 'UTF-8'); ?>" alt="Thumbnail" onerror="this.src='assets/images/icons/product-placeholder.svg'">
            </div>
          <?php endforeach; ?>
        </div>

        <script>
          function swapMainImage(thumbElement, newSrc) {
            // Update main image source
            document.getElementById('main-product-image').src = newSrc;

            // Update active class on thumbnails
            const thumbnails = document.querySelectorAll('.pd-thumbnail-wrap');
            thumbnails.forEach(t => t.classList.remove('active'));
            thumbElement.classList.add('active');
          }
        </script>
      <?php endif; ?>
    </div>

    <!-- RIGHT: Details -->
    <div class="pd-info-col">
      <div class="product-brand pd-brand"><?php echo htmlspecialchars($p['brand']); ?></div>
      <h1 class="pd-title"><?php echo htmlspecialchars($p['title']); ?></h1>
      <p class="pd-category"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($p['category']); ?></p>
      <div class="pd-price">$<?php echo htmlspecialchars($p['price']); ?></div>
      <p style="font-size:0.85rem;color:#22c55e;margin-bottom:0.5rem;"><i class="fas fa-circle-check"></i> In Stock &middot; License key sent to your email within 1 business day; physical media ships from our US warehouse in 1–2 business days.</p>
      <p class="pd-short-desc"><?php echo htmlspecialchars($p['short_desc']); ?></p>
      <p class="product-pricing">(* Other editions available on request; pricing may differ.)</p>

      <div class="pd-cta-group">
        <a href="mailto:<?php echo SITE_EMAIL; ?>?subject=Purchase%20Order%20%E2%80%93%20<?php echo rawurlencode($p['title']); ?>%20(%24<?php echo rawurlencode($p['price']); ?>)&body=Hi%20Seastar%20Technology%20Team%2C%0A%0AI%20would%20like%20to%20purchase%3A%0AProduct%3A%20<?php echo rawurlencode($p['title']); ?>%0APrice%3A%20%24<?php echo rawurlencode($p['price']); ?>%0A%0APlease%20send%20me%20a%20secure%20payment%20invoice.%0A%0AName%3A%0APhone%3A%0AShipping%20Address%20(if%20physical)%3A%0A%0AThank%20you."
          class="btn btn-primary btn-lg pd-cta">
          <i class="fas fa-cart-shopping"></i> Order Now — Request Secure Invoice
        </a>
        <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-outline btn-lg">
          <i class="fas fa-phone"></i> Call Sales: <?php echo SITE_PHONE; ?>
        </a>
      </div>
      <p style="font-size:.82rem;color:var(--text-3);margin-top:.6rem;">
        <i class="fas fa-circle-check" style="color:var(--success);"></i>
        We'll email a secure payment link within 1 business hour &middot; No account needed
      </p>

      <!-- Description -->
      <div class="pd-block">
        <h3><i class="fas fa-circle-info"></i> Description</h3>
        <p class="pd-short-desc" style="margin-bottom: 1.4rem;"><?php echo htmlspecialchars($p['description1']); ?></p>
        <?php if (!empty($p['description_blocks'])): ?>
          <div class="pd-desc-blocks">
            <?php foreach ($p['description_blocks'] as $block): ?>
              <p class="pd-desc-block-heading"><strong><?php echo htmlspecialchars($block['heading']); ?></strong></p>
              <ul class="pd-desc-block-list">
                <?php foreach ($block['items'] as $item): ?>
                  <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="pd-short-desc"><?php echo htmlspecialchars($p['description2']); ?></p>
        <?php endif; ?>
      </div>

      <!-- What's Included -->
      <div class="pd-block">
        <h3><i class="fas fa-box-open"></i> What's Included</h3>
        <ul class="pd-list">
          <?php foreach ($p['whats_included'] as $item): ?>
            <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($item); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>


      <!-- What's Included -->


      <!-- Activation Guidance USP -->
      <div class="pd-assistance-usp">
        <i class="fas fa-headset"></i>
        <div>
          <strong>Activation Guidance Included</strong>
          <p>One-time license activation guidance is included with eligible software purchases to confirm your license is registered correctly.</p>
        </div>
      </div>
      <?php if ($p['slug'] === 'windows-11-home-pro-usb'): ?>
        <p class="pd-physical-note" style="font-size:.82rem;color:#444;margin-top:.75rem;padding:.55rem .75rem;background:#f4fdf6;border-left:3px solid #22c55e;border-radius:3px;line-height:1.55;">
          <i class="fas fa-box-open" style="color:#22c55e;margin-right:.3rem;"></i>
          This is a sealed physical USB shipped from our US warehouse. We do not provide pirated or unauthorized license keys.
        </p>
      <?php endif; ?>
      <p class="product-reseller-note" style="font-size:0.8rem;color:#666;margin-top:.6rem;">Sold by SEASTAR TECHNOLOGIES LLC, an independent authorized reseller. Not affiliated with or endorsed by <?php echo htmlspecialchars($p['brand']); ?>.</p>
    </div>
  </div>
</section>

<!-- Specs -->
<?php if (!empty($p['specs'])): ?>
  <section class="section pd-specs-section">
    <div class="container">
      <h2 class="section-title">Specifications</h2>
      <div class="specs-table-wrap">
        <table class="specs-table">
          <?php foreach ($p['specs'] as $key => $val): ?>
            <tr>
              <th><?php echo htmlspecialchars($key); ?></th>
              <td><?php echo htmlspecialchars($val); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Key Features -->
<section class="section pd-problems-section">
  <div class="container">
    <h2 class="section-title">Key Features</h2>
    <div class="problems-grid">
      <?php foreach ($p['problem_solved'] as $feature): ?>
        <div class="problem-card">
          <i class="fas fa-check-circle" style="color:#22c55e;font-size:1.3rem;flex-shrink:0;"></i>
          <div class="problem-text">
            <span><?php echo htmlspecialchars($feature); ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Long Description -->
<section class="section pd-desc-section" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 4rem 0;">
  <div class="container pd-desc-inner">
    <h2 class="section-title">About This Product</h2>
    <p style="font-size: 1.05rem; line-height: 1.7; color: #4b5563; max-width: 800px; margin: 0 auto; text-align: center;"><?php echo htmlspecialchars($p['long_desc']); ?></p>
  </div>
</section>

<!-- How to Purchase -->
<section class="section pd-how-to-buy-section">
  <div class="container">
    <h2 class="section-title">How to Purchase From Us</h2>
    <p class="section-subtitle">Simple, secure, and transparent — here's how it works:</p>
    <div class="how-to-buy-steps">
      <div class="buy-step">
        <div class="buy-step-number">1</div>
        <div class="buy-step-content">
          <strong>Select Your Product</strong>
          <p>Browse our catalog and select the product that fits your needs.</p>
        </div>
      </div>
      <div class="buy-step">
        <div class="buy-step-number">2</div>
        <div class="buy-step-content">
          <strong>Request Invoice</strong>
          <p>Reach out to our sales representative directly to get expert guidance.</p>
        </div>
      </div>
      <div class="buy-step">
        <div class="buy-step-number">3</div>
        <div class="buy-step-content">
          <strong>Select your edition at checkout</strong>
          <p>We confirm your edition and quantity by email.</p>
        </div>
      </div>
      <div class="buy-step">
        <div class="buy-step-number">4</div>
        <div class="buy-step-content">
          <strong>Get Official Quote</strong>
          <p>We send you a formal pricing quote before any commitment is made.</p>
        </div>
      </div>
      <div class="buy-step">
        <div class="buy-step-number">5</div>
        <div class="buy-step-content">
          <strong>Secure Payment &amp; Invoice</strong>
          <p>Pay securely online. License key sent by email within 1 business day. Hardware ships in 1–2 business days.</p>
        </div>
      </div>
    </div>
  </div>
</section>




<!-- Related Products -->
<?php if (!empty($related)): ?>
  <section class="section related-section">
    <div class="container">
      <h2 class="section-title">Related Products</h2>
      <div class="products-grid">
        <?php foreach ($related as $rp): ?>
          <div class="product-card">
            <?php if (!empty($rp['badge'])): ?>
              <div class="product-badge"><?php echo htmlspecialchars($rp['badge']); ?></div>
            <?php endif; ?>
            <div class="product-img-wrap">
              <img src="<?php echo htmlspecialchars($rp['image']); ?>"
                alt="<?php echo htmlspecialchars($rp['title']); ?>"
                onerror="this.src='assets/images/icons/product-placeholder.svg'" loading="lazy">
            </div>
            <div class="product-card-body">
              <div class="product-brand"><?php echo htmlspecialchars($rp['brand']); ?></div>
              <h3 class="product-name"><?php echo htmlspecialchars($rp['title']); ?></h3>
              <p class="product-short-desc"><?php echo htmlspecialchars($rp['short_desc']); ?></p>
              <p class="product-reseller-note">Sold by SEASTAR TECHNOLOGIES LLC, an independent authorized reseller. Not affiliated with or endorsed by <?php echo htmlspecialchars($rp['brand']); ?>.</p>
            </div>
            <div class="product-card-footer">
              <div class="product-price">$<?php echo htmlspecialchars($rp['price']); ?></div>
              <a href="product-details.php?slug=<?php echo urlencode($rp['slug']); ?>" class="btn btn-primary btn-sm">View Details</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Sticky Mobile CTA -->
<div class="sticky-cta-mobile" id="sticky-cta">
  <a href="mailto:<?php echo SITE_EMAIL; ?>?subject=Purchase%20Order%20%E2%80%93%20<?php echo rawurlencode($p['title']); ?>%20(%24<?php echo rawurlencode($p['price']); ?>)&body=Hi%20Seastar%20Technology%20Team%2C%0A%0AI%20would%20like%20to%20purchase%3A%0AProduct%3A%20<?php echo rawurlencode($p['title']); ?>%0APrice%3A%20%24<?php echo rawurlencode($p['price']); ?>%0A%0APlease%20send%20me%20a%20secure%20payment%20invoice.%0A%0AName%3A%0APhone%3A%0AShipping%20Address%20(if%20physical)%3A%0A%0AThank%20you."
    class="btn btn-primary" style="flex:6;justify-content:center;">
    <i class="fas fa-cart-shopping"></i> Invoice by Email
  </a>
  <a href="tel:<?php echo SITE_PHONE_RAW; ?>"
    class="btn btn-outline" style="flex:4;justify-content:center;padding-left:.5rem;padding-right:.5rem;">
    <i class="fas fa-phone"></i> Call Now
  </a>
</div>

<?php include 'includes/footer.php'; ?>