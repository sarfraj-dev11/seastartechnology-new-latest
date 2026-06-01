-- ═══════════════════════════════════════════════════════════════
--  Seastar Technology — Product Database Schema
--  Run this SQL in Hostinger phpMyAdmin to create all tables
--  Date: 2026-06-02
-- ═══════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ─── Main Products Table ─────────────────────────────────────
DROP TABLE IF EXISTS `product_how_to_use`;
DROP TABLE IF EXISTS `product_related`;
DROP TABLE IF EXISTS `product_gallery_images`;
DROP TABLE IF EXISTS `product_specs`;
DROP TABLE IF EXISTS `product_whats_included`;
DROP TABLE IF EXISTS `product_problem_solved`;
DROP TABLE IF EXISTS `product_description_blocks_items`;
DROP TABLE IF EXISTS `product_description_blocks`;
DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(255) NOT NULL,
  `title` VARCHAR(500) NOT NULL,
  `brand` VARCHAR(255) NOT NULL DEFAULT '',
  `category` VARCHAR(255) NOT NULL DEFAULT 'Uncategorized',
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `duplicat_price` DECIMAL(10,2) DEFAULT NULL,
  `badge` VARCHAR(100) NOT NULL DEFAULT '',
  `short_desc` TEXT,
  `long_desc` TEXT,
  `description1` TEXT,
  `description2` TEXT,
  `editorDisc` LONGTEXT,
  `image` VARCHAR(500) NOT NULL DEFAULT 'assets/images/icons/product-placeholder.svg',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`),
  INDEX `idx_category` (`category`),
  INDEX `idx_brand` (`brand`),
  INDEX `idx_badge` (`badge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Problem Solved (Key Features) ──────────────────────────
CREATE TABLE `product_problem_solved` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `item_text` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  CONSTRAINT `fk_ps_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── What's Included ────────────────────────────────────────
CREATE TABLE `product_whats_included` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `item_text` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  CONSTRAINT `fk_wi_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Specifications (Key-Value) ─────────────────────────────
CREATE TABLE `product_specs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `spec_key` VARCHAR(255) NOT NULL,
  `spec_value` VARCHAR(500) NOT NULL DEFAULT '',
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  CONSTRAINT `fk_sp_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Gallery Images ─────────────────────────────────────────
CREATE TABLE `product_gallery_images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(500) NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  CONSTRAINT `fk_gi_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Related Products ───────────────────────────────────────
CREATE TABLE `product_related` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `related_slug` VARCHAR(255) NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  INDEX `idx_related_slug` (`related_slug`),
  CONSTRAINT `fk_rel_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── How To Use Steps ───────────────────────────────────────
CREATE TABLE `product_how_to_use` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `step_text` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  CONSTRAINT `fk_htu_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Description Blocks (used by Driver Booster etc.) ───────
CREATE TABLE `product_description_blocks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `heading` VARCHAR(500) NOT NULL DEFAULT '',
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_product` (`product_id`),
  CONSTRAINT `fk_db_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_description_blocks_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `block_id` INT UNSIGNED NOT NULL,
  `item_text` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_block` (`block_id`),
  CONSTRAINT `fk_dbi_block` FOREIGN KEY (`block_id`) REFERENCES `product_description_blocks`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ═══════════════════════════════════════════════════════════════
--  Schema created successfully!
--  Next step: Run seed.php to import products from JSON
-- ═══════════════════════════════════════════════════════════════
