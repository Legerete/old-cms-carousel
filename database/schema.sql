-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `carousel`;
CREATE TABLE `carousel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `status` enum('ok','del') CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL DEFAULT 'ok',
  `show_navigation` int(1) NOT NULL DEFAULT '1',
  `show_header` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `carousel_item`;
CREATE TABLE `carousel_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carousel_id` int(11) NOT NULL,
  `attachment_id` int(11) DEFAULT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_czech_ci,
  `text2` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
  `link_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
  `position` int(3) DEFAULT NULL,
  `status` enum('ok','del') NOT NULL DEFAULT 'ok',
  PRIMARY KEY (`id`),
  KEY `attachment_id` (`attachment_id`),
  KEY `carousel_id` (`carousel_id`),
  CONSTRAINT `carousel_item_ibfk_3` FOREIGN KEY (`attachment_id`) REFERENCES `attachment` (`id`),
  CONSTRAINT `carousel_item_ibfk_4` FOREIGN KEY (`carousel_id`) REFERENCES `carousel` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2016-08-05 11:22:22