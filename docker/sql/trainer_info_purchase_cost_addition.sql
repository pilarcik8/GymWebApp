ALTER TABLE `trainer_info`
    ADD COLUMN `purchase_cost` DECIMAL(10,2) NOT NULL DEFAULT 20.00 AFTER `image_id`;
