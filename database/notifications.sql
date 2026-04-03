-- 1Pixel Dashboard - Notifications Schema Extension
-- Thêm bảng notifications để lưu trữ thông báo hệ thống

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('warning','info','success','error') COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `item_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  INDEX `idx_cat_item` (`category`, `item_id`),
  INDEX `idx_is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
