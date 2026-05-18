<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$page_title = 'Home';
$page_desc  = 'Seastar Technology is an authorized US reseller of McAfee, Bitdefender, Malwarebytes, TP-Link and more. Genuine software, hardware &amp; accessories delivered to your door.';
$featured   = get_featured_products(6);

include 'includes/header.php';
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJT36XWT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- ═══════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════ -->
<section class="hero" id="hero">
  <!-- Hero Slider Background -->
  <div class="hero-slider" id="heroSlider">


    <div class="hero-slide active">
      <picture>
        <!-- MOBILE IMAGE: 767px wide or less -->
        <source media="(max-width: 767px)" srcset="https://seastartechnology.com/assets/images/icons/trusted-computer3.webp">
        <!-- DESKTOP IMAGE: Default -->
        <img src="https://seastartechnology.com/assets/images/icons/trusted-computer3.webp" alt="Cyber Security Slide 2" onerror="this.src='https://seastartechnology.com/assets/images/icons/trusted-computer3.webp'">
      </picture>
    </div>
    <div class="hero-slide ">
      <picture>
        <!-- MOBILE IMAGE: 767px wide or less -->
        <source media="(max-width: 767px)" srcset="https://seastartechnology.com/assets/images/icons/trusted-computer2.jpg">
        <!-- DESKTOP IMAGE: Default -->
        <img src="https://seastartechnology.com/assets/images/icons/trusted-computer2.jpg" alt="Cyber Security Slide 1" onerror="this.src='https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1920&q=80'">
      </picture>
    </div>

    <div class="hero-slide">
      <picture>
        <!-- MOBILE IMAGE: 767px wide or less -->
        <source media="(max-width: 767px)" srcset="https://seastartechnology.com/assets/images/icons/trusted-computer4.webp">
        <!-- DESKTOP IMAGE: Default -->
        <img src="https://seastartechnology.com/assets/images/icons/trusted-computer4.webp" alt="Cyber Security Slide 3" onerror="this.src='https://images.unsplash.com/photo-1614064641913-6b7140414c71?auto=format&fit=crop&w=1920&q=80'">
      </picture>
    </div>
    <div class="hero-slider-overlay"></div>
    <button class="slider-btn prev" id="sliderPrev" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next" id="sliderNext" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>
    <!-- <div class="slider-dots" id="sliderDots"></div> -->
  </div>

  <div class="container hero-inner">
    <div class="hero-content">
      <div class="hero-badge">
        <i class="fas fa-certificate"></i> Authorized US Reseller
      </div>
      <h1 class="hero-title">
        Trusted Computer &amp;<br><span class="gradient-text">Security Products</span><br>Delivered to Your Door
      </h1>
      <p class="hero-subtitle">
        Genuine software, hardware &amp; accessories from the brands you trust —
        McAfee, Bitdefender, Malwarebytes, TP-Link &amp; more. Authorized US reseller
        with fast shipping and 30-day returns.
      </p>
      <div class="hero-actions">
        <a href="products.php" class="btn btn-primary btn-lg">
          <i class="fas fa-th-large"></i> Shop All Products
        </a>
      </div>



      <div class="hero-trust-badges">
        <span><i class="fas fa-lock"></i> Secure Checkout</span>
        <span><i class="fas fa-rotate-left"></i> 30-Day Returns</span>
        <span><i class="fas fa-truck-fast"></i> Fast Shipping</span>
        <span><i class="fas fa-headset"></i> Activation Guidance Included</span>
      </div>
      <div class="hero-payment-logos">
        <span class="pay-label">Accepted:</span>
        <i class="fab fa-cc-visa" title="Visa"></i>
        <i class="fab fa-cc-mastercard" title="Mastercard"></i>
        <i class="fab fa-cc-amex" title="American Express"></i>
        <i class="fab fa-cc-paypal" title="PayPal"></i>
        <span class="ssl-note"><i class="fas fa-lock"></i> SSL Encrypted</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     FEATURES STRIP
═══════════════════════════════════════════════ -->
<!-- <section class="features-strip">
  <div class="container features-strip-inner">
    <div class="fs-item">
      <div class="fs-icon"><i class="fas fa-shield-halved"></i></div>
      <div class="fs-text">Antivirus Protection</div>
    </div>
    <div class="fs-item">
      <div class="fs-icon"><i class="fas fa-hard-drive"></i></div>
      <div class="fs-text">Storage Upgrades</div>
    </div>
    <div class="fs-item">
      <div class="fs-icon"><i class="fas fa-wifi"></i></div>
      <div class="fs-text">Networking</div>
    </div>
    <div class="fs-item">
      <div class="fs-icon"><i class="fas fa-desktop"></i></div>
      <div class="fs-text">Computers &amp; Printers</div>
    </div>
  </div>
</section> -->















<!-- ── Who We Are Strip ── -->
<div class="who-we-are-strip">
  <div class="container who-we-are-inner">
    <div class="wwa-item"><i class="fas fa-building"></i> <strong>US‑based LLC</strong> &nbsp;D‑U‑N‑S #13‑996‑7974</div>
    <span class="wwa-divider" aria-hidden="true">·</span>
    <div class="wwa-item"><i class="fas fa-location-dot"></i> Tampa, FL</div>
    <span class="wwa-divider" aria-hidden="true">·</span>
    <div class="wwa-item"><i class="fas fa-box-open"></i> Security software, storage, networking &amp; accessories</div>
    <!-- <span class="wwa-divider" aria-hidden="true">·</span>
    <div class="wwa-item"><i class="fa-brands fa-servicestack"></i> Antivirus Protection
      , Storage Upgrades
      , Networking
      &amp; Computers & Printers
    </div> -->
  </div>
</div>

<!-- ═══════════════════════════════════════════════
     FEATURED PRODUCTS
═══════════════════════════════════════════════ -->
<section class="section featured-products" id="featured-products">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Top Sellers</div>
      <h2 class="section-title">Featured Products</h2>
      <p class="section-subtitle">Handpicked best-sellers across every category — from antivirus to storage to printers.</p>
    </div>

    <div class="products-grid">
      <?php foreach ($featured as $p): ?>
        <div class="product-card" data-category="<?php echo htmlspecialchars($p['category']); ?>">
          <?php if (!empty($p['badge'])): ?>
            <div class="product-badge"><?php echo htmlspecialchars($p['badge']); ?></div>
          <?php endif; ?>
          <div class="product-img-wrap">
            <img src="<?php echo htmlspecialchars($p['image']); ?>"
              alt="<?php echo htmlspecialchars($p['title']); ?>"
              onerror="this.src='assets/images/icons/product-placeholder.svg'"
              loading="lazy">
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

    <div class="section-cta">
      <a href="products.php" class="btn btn-outline btn-lg">View All Products <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     USP BLOCK — WHY BUY FROM US
═══════════════════════════════════════════════ -->
<section class="section usp-section" id="why-us">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Our Promise</div>
      <h2 class="section-title">More Than Just a Product</h2>
      <p class="section-subtitle">
        When you buy from Seastar Technology, you get genuine products sourced through authorized channels —
        with fast delivery, transparent pricing, and 30-day returns on eligible items.
      </p>
    </div>

    <div class="usp-grid">
      <div class="usp-card">
        <div class="usp-icon"><i class="fas fa-certificate"></i></div>
        <h3>Genuine Products</h3>
        <p>Every item we sell is sourced from authorized distribution channels. No gray market, no counterfeits — guaranteed.</p>
      </div>
      <div class="usp-card usp-card--highlight">
        <div class="usp-icon"><i class="fas fa-headset"></i></div>
        <h3>Product Activation Guidance</h3>
        <p>One-time activation guidance is included with eligible software purchases to confirm your license is registered correctly.</p>
        <div class="usp-highlight-badge">Included</div>
      </div>
      <div class="usp-card">
        <div class="usp-icon"><i class="fas fa-rotate-left"></i></div>
        <h3>30-Day Return Policy</h3>
        <p>Not satisfied? Return any unopened physical product or report a non-functioning software key within 30 days for a full resolution.</p>
      </div>
      <div class="usp-card">
        <div class="usp-icon"><i class="fas fa-truck-fast"></i></div>
        <h3>Fast Delivery</h3>
        <p>License keys sent by email within 1 business day. Physical hardware ships within 1–2 business days via tracked carrier.</p>
      </div>
      <div class="usp-card">
        <div class="usp-icon"><i class="fas fa-lock"></i></div>
        <h3>Secure Transactions</h3>
        <p>SSL-encrypted checkout. Your payment and personal information are protected at every step.</p>
        <div class="usp-payment-logos">
          <i class="fab fa-cc-visa" title="Visa"></i>
          <i class="fab fa-cc-mastercard" title="Mastercard"></i>
          <i class="fab fa-cc-amex" title="American Express"></i>
          <i class="fab fa-cc-paypal" title="PayPal"></i>
        </div>
        <p class="usp-ssl-note"><i class="fas fa-shield-halved"></i> Payments processed with SSL encryption</p>
      </div>
      <div class="usp-card">
        <div class="usp-icon"><i class="fas fa-star"></i></div>
        <h3>US-Based &amp; Registered</h3>
        <p>Serving customers across the United States with genuine products and excellent service.</p>
        <p class="usp-duns"><i class="fas fa-building" style="font-size:.68rem;color:var(--accent);"></i> US‑based LLC &nbsp;·&nbsp; D‑U‑N‑S #13‑996‑7974 &nbsp;·&nbsp; Tampa, FL</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     TRUST STRIP — BRAND LOGOS
═══════════════════════════════════════════════ -->
<section class="trust-strip" id="brands">
  <div class="container">
    <p class="trust-strip-label">Authorized Reseller Of Leading Brands</p>
    <div class="trust-logos-ticker">
      <div class="trust-logos-track">
        <?php
        $brands = ['McAfee', 'Bitdefender', 'Malwarebytes', 'Western Digital', 'Seagate', 'TP-Link', 'Logitech', 'Microsoft', 'iolo', 'Crucial', 'Canon', 'Epson'];
        foreach ($brands as $b): ?>
          <div class="trust-logo-item"><span><?php echo $b; ?></span></div>
        <?php endforeach; ?>
        <?php foreach ($brands as $b): ?>
          <div class="trust-logo-item"><span><?php echo $b; ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     CALL TO ACTION BANNER
═══════════════════════════════════════════════ -->
<section class="cta-banner">
  <div class="container cta-banner-inner">
    <div class="cta-banner-content">
      <h2>Not Sure Which Product You Need?</h2>
      <p>Call our sales team — product questions, bundles, bulk orders.</p>
      <p class="cta-note" style="font-size:0.8rem;opacity:0.8;margin-top:0.5rem;">Sales: <a href="mailto:<?php echo SITE_SALES_EMAIL; ?>" style="color:inherit;"><?php echo SITE_SALES_EMAIL; ?></a></p>
    </div>
    <div class="cta-banner-actions">
      <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-white btn-lg">
        <i class="fas fa-phone"></i> Sales: <?php echo SITE_PHONE; ?>
      </a>
      <a href="contact.php" class="btn btn-outline-white btn-lg">Send a Message</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>