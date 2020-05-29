#roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
	`role` VARCHAR(255) NOT NULL UNIQUE ,
	PRIMARY KEY (`role`)) ENGINE = InnoDB;
INSERT INTO `roles` (`role`) VALUES ('user'), ('admin');

#users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
	`username` VARCHAR(255) NOT NULL UNIQUE ,
	`role` VARCHAR(255) NOT NULL DEFAULT 'user' ,
	`password` VARCHAR(255) ,
	`enabled` BOOLEAN NOT NULL DEFAULT TRUE ,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	PRIMARY KEY (`username`) ,
	FOREIGN KEY (`role`)
	REFERENCES roles(`role`)
	ON DELETE RESTRICT
) ENGINE = InnoDB;
INSERT INTO `users` (`username`, `role`, `password`, `enabled`, `created_at`, `updated_at`) VALUES 
	('user', 'user', '$2y$10$X0pAc2paOVlZY1dIVDdIUOOwgy.zse5M8v1uvvfSmQ/0.yjFzpWge', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
	('admin', 'admin', '$2y$10$X0pAc2paOVlZY1dIVDdIUOkywNB.hBXrJ2drEbniQZlbmMTqXT3Oe', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);