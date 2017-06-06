SET NAMES utf8;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `olm_exams`;
CREATE TABLE `olm_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_exams_history`;
CREATE TABLE `olm_exams_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_generations`;
CREATE TABLE `olm_generations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_mcqs`;
CREATE TABLE `olm_mcqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` int(11) NOT NULL,
  `raw` varchar(20000) COLLATE utf8_bin NOT NULL,
  `rating` int(11) NOT NULL,
  `original` tinyint(1) NOT NULL,
  `complete` tinyint(1) NOT NULL,
  `generation` int(11) NOT NULL,
  `discussion` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_mcqs_history`;
CREATE TABLE `olm_mcqs_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `module` int(11) NOT NULL,
  `raw` varchar(20000) COLLATE utf8_bin NOT NULL,
  `rating` int(11) NOT NULL,
  `original` tinyint(1) NOT NULL,
  `complete` tinyint(1) NOT NULL,
  `generation` int(11) NOT NULL,
  `discussion` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_mcqs_rated`;
CREATE TABLE `olm_mcqs_rated` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `rated` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_modules`;
CREATE TABLE `olm_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `code` varchar(4) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_modules_history`;
CREATE TABLE `olm_modules_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `code` varchar(4) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_protocolls`;
CREATE TABLE `olm_protocolls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`exam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_protocolls_history`;
CREATE TABLE `olm_protocolls_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `exam` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_sessions`;
CREATE TABLE `olm_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `questions` varchar(15000) COLLATE utf8_bin NOT NULL,
  `answers` varchar(2500) COLLATE utf8_bin NOT NULL,
  `status` varchar(2500) COLLATE utf8_bin NOT NULL,
  `current` int(11) NOT NULL,
  `answered` int(11) NOT NULL,
  `correct` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_texts`;
CREATE TABLE `olm_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(200) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  `help` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_users`;
CREATE TABLE `olm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `password` varchar(100) COLLATE utf8_bin NOT NULL,
  `salt` varchar(100) COLLATE utf8_bin NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `account_non_expired` tinyint(1) NOT NULL,
  `credentials_non_expired` tinyint(1) NOT NULL,
  `account_non_locked` tinyint(1) NOT NULL,
  `roles` varchar(20) COLLATE utf8_bin NOT NULL,
  `login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `olm_users` (`id`, `username`, `email`, `password`, `salt`, `enabled`, `account_non_expired`, `credentials_non_expired`, `account_non_locked`, `roles`) VALUES
(1,	'root',	'root@charite.de',	'$2y$13$iYODQBn6rIBfFZ80qfBTIe6qOagqFTbC0vcnB42fahux/0JIEa1Oe',	'',	1,	1,	1,	1,	'ROLE_ADMIN,ROLE_USER'),
(6,	'user',	'user@charite.de',	'$2y$13$iYODQBn6rIBfFZ80qfBTIe6qOagqFTbC0vcnB42fahux/0JIEa1Oe',	'',	1,	1,	1,	1,	'ROLE_USER');
