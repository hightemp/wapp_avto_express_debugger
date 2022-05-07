CREATE TABLE `tdebugmessages` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tdebugmessages` ADD `timestamp` INTEGER;
ALTER TABLE `tdebugmessages` ADD `type` TEXT;
ALTER TABLE `tdebugmessages` ADD `file` TEXT;
ALTER TABLE `tdebugmessages` ADD `backtrace` TEXT;
ALTER TABLE `tdebugmessages` ADD `message` TEXT;
ALTER TABLE `tdebugmessages` ADD `vars` TEXT;
ALTER TABLE `tdebugmessages` ADD `tdebugfiles_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`timestamp`,`type`,`file`,`backtrace`,`message`,`vars`,`tdebugfiles_id`);;
CREATE TABLE `tdebugmessages` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`timestamp` INTEGER ,`type` TEXT ,`file` TEXT ,`backtrace` TEXT ,`message` TEXT ,`vars` TEXT ,`tdebugfiles_id` INTEGER    );;
CREATE INDEX index_foreignkey_tdebugmessages_tdebugfiles ON `tdebugmessages` (tdebugfiles_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`timestamp`,`type`,`file`,`backtrace`,`message`,`vars`,`tdebugfiles_id`);;
CREATE TABLE `tdebugmessages` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`timestamp` INTEGER ,`type` TEXT ,`file` TEXT ,`backtrace` TEXT ,`message` TEXT ,`vars` TEXT ,`tdebugfiles_id` INTEGER   , FOREIGN KEY(`tdebugfiles_id`)
						 REFERENCES `tdebugfiles`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tdebugmessages_tdebugfiles ON `tdebugmessages` (tdebugfiles_id) ;
