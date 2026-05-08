<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/form-handler.php';

$page_title = 'Contact Us';
$page_desc  = 'Contact Seastar Technology — authorized reseller of consumer technology. Call, email, or fill in our form.';
$products   = get_all_products();
$preset_product = isset($_GET['product']) ? htmlspecialchars(urldecode($_GET['product'])) : '';
include 'includes/header.php';
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJT36XWT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<section class="page-hero">
  <div class="container">
    <div class="section-badge">Get in Touch</div>
    <h1>Contact Us</h1>
    <p>Have a question about a product or your order? We're here to serve you. Reach us by phone, email, or the form below.</p>
  </div>
</section>

<section class="section" id="contact-main">
  <div class="container contact-grid">

    <!-- Form -->
    <div class="contact-form-col">
      <div class="contact-card">
        <h2>Send Us a Message</h2>
        <p class="form-subtext">We typically respond within 1 business day.</p>
        <form id="contact-form" method="POST" action="send_mail.php" novalidate>
    <input type="hidden" name="form_token" value="1">
    <!-- Honeypot -->
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

    <div class="form-row">
        <div class="form-group">
            <label for="name">Full Name <span class="req">*</span></label>
            <input type="text" id="name" name="name" placeholder="John Smith" pattern="[A-Za-z\s]+" required
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email Address <span class="req">*</span></label>
            <input type="email" id="email" name="email" placeholder="john@example.com" required
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="phone">Phone Number <span style="font-size:.75rem;color:var(--text-3);font-weight:400;">(optional)</span></label>
            <input type="tel" id="phone" name="phone" maxlength="11" placeholder="+1 (555) 000-0000"
                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="product_interest">Product Interest</label>
            <select id="product_interest" name="product_interest">
                <option value="">— Select a product —</option>
                <?php foreach($products as $prod): ?>
                    <option value="<?php echo htmlspecialchars($prod['title']); ?>"
                        <?php echo ($preset_product===$prod['title']||($_POST['product_interest']??'')===$prod['title'])?'selected':''; ?>>
                        <?php echo htmlspecialchars($prod['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="message">Message <span class="req">*</span></label>
        <textarea id="message" name="message" rows="5" placeholder="Tell us what you need..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-full" id="submit-btn">
        <i class="fas fa-paper-plane"></i> Send Message
    </button>
</form>
      </div>
    </div>

    <!-- Info -->
    <div class="contact-info-col">
      <div class="contact-info-card">
        <h3><i class="fas fa-phone"></i> Phone</h3>
        <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="contact-link-lg"><?php echo SITE_PHONE; ?></a>
        <p>Call for sales inquiries and order questions.</p>
      </div>
      <div class="contact-info-card">
        <h3><i class="fas fa-envelope"></i> Email</h3>
        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="contact-link-lg"><?php echo SITE_EMAIL; ?></a>
        <p>We respond to emails within 1 business day.</p>
      </div>
      <div class="contact-info-card">
        <h3><i class="fas fa-location-dot"></i> Address</h3>
        <p><?php echo SITE_ADDRESS_LINE1; ?><br><?php echo SITE_ADDRESS_LINE2; ?></p>
      </div>
      <div class="contact-info-card">
        <h3><i class="fas fa-clock"></i> Business Hours</h3>
        <p><?php echo SITE_HOURS_WEEKDAY; ?><br>
        <!-- <?php echo SITE_HOURS_WEEKEND; ?> -->
      </p>
      </div>

      <div class="contact-info-card help-anchor" id="questions">
        <h3><i class="fas fa-circle-question"></i> Order & Shipping Questions</h3>
        <p>Questions about an existing order or need to track a shipment? Call us or send a message — our team is here to serve you with sales and order inquiries.</p>
        <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-primary"><?php echo SITE_PHONE; ?></a>
      </div>
    </div>

  </div>

  <div class="map-container">
   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3517.462973417015!2d-82.30077159999999!3d28.162832200000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2b588a54f8d9f%3A0xe19836cd0f26c316!2sSEASTAR%20TECHNOLOGIES%20LLC!5e0!3m2!1sen!2sin!4v1778261317304!5m2!1sen!2sin%22"  width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
 
</section>



<?php include 'includes/footer.php'; ?>
