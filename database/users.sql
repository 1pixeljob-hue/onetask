CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) DEFAULT 'Admin',
  `role` VARCHAR(20) DEFAULT 'admin',
  `avatar` VARCHAR(255) DEFAULT '/images/default-avatar.png',
  `remember_token` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm tài khoản admin mặc định: quydev / 1pixel.job@gmail.vn / Spencil@123
INSERT INTO `users` (`username`, `email`, `password`, `name`, `role`) 
VALUES ('quydev', '1pixel.job@gmail.vn', '$2y$10$/WZRJ0j1o6HM8.k.ytoMq.X5ef3JGvq4JzGSFIZqr/evVnwI0bZcO', 'Quy Dev', 'admin');
