<?php
// ─────────────────────────────────────────────
//  seastartechnology — Site Configuration
//  Update these values before going live
// ─────────────────────────────────────────────

define('SITE_NAME',         'Seastar Technology');
define('SITE_LEGAL_NAME',   'SEASTAR TECHNOLOGIES LLC');
define('SITE_TAGLINE', 'Authorized Reseller of Trusted Computer & Security Products');
define('SITE_URL',     'https://www.seastartechnology.com');
define('SITE_EMAIL',          'sales@seastartechnology.com');
define('SITE_SUPPORT_EMAIL',  'support@seastartechnology.com');
define('SITE_PHONE',        '(855) 460-3303');
define('SITE_PHONE_RAW',    '+18554603303');
define('SITE_OPTOUT_PHONE',     SITE_PHONE);
define('SITE_OPTOUT_PHONE_RAW', SITE_PHONE_RAW);

define('SITE_ADDRESS_LINE1', '10639 Mistflower Lane');
define('SITE_ADDRESS_LINE2', 'Tampa, FL 33647');
define('SITE_ADDRESS_FULL',  '10639 Mistflower Lane, Tampa, FL 33647, United States');

define('SITE_HOURS_WEEKDAY', 'Mon – Fri: 9:00 AM – 6:00 PM EST');
define('SITE_HOURS_WEEKEND', 'Sat – Sun: 9:00 AM – 6:00 PM EST');

define('ADMIN_PASSWORD_HASH', password_hash('Admin@MCC2024', PASSWORD_DEFAULT));

define('DATA_PATH', __DIR__ . '/../data/products.json');
define('ROOT_PATH', __DIR__ . '/..');

// Social (optional — leave blank to hide)
define('SOCIAL_FACEBOOK', '');
define('SOCIAL_TWITTER',  '');
define('SOCIAL_LINKEDIN', '');
