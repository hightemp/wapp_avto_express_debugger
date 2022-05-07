CREATE TABLE `tprojects` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tprojects` ADD `created_at` NUMERIC;
ALTER TABLE `tprojects` ADD `updated_at` NUMERIC;
ALTER TABLE `tprojects` ADD `timestamp` INTEGER;
ALTER TABLE `tprojects` ADD `name` TEXT;
ALTER TABLE `tprojects` ADD `description` TEXT;
ALTER TABLE `tprojects` ADD `path` TEXT;
ALTER TABLE `tprojects` ADD `path_to_debug_log` TEXT;
CREATE TABLE `tdebugfiles` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tdebugfiles` ADD `created_at` NUMERIC;
ALTER TABLE `tdebugfiles` ADD `updated_at` NUMERIC;
ALTER TABLE `tdebugfiles` ADD `timestamp` INTEGER;
ALTER TABLE `tdebugfiles` ADD `file_name` INTEGER;
ALTER TABLE `tdebugfiles` ADD `name` NUMERIC;
ALTER TABLE `tdebugfiles` ADD `tprojects_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`created_at`,`updated_at`,`timestamp`,`file_name`,`name`,`tprojects_id`);;
CREATE TABLE `tdebugfiles` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`created_at` NUMERIC ,`updated_at` NUMERIC ,`timestamp` INTEGER ,`file_name` INTEGER ,`name` NUMERIC ,`tprojects_id` INTEGER    );;
CREATE INDEX index_foreignkey_tdebugfiles_tprojects ON `tdebugfiles` (tprojects_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`created_at`,`updated_at`,`timestamp`,`file_name`,`name`,`tprojects_id`);;
CREATE TABLE `tdebugfiles` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`created_at` NUMERIC ,`updated_at` NUMERIC ,`timestamp` INTEGER ,`file_name` INTEGER ,`name` NUMERIC ,`tprojects_id` INTEGER   , FOREIGN KEY(`tprojects_id`)
						 REFERENCES `tprojects`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tdebugfiles_tprojects ON `tdebugfiles` (tprojects_id) ;
