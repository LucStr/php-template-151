DROP DATABASE IF EXISTS browsergame;
CREATE DATABASE browsergame;
USE browsergame;
-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `army`;
CREATE TABLE `army` (
  `armyId` int(11) NOT NULL AUTO_INCREMENT,
  `bigMeeleship` int(11) DEFAULT NULL,
  `smallMeeleship` int(11) DEFAULT NULL,
  `bigRangedship` int(11) DEFAULT NULL,
  `smallRangedship` int(11) DEFAULT NULL,
  `userId` int(11) NOT NULL,
  `homevillageId` int(11) NOT NULL,
  PRIMARY KEY (`armyId`),
  KEY `fk_army_user1_idx` (`userId`),
  KEY `fk_army_village1_idx` (`homevillageId`),
  CONSTRAINT `fk_army_user1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_army_village1` FOREIGN KEY (`homevillageId`) REFERENCES `village` (`villageId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `attack`;
CREATE TABLE `attack` (
  `attackId` int(11) NOT NULL,
  `sendTime` timestamp NULL DEFAULT NULL,
  `movementTime` int(11) DEFAULT NULL,
  `armyId` int(11) NOT NULL,
  `targetvillageId` int(11) NOT NULL,
  PRIMARY KEY (`attackId`),
  KEY `fk_attack_army1_idx` (`armyId`),
  KEY `fk_attack_village1_idx` (`targetvillageId`),
  CONSTRAINT `fk_attack_army1` FOREIGN KEY (`armyId`) REFERENCES `army` (`armyId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_attack_village1` FOREIGN KEY (`targetvillageId`) REFERENCES `village` (`villageId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `build`;
CREATE TABLE `build` (
  `buildId` int(11) NOT NULL AUTO_INCREMENT,
  `building` varchar(45) DEFAULT NULL,
  `startTime` timestamp NULL DEFAULT NULL,
  `endTime` timestamp NULL DEFAULT NULL,
  `villageId` int(11) NOT NULL,
  `seconds` int(11) NOT NULL,
  `isDone` bit(1) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`buildId`),
  KEY `villageId` (`villageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `reportId` int(11) NOT NULL,
  `attackerBigMeeleship` int(11) DEFAULT NULL,
  `attackerSmallMeeleship` int(11) DEFAULT NULL,
  `attackerBigRangedship` int(11) DEFAULT NULL,
  `attackerSmallRangedship` int(11) DEFAULT NULL,
  `attackerBigMeeleshipLost` int(11) DEFAULT NULL,
  `attackerSmallMeeleshipLost` int(11) DEFAULT NULL,
  `attackerBigRangedshipLost` int(11) DEFAULT NULL,
  `attackerSmallRangedshipLost` int(11) DEFAULT NULL,
  `defenderBigMeeleship` int(11) DEFAULT NULL,
  `defenderSmallMeeleship` int(11) DEFAULT NULL,
  `defenderBigRangedship` int(11) DEFAULT NULL,
  `defenderSmallRangedship` int(11) DEFAULT NULL,
  `defenderBigMeeleshipLost` int(11) DEFAULT NULL,
  `defenderSmallMeeleshipLost` int(11) DEFAULT NULL,
  `defenderBigRangedshipLost` int(11) DEFAULT NULL,
  `defenderSmallRangedshipLost` int(11) DEFAULT NULL,
  `fightTime` datetime DEFAULT NULL,
  `watchtowerlvl` int(11) DEFAULT NULL,
  `attackerId` int(11) NOT NULL,
  `defenderId` int(11) NOT NULL,
  PRIMARY KEY (`reportId`),
  KEY `fk_report_user1_idx` (`attackerId`),
  KEY `fk_report_user2_idx` (`defenderId`),
  CONSTRAINT `fk_report_user1` FOREIGN KEY (`attackerId`) REFERENCES `user` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_report_user2` FOREIGN KEY (`defenderId`) REFERENCES `user` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `activated` tinyint(4) NOT NULL DEFAULT '0',
  `confirmationUUID` tinytext NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `village`;
CREATE TABLE `village` (
  `villageId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `mainlvl` int(11) NOT NULL DEFAULT '0',
  `woodlvl` int(11) NOT NULL DEFAULT '0',
  `stonelvl` int(11) NOT NULL DEFAULT '0',
  `shipyardlvl` int(11) NOT NULL DEFAULT '0',
  `farmlvl` int(11) NOT NULL DEFAULT '0',
  `towerlvl` int(11) NOT NULL DEFAULT '0',
  `wood` decimal(10,0) NOT NULL DEFAULT '0',
  `stone` decimal(10,0) NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  `lastUpdate` datetime DEFAULT NULL,
  `userId` int(11) NOT NULL,
  `coordY` int(11) DEFAULT NULL,
  `coordX` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  PRIMARY KEY (`villageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2017-06-19 19:49:49

