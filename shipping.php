<?php
require_once 'includes/config.php';
$page_title = 'Shipping Policy';
$page_desc  = 'Seastar Technology Shipping Policy — license keys sent by email within 1 business day, physical hardware ships in 1-2 business days from our US warehouse.';
include 'includes/header.php';
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJT36XWT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<section class="page-hero page-hero--sm">
  <div class="container"><h1>Shipping Policy</h1><p>Last Updated: April 1, 2026</p></div>
</section>
<section class="section policy-section">
<div class="container policy-content">

<h2>1. Digital Products (Software, License Keys)</h2>
<p>License keys — including antivirus licenses, renewal keys, and software activation keys — are sent by email within <strong>1 business day</strong> of payment confirmation. Most orders are fulfilled within 1–4 hours during business hours.</p>
<ul>
  <li>Check your spam/junk folder if you do not receive the email within 24 hours.</li>
  <li>Contact us at <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a> if you experience any delivery issues.</li>
</ul>

<h2>2. Physical Products (Hardware, USB Drives, Accessories)</h2>
<ul>
  <li><strong>Processing time:</strong> Orders are processed and shipped within 1–2 business days (Monday–Friday, excluding US federal holidays).</li>
  <li><strong>Carriers:</strong> We ship via USPS, UPS, or FedEx depending on size and destination.</li>
  <li><strong>Standard shipping:</strong> 3–7 business days within the contiguous United States.</li>
  <li><strong>Expedited shipping:</strong> Available at checkout for 1–3 business day delivery.</li>
  <li><strong>Tracking:</strong> A tracking number is emailed once your order ships.</li>
</ul>

<h2>3. Shipping Destinations</h2>
<p>We currently ship physical products to addresses within the <strong>contiguous United States</strong>. We do not ship internationally or to PO Boxes for hardware items.</p>

<h2>4. Shipping Costs</h2>
<ul>
  <li>Calculated at checkout based on item weight and destination.</li>
  <li>Free standard shipping may be offered on qualifying orders — see current promotions at checkout.</li>
</ul>

<h2>5. Lost or Damaged Shipments</h2>
<p>If your shipment is lost in transit or arrives damaged, contact us within 7 days of the expected delivery date. We will file a carrier claim and arrange a replacement or refund.</p>

<h2>Contact Us</h2>
<p><?php echo SITE_NAME; ?> — <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a> | <a href="tel:<?php echo SITE_PHONE_RAW; ?>"><?php echo SITE_PHONE; ?></a></p>

</div>
</section>
<?php include 'includes/footer.php'; ?>
