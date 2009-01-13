CREATE DATABASE IF NOT EXISTS storefront;
USE storefront;

--
-- Definition of table `storefront`.`category`
--

DROP TABLE IF EXISTS `storefront`.`category`;
CREATE TABLE  `storefront`.`category` (
  `categoryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `parentId` int(10) unsigned NOT NULL,
  `ident` varchar(200) NOT NULL,
  PRIMARY KEY  (`categoryId`),
  UNIQUE KEY `ident` (`ident`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Definition of table `storefront`.`categoryImage`
--

DROP TABLE IF EXISTS `storefront`.`categoryImage`;
CREATE TABLE  `storefront`.`categoryImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `thumbnail` varchar(200) NOT NULL,
  `full` varchar(200) NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`imageId`),
  KEY `categoryImage` (`categoryId`),
  CONSTRAINT `categoryImage` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Definition of table `storefront`.`product`
--

DROP TABLE IF EXISTS `storefront`.`product`;
CREATE TABLE  `storefront`.`product` (
  `productId` int(10) unsigned NOT NULL auto_increment,
  `categoryId` int(10) unsigned NOT NULL,
  `ident` varchar(56) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `shortDescription` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discountPercent` int(3) NOT NULL,
  `taxable` enum('Yes','No') NOT NULL,
  `deliveryMethod` enum('Mail','Download') NOT NULL,
  `stockStatus` enum('InStock','PreOrder','Discontinued','Unavailable') NOT NULL,
  PRIMARY KEY  (`productId`),
  KEY `category` (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Definition of table `storefront`.`productImage`
--

DROP TABLE IF EXISTS `storefront`.`productImage`;
CREATE TABLE  `storefront`.`productImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `productId` int(10) unsigned NOT NULL,
  `thumbnail` varchar(200) NOT NULL,
  `full` varchar(200) NOT NULL,
  PRIMARY KEY  (`imageId`),
  KEY `productId` (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `storefront`.`user`
--

DROP TABLE IF EXISTS `storefront`.`user`;
CREATE TABLE  `storefront`.`user` (
  `userId` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passwd` char(40) NOT NULL,
  `salt` char(32) NOT NULL,
  `role` varchar(100) NOT NULL default 'customer',
  PRIMARY KEY  (`userId`),
  KEY `email_pass` (`email`,`passwd`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;