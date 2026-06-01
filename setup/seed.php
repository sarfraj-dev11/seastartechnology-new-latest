<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Seastar Technology — Database Seeder
 *  
 *  This script reads data/products.json and inserts all products
 *  into the MySQL database. Run ONCE after creating the schema.
 *  
 *  HOW TO RUN:
 *  1. Upload this file to your Hostinger server under /setup/
 *  2. Visit: https://yourdomain.com/setup/seed.php
 *  3. After successful seeding, DELETE this file from server
 *  
 *  ⚠️  DELETE THIS FILE AFTER USE — it should not remain on
 *      a production server.
 * ═══════════════════════════════════════════════════════════════
 */

// ─── Security Check ─────────────────────────────────────────
// Simple protection — remove or change this key before running
$SEED_KEY = 'seastar2026seed';
if (!isset($_GET['key']) || $_GET['key'] !== $SEED_KEY) {
    die('❌ Access denied. Use: seed.php?key=' . $SEED_KEY);
}

// ─── Load Environment ────────────────────────────────────────
require_once __DIR__ . '/../includes/env.php';
load_env(__DIR__ . '/../.env');

$db_host    = $_ENV['DB_HOST']    ?? 'localhost';
$db_name    = $_ENV['DB_NAME']    ?? '';
$db_user    = $_ENV['DB_USER']    ?? 'root';
$db_pass    = $_ENV['DB_PASS']    ?? '';
$db_charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

if (empty($db_name)) {
    die('❌ DB_NAME is not set in .env file. Please configure your .env first.');
}

// ─── Connect to Database ─────────────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    echo "✅ Connected to database: {$db_name}<br><br>";
} catch (PDOException $e) {
    die('❌ Database connection failed: ' . $e->getMessage());
}

// ─── Load JSON Data ──────────────────────────────────────────
$jsonPath = __DIR__ . '/../data/products.json';
if (!file_exists($jsonPath)) {
    die('❌ products.json not found at: ' . $jsonPath);
}

$jsonContent = file_get_contents($jsonPath);
$products = json_decode($jsonContent, true);

if (!$products || !is_array($products)) {
    die('❌ Failed to parse products.json. Check JSON syntax.');
}

echo "📦 Found " . count($products) . " products to import.<br><br>";

// ─── Check if products table already has data ────────────────
$existingCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
if ($existingCount > 0) {
    echo "⚠️  Products table already has {$existingCount} records.<br>";
    echo "To re-seed, first run: <code>TRUNCATE TABLE products;</code> in phpMyAdmin (this will cascade-delete child table data too).<br>";
    echo "Or add <code>&force=1</code> to the URL to clear and re-seed.<br><br>";
    
    if (isset($_GET['force']) && $_GET['force'] === '1') {
        // Disable FK checks temporarily to truncate
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $pdo->exec("TRUNCATE TABLE product_description_blocks_items");
        $pdo->exec("TRUNCATE TABLE product_description_blocks");
        $pdo->exec("TRUNCATE TABLE product_how_to_use");
        $pdo->exec("TRUNCATE TABLE product_related");
        $pdo->exec("TRUNCATE TABLE product_gallery_images");
        $pdo->exec("TRUNCATE TABLE product_specs");
        $pdo->exec("TRUNCATE TABLE product_whats_included");
        $pdo->exec("TRUNCATE TABLE product_problem_solved");
        $pdo->exec("TRUNCATE TABLE products");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        echo "🗑️  All existing data cleared. Re-seeding...<br><br>";
    } else {
        die('Seeding aborted. Use &force=1 to override.');
    }
}

// ─── Prepare Statements ──────────────────────────────────────

$stmtProduct = $pdo->prepare("
    INSERT INTO products 
        (id, slug, title, brand, category, price, duplicat_price, badge, short_desc, long_desc, description1, description2, editorDisc, image)
    VALUES 
        (:id, :slug, :title, :brand, :category, :price, :duplicat_price, :badge, :short_desc, :long_desc, :description1, :description2, :editorDisc, :image)
");

$stmtProblem = $pdo->prepare("
    INSERT INTO product_problem_solved (product_id, item_text, sort_order) 
    VALUES (:pid, :text, :sort)
");

$stmtIncluded = $pdo->prepare("
    INSERT INTO product_whats_included (product_id, item_text, sort_order) 
    VALUES (:pid, :text, :sort)
");

$stmtSpec = $pdo->prepare("
    INSERT INTO product_specs (product_id, spec_key, spec_value, sort_order) 
    VALUES (:pid, :key, :value, :sort)
");

$stmtGallery = $pdo->prepare("
    INSERT INTO product_gallery_images (product_id, image_path, sort_order) 
    VALUES (:pid, :path, :sort)
");

$stmtRelated = $pdo->prepare("
    INSERT INTO product_related (product_id, related_slug, sort_order) 
    VALUES (:pid, :slug, :sort)
");

$stmtHowTo = $pdo->prepare("
    INSERT INTO product_how_to_use (product_id, step_text, sort_order) 
    VALUES (:pid, :text, :sort)
");

$stmtDescBlock = $pdo->prepare("
    INSERT INTO product_description_blocks (product_id, heading, sort_order) 
    VALUES (:pid, :heading, :sort)
");

$stmtDescBlockItem = $pdo->prepare("
    INSERT INTO product_description_blocks_items (block_id, item_text, sort_order) 
    VALUES (:bid, :text, :sort)
");

// ─── Insert Products ─────────────────────────────────────────
$successCount = 0;
$errorCount = 0;

foreach ($products as $p) {
    try {
        $pdo->beginTransaction();
        
        $productId = $p['id'];
        
        // ── Insert main product ──
        $stmtProduct->execute([
            ':id'             => $productId,
            ':slug'           => $p['slug'] ?? '',
            ':title'          => $p['title'] ?? '',
            ':brand'          => $p['brand'] ?? '',
            ':category'       => $p['category'] ?? 'Uncategorized',
            ':price'          => floatval($p['price'] ?? 0),
            ':duplicat_price' => isset($p['duplicat_price']) && $p['duplicat_price'] !== '' ? floatval($p['duplicat_price']) : null,
            ':badge'          => $p['badge'] ?? '',
            ':short_desc'     => $p['short_desc'] ?? '',
            ':long_desc'      => $p['long_desc'] ?? '',
            ':description1'   => $p['description1'] ?? '',
            ':description2'   => $p['description2'] ?? '',
            ':editorDisc'     => $p['editorDisc'] ?? '',
            ':image'          => $p['image'] ?? 'assets/images/icons/product-placeholder.svg',
        ]);
        
        // ── problem_solved ──
        if (!empty($p['problem_solved']) && is_array($p['problem_solved'])) {
            foreach ($p['problem_solved'] as $i => $text) {
                $stmtProblem->execute([':pid' => $productId, ':text' => $text, ':sort' => $i]);
            }
        }
        
        // ── whats_included ──
        if (!empty($p['whats_included']) && is_array($p['whats_included'])) {
            foreach ($p['whats_included'] as $i => $text) {
                $stmtIncluded->execute([':pid' => $productId, ':text' => $text, ':sort' => $i]);
            }
        }
        
        // ── specs ──
        if (!empty($p['specs']) && is_array($p['specs'])) {
            $sortIdx = 0;
            foreach ($p['specs'] as $key => $value) {
                $stmtSpec->execute([':pid' => $productId, ':key' => $key, ':value' => $value, ':sort' => $sortIdx]);
                $sortIdx++;
            }
        }
        
        // ── gallery_images ──
        if (!empty($p['gallery_images']) && is_array($p['gallery_images'])) {
            foreach ($p['gallery_images'] as $i => $path) {
                if (!empty($path)) {
                    $stmtGallery->execute([':pid' => $productId, ':path' => $path, ':sort' => $i]);
                }
            }
        }
        
        // ── related ──
        if (!empty($p['related']) && is_array($p['related'])) {
            foreach ($p['related'] as $i => $slug) {
                $stmtRelated->execute([':pid' => $productId, ':slug' => $slug, ':sort' => $i]);
            }
        }
        
        // ── how_to_use ──
        if (!empty($p['how_to_use']) && is_array($p['how_to_use'])) {
            foreach ($p['how_to_use'] as $i => $text) {
                $stmtHowTo->execute([':pid' => $productId, ':text' => $text, ':sort' => $i]);
            }
        }
        
        // ── description_blocks ──
        if (!empty($p['description_blocks']) && is_array($p['description_blocks'])) {
            foreach ($p['description_blocks'] as $bi => $block) {
                $stmtDescBlock->execute([
                    ':pid'     => $productId,
                    ':heading' => $block['heading'] ?? '',
                    ':sort'    => $bi,
                ]);
                $blockId = $pdo->lastInsertId();
                
                if (!empty($block['items']) && is_array($block['items'])) {
                    foreach ($block['items'] as $ii => $itemText) {
                        $stmtDescBlockItem->execute([
                            ':bid'  => $blockId,
                            ':text' => $itemText,
                            ':sort' => $ii,
                        ]);
                    }
                }
            }
        }
        
        $pdo->commit();
        $successCount++;
        echo "✅ [{$productId}] <strong>{$p['title']}</strong> — inserted successfully<br>";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $errorCount++;
        echo "❌ [{$productId}] <strong>{$p['title']}</strong> — ERROR: " . $e->getMessage() . "<br>";
    }
}

// ─── Summary ─────────────────────────────────────────────────
echo "<br>";
echo "═══════════════════════════════════════<br>";
echo "📊 <strong>Seeding Complete</strong><br>";
echo "✅ Success: {$successCount}<br>";
echo "❌ Errors:  {$errorCount}<br>";
echo "═══════════════════════════════════════<br><br>";

if ($errorCount === 0) {
    echo "🎉 All products imported successfully!<br>";
    echo "⚠️  <strong>IMPORTANT:</strong> Delete this seed.php file from your server now.<br>";
} else {
    echo "⚠️  Some products failed. Check errors above and fix before re-running.<br>";
}

// ─── Verification: Show counts ───────────────────────────────
echo "<br><strong>Verification — Row counts:</strong><br>";
$tables = ['products', 'product_problem_solved', 'product_whats_included', 'product_specs', 'product_gallery_images', 'product_related', 'product_how_to_use', 'product_description_blocks', 'product_description_blocks_items'];
foreach ($tables as $t) {
    $count = $pdo->query("SELECT COUNT(*) FROM `{$t}`")->fetchColumn();
    echo "  📋 {$t}: <strong>{$count}</strong> rows<br>";
}
?>
