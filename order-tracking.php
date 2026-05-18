<?php
require_once 'includes/config.php';
$page_title = 'Order Tracking';
$page_desc  = 'Track your Seastar Technology order. All physical shipments include a tracking number sent to your email. Contact us with your order number for a status update.';
include 'includes/header.php';
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJT36XWT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<section class="page-hero page-hero--sm">
  <div class="container">
    <div class="breadcrumb-bar">
      <a href="index.php">Home</a>
      <i class="fas fa-chevron-right"></i>
      <span>Order Tracking</span>
    </div>
    <span class="section-badge"><i class="fas fa-box"></i> Orders</span>
    <h1>Order Tracking</h1>
    <p>Need an update on your order? We'll get you the information you need.</p>
  </div>
</section>

<section class="section policy-section">
  <div class="container policy-content">

    <h2>How to Track Your Order</h2>
    <p>All physical orders shipped from our US warehouse include a tracking number sent to your email address at the time of dispatch. To track your order:</p>
    <ol>
      <li>Check your inbox (and spam folder) for a shipping confirmation email from <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>.</li>
      <li>Click the tracking link in the email, or enter your tracking number directly on the carrier's website.</li>
      <li>If you did not receive a tracking email, contact us and we will locate your shipment.</li>
    </ol>

    <h2>Software License Key Orders</h2>
    <p>License keys are sent by email within 1 business day of order confirmation. Check your inbox and spam folder. If you have not received your license key after 1 business day, contact us immediately at <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>.</p>

    <h2>Contact Us for Order Status</h2>
    <p>If you have questions about your order, please reach out with your order number and we will respond within 1 business day:</p>
    <ul>
      <li><strong>Email:</strong> <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></li>
      <li><strong>Phone:</strong> <a href="tel:<?php echo SITE_PHONE_RAW; ?>"><?php echo SITE_PHONE; ?></a></li>
      <li><strong>Hours:</strong> <?php echo SITE_HOURS_WEEKDAY; ?></li>
    </ul>

    <h2>Shipping Timeframes</h2>
    <ul>
      <li><strong>Software license keys:</strong> Sent by email within 1 business day of order confirmation.</li>
      <li><strong>Physical hardware:</strong> Ships within 1–2 business days via tracked carrier. Estimated delivery 3–7 business days depending on location.</li>
    </ul>

    <p>For full shipping details, see our <a href="shipping.php">Shipping Policy</a>. To request a return or refund, visit our <a href="refund.php">Refund Policy</a> page.</p>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
