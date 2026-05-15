</main>

<!-- Footer -->
<footer class="site-footer">

  <div class="footer-main">
    <div class="container footer-grid">

      <!-- Brand Col -->
      <div class="footer-col footer-brand-col">
        <div class="footer-logo">
          <div class="logo-icon">
             <img src="https://seastartechnology.com/assets/images/icons/seastar%20technology-png%201.png" alt="Seastar Technology Logo">
        </div>
          <!-- <span class="logo-name">seastartechnology</span> -->
        </div>
        <p class="footer-tagline">Authorized US reseller of genuine computer security software, hardware, and accessories.</p>
         <div class="social-icons">
    <?php if(SOCIAL_PINITEREST): ?>
        <a href="<?php echo SOCIAL_PINITEREST; ?>" target="_blank" aria-label="Pinterest">
            <i class="fab fa-pinterest-p"></i>
        </a>
    <?php endif; ?>

    <?php if(SOCIAL_INSTAGRAM): ?>
        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
    <?php endif; ?>
</div>
        <div class="footer-social">
          <?php if(SOCIAL_FACEBOOK): ?><a href="<?php echo SOCIAL_FACEBOOK; ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
          <?php if(SOCIAL_TWITTER): ?><a href="<?php echo SOCIAL_TWITTER; ?>" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
          <?php if(SOCIAL_LINKEDIN): ?><a href="<?php echo SOCIAL_LINKEDIN; ?>" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
        </div>
      </div>

      <!-- Products Col -->
      <div class="footer-col">
        <h4 class="footer-col-title">Products</h4>
        <ul class="footer-links">
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=Security+Software">Security Software</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=PC+Optimization">PC Optimization</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=OS+%26+Recovery">OS &amp; Recovery</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=Storage">Storage</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=Networking">Networking</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=Printers">Printers</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php?cat=Coverage+Plans">Coverage Plans</a></li>
        </ul>
      </div>

      <!-- Company Col -->
      <div class="footer-col">
        <h4 class="footer-col-title">Company</h4>
        <ul class="footer-links">
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>about.php">About Us</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>contact.php">Contact</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>terms.php">Terms &amp; Conditions</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>privacy.php">Privacy Policy</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>cookiepolicy.php">Cookie Policy</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>refund.php">Refund Policy</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>shipping.php">Shipping Policy</a></li>
          <li><a href="<?php echo isset($depth) ? $depth : ''; ?>order-tracking.php">Order Tracking</a></li>
        </ul>
      </div>

      <!-- Contact Col -->
      <div class="footer-col">
        <h4 class="footer-col-title">Contact Us</h4>
        <ul class="footer-contact-list">
          <li><i class="fas fa-location-dot"></i>
            <span><?php echo SITE_ADDRESS_LINE1; ?><br><?php echo SITE_ADDRESS_LINE2; ?></span>
          </li>
          <li><i class="fas fa-phone"></i>
            <span>Sales &amp; Orders: <a href="tel:<?php echo SITE_PHONE_RAW; ?>"><?php echo SITE_PHONE; ?></a><br>
            <small>Sales enquiries: <a href="mailto:<?php echo SITE_SALES_EMAIL; ?>"><?php echo SITE_SALES_EMAIL; ?></a></small></span>
          </li>
          <li><i class="fas fa-envelope"></i>
            <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
          </li>
          <li><i class="fas fa-clock"></i>
            <span><?php echo SITE_HOURS_WEEKDAY; ?><br>
            <!-- <?php echo SITE_HOURS_WEEKEND; ?> -->
          </span>
          </li>
        </ul>
      </div>

    </div>
  </div>

  <!-- Payment & Trust Badges -->
  <div class="footer-trust-row">
    <div class="container footer-trust-inner">
      <div class="footer-payment-logos">
        <span>Accepted:</span>
        <i class="fab fa-cc-visa" title="Visa"></i>
        <i class="fab fa-cc-mastercard" title="Mastercard"></i>
        <i class="fab fa-cc-amex" title="American Express"></i>
        <i class="fab fa-cc-paypal" title="PayPal"></i>
      </div>
      <div class="footer-trust-badge">
        <i class="fas fa-lock"></i> SSL Secure Checkout
      </div>
      <div class="footer-trust-badge">
        <i class="fas fa-certificate"></i> Authorized Reseller
      </div>
    </div>
  </div>

  <!-- Disclaimer -->
  <div class="footer-disclaimer">
    <div class="container">
      <p><?php echo SITE_LEGAL_NAME; ?> (D-U-N-S #: 13-996-7974 &middot; <?php echo SITE_ADDRESS_FULL; ?>) is an independent authorized reseller of select consumer technology products including antivirus software, storage devices, networking hardware, printers, and peripherals. We are not affiliated with, endorsed by, or acting on behalf of any software manufacturer, technology brand, or OEM unless explicitly stated. Brand names, logos, and trademarks mentioned belong to their respective owners and are used solely for product identification. All software products are sold as-is per their respective manufacturer's terms. License activation guidance is provided as part of eligible software purchases to confirm your license is registered correctly. Prices and availability are subject to change. Software keys are refundable within 30 days only if unactivated or proven non-functional.</p>
      <p>Opt-Out: To stop receiving communications, call <a href="tel:<?php echo SITE_PHONE_RAW; ?>"><?php echo SITE_PHONE; ?></a> or email <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>.</p>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_LEGAL_NAME; ?> dba <?php echo SITE_NAME; ?>. D-U-N-S #: 13-996-7974. All rights reserved. Authorized reseller — not affiliated with or endorsed by manufacturers.</p>
      <div class="footer-bottom-links">
        <a href="<?php echo isset($depth) ? $depth : ''; ?>privacy.php">Privacy</a>
        <a href="<?php echo isset($depth) ? $depth : ''; ?>cookiepolicy.php">Cookies</a>
        <a href="<?php echo isset($depth) ? $depth : ''; ?>terms.php">Terms</a>
        <a href="<?php echo isset($depth) ? $depth : ''; ?>refund.php">Refunds</a>
        <a href="<?php echo isset($depth) ? $depth : ''; ?>shipping.php">Shipping</a>
      </div>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="<?php echo isset($depth) ? $depth : ''; ?>assets/js/main.js"></script>
<?php if(isset($extra_scripts)) echo $extra_scripts; ?>
<script src="assets/js/search.js"></script>
</body>
</html>
