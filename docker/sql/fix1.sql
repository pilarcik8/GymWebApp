ALTER TABLE `accounts` MODIFY `password` VARCHAR(255) NOT NULL;
ALTER TABLE `accounts` MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `accounts` MODIFY COLUMN `role` ENUM('admin','trainer','customer','reception') NOT NULL DEFAULT 'customer';

ALTER TABLE accounts
    MODIFY COLUMN credit DECIMAL(10,2) NOT NULL DEFAULT 0,
    ADD CONSTRAINT chk_credit_nonnegative CHECK (credit >= 0);

