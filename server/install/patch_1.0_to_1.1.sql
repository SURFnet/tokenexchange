ALTER TABLE `token` ADD COLUMN `devicefamily` varchar(10) NOT NULL;
UPDATE `token` SET `devicefamily` = 'ios' WHERE `devicefamily` IS NULL;
