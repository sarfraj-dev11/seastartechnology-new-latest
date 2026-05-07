<?php
// db.php — Database connection stub
// seastartechnology currently uses flat-file (products.json).
// Uncomment and configure below when ready to migrate to MySQL.

/*
define('DB_HOST', 'localhost');
define('DB_NAME', 'seastartechnology');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('DB Connection failed: ' . $e->getMessage());
    die('Service temporarily unavailable. Please try again later.');
}
*/

// ─── Flat-file product helpers ────────────────────────────────────────────────

function get_all_products() {
    $json = file_get_contents(DATA_PATH);
    return json_decode($json, true) ?? [];
}

function get_product_by_slug(string $slug) {
    foreach (get_all_products() as $p) {
        if ($p['slug'] === $slug) return $p;
    }
    return null;
}

function get_products_by_category(string $cat) {
    return array_values(array_filter(get_all_products(), fn($p) => $p['category'] === $cat));
}

function get_featured_products(int $limit = 6) {
    $all = get_all_products();
    $featured = array_filter($all, fn($p) => !empty($p['badge']));
    if (count($featured) < $limit) {
        $featured = array_merge($featured, array_slice($all, 0, $limit));
    }
    return array_slice(array_values($featured), 0, $limit);
}

function get_related_products(array $slugs) {
    $all = get_all_products();
    return array_values(array_filter($all, fn($p) => in_array($p['slug'], $slugs)));
}

function get_categories() {
    $all = get_all_products();
    $cats = array_unique(array_column($all, 'category'));
    sort($cats);
    return $cats;
}
