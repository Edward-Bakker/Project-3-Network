SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Compatible with newer MySQL versions. (After MySQL-5.5)
-- This SQL uses utf8mb4 and has CURRENT_TIMESTAMP function.
--

--
-- Creates database `battlebots` unless it already exists and uses `battlebots`
-- Default Schema
--

CREATE DATABASE IF NOT EXISTS `battlebots` DEFAULT CHARACTER SET utf8mb4;
USE `battlebots`;

--
-- Table structure for table `battlebots`
--

CREATE TABLE IF NOT EXISTS `battlebots` (
    `id`            INT NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(64) NOT NULL,
    `task`          VARCHAR(64) NOT NULL,
    `data`          VARCHAR(1024) NULL,
    `insert_time`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `last_update`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT `PK_botid` PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `apikeys`
--

CREATE TABLE IF NOT EXISTS `apikeys` (
    `id`            INT NOT NULL AUTO_INCREMENT,
    `secret`        VARCHAR(64) NOT NULL,
    `botid`         INT NULL,
    `admin`         ENUM('0','1') NOT NULL DEFAULT '0',

    CONSTRAINT `PK_keyid` PRIMARY KEY (`id`),
    CONSTRAINT `FK_botid` FOREIGN KEY `FK_botid` (`botid`)
        REFERENCES `battlebots` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `ratelimit`
--

CREATE TABLE IF NOT EXISTS `ratelimit` (
    `id`            INT NOT NULL AUTO_INCREMENT,
    `keyid`         INT NOT NULL,
    `last_update`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT `PK_ratelimitid` PRIMARY KEY (`id`),
    UNIQUE KEY `unique_keyid` (`keyid`),
    CONSTRAINT `FK_keyid` FOREIGN KEY `FK_keyid` (`keyid`)
        REFERENCES `apikeys` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
--
-- Creates default user `battlebots_user` with password `changeme` unless it already exists
-- Granting permissions to user `battlebots_user`, created below
-- Reloads the privileges from the grant tables in the MySQL system database.
--

CREATE USER IF NOT EXISTS `battlebots_user`@`localhost` IDENTIFIED BY 'changeme';
GRANT SELECT, UPDATE, INSERT ON `battlebots`.* TO 'battlebots_user'@'localhost';
FLUSH PRIVILEGES;
