SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `vaiicko_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `vaiicko_db`;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
                            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `role` ENUM('admin','trainer','customer','reception') NOT NULL DEFAULT 'customer',
                            `first_name` varchar(20) NOT NULL,
                            `last_name` varchar(20) NOT NULL,
                            `email` varchar(50) NOT NULL,
                            `password` VARCHAR(255) NOT NULL,
                            `credit` DECIMAL(10,2) NOT NULL DEFAULT 0,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `group_classes`;
CREATE TABLE `group_classes` (
                                 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                 `name` varchar(100) NOT NULL,
                                 `start_datetime` datetime NOT NULL,
                                 `duration_minutes` int(10) unsigned ZEROFILL NOT NULL,
                                 `trainer_id` int(10) unsigned NOT NULL,
                                 `capacity` int(10) unsigned NOT NULL DEFAULT 0,
                                 `description` VARCHAR(65535) NULL,
                                 PRIMARY KEY (`id`),
                                 KEY `fk_groupclass_trainer` (`trainer_id`),
                                 CONSTRAINT `fk_groupclass_trainer`
                                     FOREIGN KEY (`trainer_id`) REFERENCES `accounts` (`id`)
                                         ON DELETE CASCADE ON UPDATE CASCADE,
                                 CONSTRAINT `chk_group_classes_capacity`
                                     CHECK (`capacity` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `group_class_participants`;
CREATE TABLE `group_class_participants` (
                                            `customer_id` int(10) unsigned NOT NULL,
                                            `group_class_id` int(10) unsigned NOT NULL,
                                            `customer_note` varchar(250) DEFAULT NULL,
                                            PRIMARY KEY (`customer_id`,`group_class_id`),
                                            KEY `fk_participant_groupclass` (`group_class_id`),
                                            CONSTRAINT `fk_participant_customer`
                                                FOREIGN KEY (`customer_id`) REFERENCES `accounts` (`id`)
                                                    ON DELETE CASCADE ON UPDATE CASCADE,
                                            CONSTRAINT `fk_participant_groupclass`
                                                FOREIGN KEY (`group_class_id`) REFERENCES `group_classes` (`id`)
                                                    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `passes`;
CREATE TABLE `passes` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `user_id` int(11) unsigned NOT NULL,
                          `purchase_date` datetime NOT NULL,
                          `expiration_date` date NOT NULL,
                          PRIMARY KEY (`id`),
                          KEY `fk_pass_user` (`user_id`),
                          CONSTRAINT `fk_pass_user`
                              FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`)
                                  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `trainings`;
CREATE TABLE `trainings` (
                             `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                             `customer_id` int(11) unsigned NOT NULL,
                             `trainer_id` int(11) unsigned NOT NULL,
                             `purchase_date` datetime NOT NULL,
                             `start_date` datetime NOT NULL,
                             PRIMARY KEY (`id`),
                             KEY `fk_training_customer` (`customer_id`),
                             KEY `fk_training_trainer` (`trainer_id`),
                             CONSTRAINT `fk_training_customer`
                                 FOREIGN KEY (`customer_id`) REFERENCES `accounts` (`id`)
                                     ON DELETE CASCADE ON UPDATE CASCADE,
                             CONSTRAINT `fk_training_trainer`
                                 FOREIGN KEY (`trainer_id`) REFERENCES `accounts` (`id`)
                                     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `accounts`
(`id`, `role`, `first_name`, `last_name`, `email`, `password`, `credit`)
VALUES
    (1, 'admin', 'Jozef', 'Piaček', 'admin@admin.sk',
     '$2y$10$GRA8D27bvZZw8b85CAwRee9NH5nj4CQA6PDFMc90pN9Wi4VAWq3yq', 0);

ALTER TABLE `accounts`
    ADD CONSTRAINT `chk_credit_nonnegative`
        CHECK (`credit` >= 0);

-- Tabuľka `images`

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

ALTER TABLE `images` CHANGE COLUMN `use` `img_use` ENUM('trainer','gallery') NOT NULL DEFAULT 'gallery';

-- Úprava tabuľky `trainer_info`

ALTER TABLE `trainer_info`
    ADD COLUMN `purchase_cost` DECIMAL(10,2) NOT NULL DEFAULT 20.00 AFTER `image_id`;
