<?php
require_once __DIR__ . '/config.php';

// Development mode: allow crawling but prevent caching/archiving
header('X-Robots-Tag: noarchive, nosnippet');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

$current_page = basename($_SERVER['PHP_SELF'], '.php');

function nav_class($page) {
    global $current_page;
    return $current_page === $page ? 'active' : '';
}

function products_url($slug = '') {
    return $slug ? 'product-details.php?slug=' . $slug : 'products.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KJT36XWT');</script>
<!-- End Google Tag Manager -->

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME . ' — ' . SITE_TAGLINE; ?></title>
  <meta name="description" content="<?php echo isset($page_desc) ? $page_desc : 'Seastar Technology is an authorized US reseller of trusted computer security software, hardware, and accessories. Genuine products delivered to your door.'; ?>">

  <meta name="googlebot" content="noarchive, nosnippet">
  <link rel="canonical" href="<?php echo SITE_URL . '/' . basename($_SERVER['PHP_SELF']); ?>">
  <link rel="icon" href="https://seastartechnology.com/assets/images/icons/seastar-technology-favicon.ico" type="image/x-icon">
  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
  <meta property="og:title" content="<?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME; ?>">
  <meta property="og:description" content="<?php echo isset($page_desc) ? $page_desc : 'Authorized reseller of McAfee, Bitdefender, Malwarebytes,  TP-Link, and more.'; ?>">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/theme.css">
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/base.css">
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/layout.css">
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/home.css">
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/products.css">
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/pages.css">
  <link rel="stylesheet" href="<?php echo isset($depth) ? $depth : ''; ?>assets/css/responsive.css">
  <?php if(isset($extra_head)) echo $extra_head; ?>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
  <div class="container top-bar-inner">
    <span class="top-bar-text"><i class="fas fa-shield-halved"></i> Authorized Reseller — Genuine Products Guaranteed</span>
    <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="top-bar-phone">
      <i class="fas fa-shopping-cart"></i> Sales: <?php echo SITE_PHONE; ?> &middot; Mon–Fri 9–6 EST
    </a>
    <a href="mailto:<?php echo SITE_SUPPORT_EMAIL; ?>" class="top-bar-email">
  <i class="fas fa-envelope"></i> 
  <?php echo SITE_SUPPORT_EMAIL; ?>
</a>
  </div>
</div>

<!-- Header -->
<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="<?php echo isset($depth) ? $depth : ''; ?>index.php" class="logo">
      <div class="logo-icon">
        <img src="https://seastartechnology.com/assets/images/icons/seastar%20technology-png%201.png">
        <!-- <i class="fas fa-laptop-medical"></i> -->
    </div>
      <!-- <div class="logo-text">
        <span class="logo-name">seastartechnology</span>
        <span class="logo-tag">Authorized Reseller</span>
      </div> -->
    </a>

    <nav class="main-nav" id="main-nav">
      <ul>
        <li><a href="<?php echo isset($depth) ? $depth : ''; ?>index.php" class="<?php echo nav_class('index'); ?>">Home</a></li>
        <li><a href="<?php echo isset($depth) ? $depth : ''; ?>products.php" class="<?php echo nav_class('products'); ?>">Products</a></li>
        <li><a href="<?php echo isset($depth) ? $depth : ''; ?>about.php" class="<?php echo nav_class('about'); ?>">About Us</a></li>
        <li><a href="<?php echo isset($depth) ? $depth : ''; ?>contact.php" class="<?php echo nav_class('contact'); ?>">Contact</a></li>
        <li class="none"><a href="<?php echo isset($depth) ? $depth : ''; ?>terms.php" class="<?php echo nav_class('terms'); ?>">Terms &amp; Conditions</a></li>
        <li class="none"><a href="<?php echo isset($depth) ? $depth : ''; ?>privacy.php" class="<?php echo nav_class('privacy'); ?>">Privacy Policy</a></li>
        <li class="none"><a href="<?php echo isset($depth) ? $depth : ''; ?>refund.php" class="<?php echo nav_class('refund'); ?>">Refund Policy</a></li>
        <li class="none"><a href="<?php echo isset($depth) ? $depth : ''; ?>shipping.php" class="<?php echo nav_class('shipping'); ?>">Shipping Policy</a></li>
        <li><a href="<?php echo isset($depth) ? $depth : ''; ?>contact.php#questions" class="nav-help"><i class="fas fa-circle-question"></i> Any Questions</a></li>
     
      </ul>
    </nav>

    <div class="header-cta">
      <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-phone"></i> Sales Line
      </a>
      <button class="hamburger" id="hamburger" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<div class="nav-overlay" id="nav-overlay"></div>

<main>
