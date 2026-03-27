-- 1Pixel Dashboard - Database Schema
-- Last Updated: 2026-03-27

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` decimal(15,2) DEFAULT 0.00,
  `date` date DEFAULT NULL,
  `status` enum('doing','testing','done') COLLATE utf8mb4_unicode_ci DEFAULT 'doing',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_pass` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for hostings
-- ----------------------------
DROP TABLE IF EXISTS `hostings`;
CREATE TABLE `hostings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) DEFAULT 0.00,
  `reg_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `usage_period` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for passwords
-- ----------------------------
DROP TABLE IF EXISTS `passwords`;
CREATE TABLE `passwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- SEED DATA (From shared-data.js)
-- ----------------------------

INSERT INTO `projects` (`name`, `link`, `customer`, `phone`, `value`, `date`, `status`, `description`, `admin_url`, `admin_user`, `admin_pass`) VALUES
('Thêm sản phẩm cho web Trái Cây Lâm Thành', 'https://lamthanhfruit.myshopify.com/admin', 'Khánh Linh', '0354777188', 3500000.00, '2026-03-10', 'doing', 'Đã thanh toán 50%. Link drive: https://docs.google.com/spreadsheets/d/1x3m5-iiqPx-CJDO6vue004HKsBld5z_mLPtObNHhOYA/edit?pli=1&gid=1832823340#gid=1832823340', 'https://lamthanhhimex.mysapo.net/admin', '0354777188', 'D@ngminhlong1512'),
('Onelaw Code section tài liệu kèm iframe view', 'https://onelawvn.com/adminxxxx', 'Onelaw', 'N/A', 1500000.00, '2026-03-05', 'testing', 'Thêm section tài liệu và iframe view...', 'https://onelawvn.com/adminxxxx', 'admin', 'password123'),
('Thiết kế web Nam Việt Food Land', 'https://namviethoodland.com/nam-login', 'Anh Nguyễn Sư', 'N/A', 4000000.00, '2026-03-03', 'testing', 'Thiết kế website thương mại điện tử cho Nam Việt Food Land', 'N/A', 'N/A', 'N/A'),
('Hỗ trợ chị Hạnh xử lý web Phú Thành', 'https://phuthanh.net/phu-admin', 'Phú Thành', 'N/A', 1000000.00, '2026-03-05', 'done', 'Hỗ trợ xử lý các lỗi trên website Phú Thành', 'https://phuthanh.net/phu-admin', 'admin', 'admin123'),
('Sayoung - Đăng ký website với Bộ Công Thương', 'https://dichvucong.moit.gov.vn/Login', 'Sayoung', 'N/A', 2500000.00, '2026-01-25', 'done', 'Đăng ký website thương mại điện tử với Bộ Công Thương', 'N/A', 'N/A', 'N/A'),
('Thiết kế Landing cho Onelaw.vn', 'https://onelaw.vn/adminxxxx', 'A Hùng', 'N/A', 1000000.00, '2026-01-24', 'done', 'Thiết kế landing page cho Onelaw.vn', 'https://onelaw.vn/adminxxxx', 'admin', 'admin123'),
('Thiết kế web Pearlcenter', 'https://pearlcenter.vn/pro-login', 'A Hùng', 'N/A', 5000000.00, '2026-01-22', 'done', 'Thiết kế website cho PearlCenter', 'https://pearlcenter.vn/pro-login', 'admin', 'admin123');

INSERT INTO `hostings` (`name`, `domain`, `provider`, `price`, `reg_date`, `exp_date`, `usage_period`) VALUES
('Photoeditor 24h', 'https://photoeditor24h.com/', 'iNet', 200000.00, '2025-04-12', '2026-04-12', '1 năm'),
('Hosting Sơn TREX', 'https://sontrex.vn/', 'iNet', 200000.00, '2025-05-07', '2026-05-07', '1 năm'),
('Sayoung', 'https://sayoung.vn/', 'iNet', 200000.00, '2021-05-20', '2026-05-20', '5 năm'),
('BĐS Yên Thủy', 'https://vietnamrussia.com.vn/', 'iNet', 200000.00, '2025-06-14', '2026-06-14', '1 năm'),
('Giấy Sao Mai', 'http://thesaomaigroup.com/', 'iNet', 200000.00, '2025-07-11', '2026-07-11', '1 năm'),
('Sơn KAZUKI', 'https://sonkazuki.com/', 'iNet', 200000.00, '2025-07-18', '2026-07-18', '1 năm'),
('Pomaxx', 'https://pomaxx.vn/', 'iNet', 200000.00, '2024-07-24', '2026-07-24', '2 năm'),
('VinaLink', 'https://vinalink.com/', 'Mắt Bão', 200000.00, '2025-08-10', '2026-08-10', '1 năm'),
('TechBase', 'https://techbase.vn/', 'PA Việt Nam', 200000.00, '2024-09-15', '2026-09-15', '2 năm'),
('GreenWeb', 'https://greenweb.io/', 'iNet', 200000.00, '2025-10-20', '2026-10-20', '1 năm'),
('FastDev', 'https://fastdev.com.vn/', 'Azdigi', 200000.00, '2023-11-05', '2026-11-05', '3 năm'),
('Old Project', 'https://oldproject.com/', 'iNet', 200000.00, '2025-01-01', '2026-01-01', '1 năm');

SET FOREIGN_KEY_CHECKS = 1;
