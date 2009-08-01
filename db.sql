CREATE DATABASE  `bltippdb`.`bltippdb` /*!40100 DEFAULT CHARACTER SET latin1 */

CREATE TABLE  `bltippdb`.`benutzer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `picture` blob,
  `role` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8

CREATE TABLE  `bltippdb`.`saisons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichner` text,
  `beginn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ende` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

CREATE TABLE  `bltippdb`.`spiele` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spieltag_id` int(11) NOT NULL,
  `t1` text,
  `t2` text,
  `ergebnis` text,
  `zeit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`spieltag_id`),
  KEY `spieltagFK` (`spieltag_id`),
  CONSTRAINT `spieltagFK` FOREIGN KEY (`spieltag_id`) REFERENCES `spieltage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8

CREATE TABLE  `bltippdb`.`spieltage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `saison_id` int(11) NOT NULL DEFAULT '1',
  `nr` int(11) DEFAULT NULL,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `saisonFK` (`saison_id`),
  CONSTRAINT `saisonFK` FOREIGN KEY (`saison_id`) REFERENCES `saisons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8

CREATE TABLE  `bltippdb`.`tipp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `spiel_id` int(11) NOT NULL,
  `ergebnis` text,
  `punkte` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userFK` (`user_id`),
  KEY `spielFK` (`spiel_id`),
  CONSTRAINT `spielFK` FOREIGN KEY (`spiel_id`) REFERENCES `spiele` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userFK` FOREIGN KEY (`user_id`) REFERENCES `benutzer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8