<?php
// ─────────────────────────────────────────────────────────────
//  Seastar Technology — Database Connection & Product Functions
//  MySQL via PDO — All functions return data in the SAME format
//  as the old JSON, so frontend pages need ZERO changes.
// ─────────────────────────────────────────────────────────────

// ─── Database Connection (Singleton) ─────────────────────────

function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $host    = $_ENV['DB_HOST']    ?? 'localhost';
    $name    = $_ENV['DB_NAME']    ?? '';
    $user    = $_ENV['DB_USER']    ?? 'root';
    $pass    = $_ENV['DB_PASS']    ?? '';
    $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

    if (empty($name)) {
        error_log('DB_NAME is not configured in .env');
        die('Database not configured. Please check .env file.');
    }

    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$name};charset={$charset}",
            $user,
            $pass,
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

    return $pdo;
}

// ─── Private: Enrich product row with child table data ───────
// Takes a row from `products` table and adds all related data.
// Returns array in the EXACT same format as the old JSON structure
// so index.php, products.php, product-details.php work unchanged.

function _enrich_product(array $row): array {
    $db = get_db();
    $id = $row['id'];

    // Format price as string to match old JSON format ("38.98")
    $row['price'] = number_format((float)$row['price'], 2, '.', '');
    if ($row['duplicat_price'] !== null) {
        $row['duplicat_price'] = number_format((float)$row['duplicat_price'], 2, '.', '');
    }

    // Remove DB-only timestamps (frontend doesn't use them)
    unset($row['created_at'], $row['updated_at']);

    // ── problem_solved (array of strings) ──
    $stmt = $db->prepare("SELECT item_text FROM product_problem_solved WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $row['problem_solved'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ── whats_included (array of strings) ──
    $stmt = $db->prepare("SELECT item_text FROM product_whats_included WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $row['whats_included'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ── specs (associative array: key => value) ──
    $stmt = $db->prepare("SELECT spec_key, spec_value FROM product_specs WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $specs = [];
    foreach ($stmt->fetchAll() as $s) {
        $specs[$s['spec_key']] = $s['spec_value'];
    }
    $row['specs'] = $specs;

    // ── gallery_images (array of path strings) ──
    $stmt = $db->prepare("SELECT image_path FROM product_gallery_images WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $row['gallery_images'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ── related product slugs (array of strings) ──
    $stmt = $db->prepare("SELECT related_slug FROM product_related WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $row['related'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ── how_to_use (optional — only McAfee has this) ──
    $stmt = $db->prepare("SELECT step_text FROM product_how_to_use WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $htu = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($htu)) {
        $row['how_to_use'] = $htu;
    }

    // ── description_blocks (optional — only Driver Booster has this) ──
    $stmt = $db->prepare("SELECT id, heading FROM product_description_blocks WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $blocks = $stmt->fetchAll();
    if (!empty($blocks)) {
        $descBlocks = [];
        foreach ($blocks as $block) {
            $stmtItems = $db->prepare("SELECT item_text FROM product_description_blocks_items WHERE block_id = ? ORDER BY sort_order ASC");
            $stmtItems->execute([$block['id']]);
            $descBlocks[] = [
                'heading' => $block['heading'],
                'items'   => $stmtItems->fetchAll(PDO::FETCH_COLUMN),
            ];
        }
        $row['description_blocks'] = $descBlocks;
    }

    return $row;
}

// ═══════════════════════════════════════════════════════════════
//  READ FUNCTIONS — Same names & return format as before
//  Frontend pages call these — no changes needed in frontend
// ═══════════════════════════════════════════════════════════════

function get_all_products(): array {
    $db = get_db();
    $rows = $db->query("SELECT * FROM products ORDER BY id ASC")->fetchAll();
    return array_map('_enrich_product', $rows);
}

function get_product_by_slug(string $slug) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM products WHERE slug = ? LIMIT 1");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ? _enrich_product($row) : null;
}

function get_products_by_category(string $cat): array {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM products WHERE category = ? ORDER BY id ASC");
    $stmt->execute([$cat]);
    $rows = $stmt->fetchAll();
    return array_map('_enrich_product', $rows);
}

function get_featured_products(int $limit = 6): array {
    $db = get_db();

    // Get products with badges first (same logic as old JSON version)
    $stmt = $db->prepare("SELECT * FROM products WHERE badge != '' ORDER BY id ASC");
    $stmt->execute();
    $featured = $stmt->fetchAll();

    // If not enough badged products, fill from beginning
    if (count($featured) < $limit) {
        $all = $db->query("SELECT * FROM products ORDER BY id ASC")->fetchAll();
        $featured = array_merge($featured, $all);
        // Remove duplicates by id
        $seen = [];
        $unique = [];
        foreach ($featured as $row) {
            if (!isset($seen[$row['id']])) {
                $seen[$row['id']] = true;
                $unique[] = $row;
            }
        }
        $featured = $unique;
    }

    $featured = array_slice($featured, 0, $limit);
    return array_map('_enrich_product', $featured);
}

function get_related_products(array $slugs): array {
    if (empty($slugs)) return [];

    $db = get_db();
    $placeholders = implode(',', array_fill(0, count($slugs), '?'));
    $stmt = $db->prepare("SELECT * FROM products WHERE slug IN ({$placeholders}) ORDER BY id ASC");
    $stmt->execute(array_values($slugs));
    $rows = $stmt->fetchAll();
    return array_map('_enrich_product', $rows);
}

function get_categories(): array {
    $db = get_db();
    return $db->query("SELECT DISTINCT category FROM products ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
}

// ═══════════════════════════════════════════════════════════════
//  WRITE FUNCTIONS — Used by Admin Panel (manage-products.php)
// ═══════════════════════════════════════════════════════════════

/**
 * Insert child table data for a product (problem_solved, whats_included, etc.)
 */
function _save_child_data(PDO $db, int $productId, array $data): void {
    // ── problem_solved ──
    if (!empty($data['problem_solved']) && is_array($data['problem_solved'])) {
        $stmt = $db->prepare("INSERT INTO product_problem_solved (product_id, item_text, sort_order) VALUES (?, ?, ?)");
        foreach ($data['problem_solved'] as $i => $text) {
            if (trim($text) !== '') {
                $stmt->execute([$productId, trim($text), $i]);
            }
        }
    }

    // ── whats_included ──
    if (!empty($data['whats_included']) && is_array($data['whats_included'])) {
        $stmt = $db->prepare("INSERT INTO product_whats_included (product_id, item_text, sort_order) VALUES (?, ?, ?)");
        foreach ($data['whats_included'] as $i => $text) {
            if (trim($text) !== '') {
                $stmt->execute([$productId, trim($text), $i]);
            }
        }
    }

    // ── specs ──
    if (!empty($data['specs']) && is_array($data['specs'])) {
        $stmt = $db->prepare("INSERT INTO product_specs (product_id, spec_key, spec_value, sort_order) VALUES (?, ?, ?, ?)");
        $i = 0;
        foreach ($data['specs'] as $key => $value) {
            $stmt->execute([$productId, $key, $value, $i]);
            $i++;
        }
    }

    // ── gallery_images ──
    if (!empty($data['gallery_images']) && is_array($data['gallery_images'])) {
        $stmt = $db->prepare("INSERT INTO product_gallery_images (product_id, image_path, sort_order) VALUES (?, ?, ?)");
        foreach ($data['gallery_images'] as $i => $path) {
            if (!empty($path)) {
                $stmt->execute([$productId, $path, $i]);
            }
        }
    }

    // ── related ──
    if (!empty($data['related']) && is_array($data['related'])) {
        $stmt = $db->prepare("INSERT INTO product_related (product_id, related_slug, sort_order) VALUES (?, ?, ?)");
        foreach ($data['related'] as $i => $rslug) {
            if (!empty($rslug)) {
                $stmt->execute([$productId, $rslug, $i]);
            }
        }
    }
}

/**
 * Delete all child table data for a product (before re-inserting on update)
 */
function _clear_child_data(PDO $db, int $productId): void {
    $tables = [
        'product_problem_solved',
        'product_whats_included',
        'product_specs',
        'product_gallery_images',
        'product_related',
        'product_how_to_use',
    ];
    foreach ($tables as $table) {
        $db->prepare("DELETE FROM `{$table}` WHERE product_id = ?")->execute([$productId]);
    }

    // Handle description_blocks (has nested child table)
    $stmt = $db->prepare("SELECT id FROM product_description_blocks WHERE product_id = ?");
    $stmt->execute([$productId]);
    $blockIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($blockIds)) {
        $ph = implode(',', array_fill(0, count($blockIds), '?'));
        $db->prepare("DELETE FROM product_description_blocks_items WHERE block_id IN ({$ph})")->execute($blockIds);
    }
    $db->prepare("DELETE FROM product_description_blocks WHERE product_id = ?")->execute([$productId]);
}

/**
 * Create a new product. Returns the new product ID, or 0 on failure.
 * Slug is auto-generated from title (with uniqueness check).
 */
function create_product(array $data): int {
    $db = get_db();
    $db->beginTransaction();

    try {
        // Generate unique slug from title
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title'] ?? 'new-product'), '-'));
        $original_slug = $slug;
        $count = 1;
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
        $checkStmt->execute([$slug]);
        while ($checkStmt->fetchColumn() > 0) {
            $slug = $original_slug . '-' . $count++;
            $checkStmt->execute([$slug]);
        }

        $stmt = $db->prepare("
            INSERT INTO products (slug, title, brand, category, price, duplicat_price, badge, short_desc, long_desc, description1, description2, editorDisc, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $slug,
            $data['title'] ?? '',
            $data['brand'] ?? '',
            $data['category'] ?? 'Uncategorized',
            floatval($data['price'] ?? 0),
            !empty($data['duplicat_price']) ? floatval($data['duplicat_price']) : null,
            $data['badge'] ?? '',
            $data['short_desc'] ?? '',
            $data['long_desc'] ?? '',
            $data['description1'] ?? '',
            $data['description2'] ?? '',
            $data['editorDisc'] ?? '',
            $data['image'] ?? 'assets/images/icons/product-placeholder.svg',
        ]);

        $productId = (int)$db->lastInsertId();
        _save_child_data($db, $productId, $data);

        $db->commit();
        return $productId;

    } catch (Exception $e) {
        $db->rollBack();
        error_log('Create product failed: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Update an existing product by slug. Returns true on success.
 * Child data is cleared and re-inserted (simpler than diffing).
 */
function update_product(string $slug, array $data): bool {
    $db = get_db();

    // Get current product ID
    $stmt = $db->prepare("SELECT id FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    if (!$row) return false;

    $productId = (int)$row['id'];
    $db->beginTransaction();

    try {
        $stmt = $db->prepare("
            UPDATE products SET
                title = ?, price = ?, badge = ?, short_desc = ?, long_desc = ?,
                description1 = ?, description2 = ?, editorDisc = ?, image = ?
            WHERE slug = ?
        ");
        $stmt->execute([
            $data['title'] ?? '',
            floatval($data['price'] ?? 0),
            $data['badge'] ?? '',
            $data['short_desc'] ?? '',
            $data['long_desc'] ?? '',
            $data['description1'] ?? '',
            $data['description2'] ?? '',
            $data['editorDisc'] ?? '',
            $data['image'] ?? 'assets/images/icons/product-placeholder.svg',
            $slug,
        ]);

        // Clear old child data and re-insert updated data
        _clear_child_data($db, $productId);
        _save_child_data($db, $productId, $data);

        $db->commit();
        return true;

    } catch (Exception $e) {
        $db->rollBack();
        error_log('Update product failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Delete a product by slug. Child rows are cascade-deleted by FK constraints.
 * Returns true if a product was deleted.
 */
function delete_product(string $slug): bool {
    $db = get_db();
    try {
        $stmt = $db->prepare("DELETE FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        error_log('Delete product failed: ' . $e->getMessage());
        return false;
    }
}
