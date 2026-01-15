SET NAMES utf8mb4;
USE `vaiicko_db`;

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_gallery_created_by` (`created_by`),
  CONSTRAINT `fk_gallery_created_by` FOREIGN KEY (`created_by`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

