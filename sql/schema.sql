SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `category` (
  `categoryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `parentId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB;

CREATE TABLE `categoryImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `thumbnail` varchar(200) NOT NULL,
  `full` varchar(200) NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`imageId`),
  KEY `categoryImage` (`categoryId`),
  CONSTRAINT `categoryImage` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `product` (
  `productId` int(10) unsigned NOT NULL auto_increment,
  `ident` varchar(56) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `shortDescription` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discountPercent` int(3) NOT NULL,
  `taxable` enum('Yes','No') NOT NULL,
  `deliveryMethod` enum('Mail','Download') NOT NULL,
  `stockStatus` enum('InStock','PreOrder','Discontinued','Unavailable') NOT NULL,
  PRIMARY KEY  (`productId`)
) ENGINE=InnoDB;

CREATE TABLE `productImage` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `thumbnail` varchar(200) NOT NULL,
  `full` varchar(200) NOT NULL,
  PRIMARY KEY  (`imageId`),
  CONSTRAINT `fk_productImageMap` FOREIGN KEY (`imageId`) REFERENCES `productImageMap` (`imageId`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `productImageMap` (
  `productId` int(10) unsigned NOT NULL,
  `imageId` int(10) unsigned NOT NULL,
  PRIMARY KEY  USING BTREE (`productId`,`imageId`),
  KEY `fk_productImage` (`imageId`),
  CONSTRAINT `fk_product` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE CASCADE,
  CONSTRAINT `fk_productImage` FOREIGN KEY (`imageId`) REFERENCES `productImage` (`imageId`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `user` (
  `userId` int(10) unsigned NOT NULL,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `salt` char(10) NOT NULL,
  PRIMARY KEY  (`userId`),
  KEY `email_pass` (`email`,`passwd`),
  KEY `email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE  `userAddress` (
  `addressId` int(10) unsigned NOT NULL auto_increment,
  `userId` int(10) unsigned NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `street1` varchar(200) NOT NULL,
  `street2` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `stateOrProvince` varchar(200) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(200) NOT NULL,
  `isDefault` enum('Yes','No') NOT NULL,
  PRIMARY KEY  (`addressId`),
  KEY `ind_userid` (`userId`),
  CONSTRAINT `fk_user` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS=1;